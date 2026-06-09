<?php
// Ten plik JavaScript wywołuje przez fetch()
// i dostaje z powrotem JSON z listą wolnych pokoi dla podanego terminu.
// Nie generuje HTML, tylko dane.

require_once dirname(__FILE__) . '/../db_connect.php';
header('Content-Type: application/json; charset=utf-8');  // info dla przeglądarki że to jest json

// daty wybrane przez użytkownika w kalendarzu
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to']   ?? '';

// zabezpieczenia czy daty są podane i czy mają poprawny format

if (!$date_from || !$date_to) {
    echo json_encode(['error' => 'Brak dat']);
    exit;
}

/// format dat
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
    echo json_encode(['error' => 'Nieprawidłowy format daty']);
    exit;
}

// wyjazd musi być po przyjeździe
if ($date_from >= $date_to) {
    echo json_encode(['error' => 'Data wyjazdu musi być późniejsza niż przyjazdu']);
    exit;
}

// nie może być w przeszłości
if ($date_from < date('Y-m-d')) {
    echo json_encode(['error' => 'Data przyjazdu nie może być w przeszłości']);
    exit;
}

// pobieramy z bazy pokoje bez rezerwacji
$stmt = mysqli_prepare($conn, "
    SELECT r.id, r.name, r.capacity
    FROM rooms r
    WHERE r.id NOT IN (
        SELECT res.room_id
        FROM reservations res
        WHERE res.status = 'confirmed'
          AND res.date_from < ?
          AND res.date_to   > ?
    )
    ORDER BY r.capacity ASC, r.id ASC
");
mysqli_stmt_bind_param($stmt, 'ss', $date_to, $date_from);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

//cennik pokojow
$priceMap = [2 => 65, 3 => 50];

$rooms = [];
while ($row = mysqli_fetch_assoc($result)) {
        $row['price_per_person'] = $priceMap[(int)$row['capacity']] ?? 0;
    $rooms[] = $row;
}

// zwracamy JSON z pasujacymi pokojami
echo json_encode(['rooms' => $rooms]);
