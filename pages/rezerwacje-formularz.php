<?php
/** @var mysqli $conn */

// Inspiracja i źródła:
// - Wzorzec SQL do wykrywania nakładających się rezerwacji:
//   https://www.daniweb.com/programming/web-development/threads/479348/room-availability-check-with-date-range
// - Ogólna architektura systemu rezerwacji PHP/MySQL:
//   https://phpzag.com/online-hotel-reservation-system-with-php-mysql/
// - Formularz rezerwacji: https://codeshack.io/hotel-reservation-form-php/

$formSuccess = false;
$formError   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id     = (int)  trim($_POST['room_id']     ?? '');
    $guest_name  =        trim($_POST['guest_name']  ?? '');
    $guest_email =        trim($_POST['guest_email'] ?? '');
    $guest_phone =        trim($_POST['guest_phone'] ?? '');
    $num_guests  = (int)  trim($_POST['num_guests']  ?? '');
    $date_from   =        trim($_POST['date_from']   ?? '');
    $date_to     =        trim($_POST['date_to']     ?? '');

    if (!$room_id || !$guest_name || !$guest_email || !$guest_phone || !$num_guests || !$date_from || !$date_to) {
        $formError = 'Wszystkie pola są wymagane.';
    } elseif (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Podaj prawidłowy adres e-mail.';
    } elseif (!preg_match('/^[\d\s\+\-\(\)]{7,15}$/', $guest_phone)) {
        $formError = 'Podaj prawidłowy numer telefonu (cyfry, spacje, +, -).';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
        $formError = 'Nieprawidłowy format daty.';
    } elseif ($date_from < date('Y-m-d')) {
        $formError = 'Data przyjazdu nie może być w przeszłości.';
    } elseif ($date_from >= $date_to) {
        $formError = 'Data wyjazdu musi być późniejsza niż data przyjazdu.';
    } else {
        $stmtRoom = mysqli_prepare($conn, "SELECT capacity FROM rooms WHERE id = ?");
        mysqli_stmt_bind_param($stmtRoom, 'i', $room_id);
        mysqli_stmt_execute($stmtRoom);
        $roomRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtRoom));

        if (!$roomRow) {
            $formError = 'Wybrany pokój nie istnieje.';
        } elseif ($num_guests > $roomRow['capacity'] || $num_guests < 1) {
            $formError = 'Nieprawidłowa liczba osób dla wybranego pokoju.';
        } else {
            $stmtCheck = mysqli_prepare($conn, "
                SELECT COUNT(*) AS cnt FROM reservations
                WHERE room_id = ? AND status != 'cancelled'
                  AND date_from < ? AND date_to > ?
            ");
            mysqli_stmt_bind_param($stmtCheck, 'iss', $room_id, $date_to, $date_from);
            mysqli_stmt_execute($stmtCheck);
            $checkRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCheck));

            if ($checkRow['cnt'] > 0) {
                $formError = 'Wybrany pokój właśnie został zarezerwowany. Wybierz inny termin lub pokój.';
            } else {
                $stmtIns = mysqli_prepare($conn, "
                    INSERT INTO reservations (room_id, guest_name, guest_email, guest_phone, num_guests, date_from, date_to)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                mysqli_stmt_bind_param($stmtIns, 'isssiss', $room_id, $guest_name, $guest_email, $guest_phone, $num_guests, $date_from, $date_to);
                if (mysqli_stmt_execute($stmtIns)) {
                    $formSuccess = true;
                } else {
                    $formError = 'Błąd serwera. Spróbuj ponownie.';
                }
            }
        }
    }
}
