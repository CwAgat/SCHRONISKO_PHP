<?php
/** @var mysqli $conn */

function getMonthAvailability(mysqli $conn, int $year, int $month): array
{
    $totalRooms  = 8;
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $monthStart  = sprintf('%04d-%02d-01', $year, $month);
    $monthEnd    = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

    $stmt = mysqli_prepare($conn, "
        SELECT room_id, date_from, date_to
        FROM reservations
        WHERE status = 'confirmed'
          AND date_from <= ? AND date_to >= ?
    ");
    mysqli_stmt_bind_param($stmt, 'ss', $monthEnd, $monthStart);
    mysqli_stmt_execute($stmt);
    $reservations = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    $availability = [];
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date   = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $booked = 0;
        foreach ($reservations as $res) {
            if ($res['date_from'] <= $date && $res['date_to'] > $date) {
                $booked++;
            }
        }
        $availability[$day] = ['free' => $totalRooms - $booked, 'total' => $totalRooms];
    }
    return $availability;
}

function renderCalendar(array $availability, int $year, int $month, string $today): string
{
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDayDow = (int)date('N', mktime(0, 0, 0, $month, 1, $year));
    $monthNames  = ['', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
                    'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'];

    $html  = "<div class='calendar'>";
    $html .= "<h3 class='calendar-month'>{$monthNames[$month]} {$year}</h3>";
    $html .= "<table class='calendar-table'><thead><tr>";
    foreach (['Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So', 'Nd'] as $d) {
        $html .= "<th>{$d}</th>";
    }
    $html .= "</tr></thead><tbody><tr>";

    for ($i = 1; $i < $firstDayDow; $i++) {
        $html .= "<td class='empty'></td>";
    }

    $col = $firstDayDow;
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $isPast  = $dateStr < $today;
        $isToday = $dateStr === $today;
        $free    = $availability[$day]['free'];
        $total   = $availability[$day]['total'];

        if ($isPast) {
            $status = 'past';
        } elseif ($free === 0) {
            $status = 'full';
        } elseif ($free <= intdiv($total, 2)) {
            $status = 'limited';
        } else {
            $status = 'available';
        }

        $classes  = "day {$status}" . ($isToday ? ' today' : '');
        $dataAttr = (!$isPast && $free > 0) ? "data-date='{$dateStr}'" : '';

        $html .= "<td class='{$classes}' {$dataAttr}>";
        $html .= "<span class='day-num'>{$day}</span>";
        if (!$isPast) {
            $label = $free > 0 ? "{$free} wol." : 'brak';
            $html .= "<span class='day-free'>{$label}</span>";
        }
        $html .= "</td>";

        if ($col % 7 === 0 && $day < $daysInMonth) {
            $html .= "</tr><tr>";
        }
        $col++;
    }

    while ($col % 7 !== 1) {
        $html .= "<td class='empty'></td>";
        $col++;
    }

    $html .= "</tr></tbody></table></div>";
    return $html;
}
