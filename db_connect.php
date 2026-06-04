<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB_NAME', 'schronisko_db');

$conn = mysqli_connect(HOST, USER, PASS, DB_NAME);

if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
