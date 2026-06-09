<?php
/** @var mysqli $conn */


// sprawdzenie ile pokoi jest wolnych każdego dnia w podanym miesiącu i roku

function getMonthAvailability(mysqli $conn, int $year, int $month): array
{
    $totalRooms  = 8;   
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);  //ile dni w miesiacu

    // zmienne do zapytania SQL
    $monthStart  = sprintf('%04d-%02d-01', $year, $month);
    $monthEnd    = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);


    // zapytanie do tabeli rezerwacji o te które są potwierdzone
    $stmt = mysqli_prepare($conn, "
        SELECT room_id, date_from, date_to
        FROM reservations
        WHERE status = 'confirmed'
          AND date_from <= ? AND date_to >= ?
    ");
    mysqli_stmt_bind_param($stmt, 'ss', $monthEnd, $monthStart);
    mysqli_stmt_execute($stmt);
    $reservations = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    // ile pokoi jest zajętych dla kazdego dnia
    $availability = [];
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date   = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $booked = 0;

        // sprawdzanie rezerwaacji
        foreach ($reservations as $res) {
            if ($res['date_from'] <= $date && $res['date_to'] > $date) {
                $booked++;
            }
        }

        // obliczane dla kazdego dnia, ile wolnych pokoi zostalo
        $availability[$day] = ['free' => $totalRooms - $booked, 'total' => $totalRooms];
    }
    return $availability;
}

// tworzenie HTML kalendarzy

function renderCalendar(array $availability, int $year, int $month, string $today): string
{
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    //potrzebne do układu miesiąca w kalendarzu, ile pustych okienek przed jezeli np pierwszy dzień to piątek
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

    // puste komórki przed pierwszym dniem miesiąca 
    for ($i = 1; $i < $firstDayDow; $i++) {
        $html .= "<td class='empty'></td>";
    }

    // info o kolumnie najpierw jest ustawiona na pierwszy dzień miesiąca potem zwiększana w pętli
    $col = $firstDayDow;

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);

        $isPast  = $dateStr < $today;   
        $isToday = $dateStr === $today; 
        $free    = $availability[$day]['free'];
        $total   = $availability[$day]['total'];

        // statusy potrzebne do cssa
        if ($isPast) {
            $status = 'past';       
        } elseif ($free === 0) {
            $status = 'full';       
        } elseif ($free <= intdiv($total, 2)) {
            $status = 'limited';    
        } else {
            $status = 'available';  
        }

        // tez klasy do cssa: dzień, status i dodatkowo today jeśli to jest dzisiejszy dzień
        $classes  = "day {$status}" . ($isToday ? ' today' : '');

        // atrybut potrzebny pozniej do js, tylko dla klikalnych dni
        $dataAttr = (!$isPast && $free > 0) ? "data-date='{$dateStr}'" : '';

        $html .= "<td class='{$classes}' {$dataAttr}>";
        $html .= "<span class='day-num'>{$day}</span>";  // numer dnia

        // ile miejsc wolnych/brak, tylko dla klikalnych dni
        if (!$isPast) {
            $label = $free > 0 ? "{$free} wol." : 'brak';
            $html .= "<span class='day-free'>{$label}</span>";
        }
        $html .= "</td>";

        // łamanie na 7 dniu tygodnia
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
