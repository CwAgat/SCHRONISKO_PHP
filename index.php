
<?php
session_start();
require_once 'db_connect.php';

$allowedPages = [
    'home' => 'Strona główna',
    'oferta' => 'Oferta',
    'dlaturysty' => 'Dla Turysty',
    'galeria' => 'Galeria',
    'kontakt' => 'Kontakt',
    'admin-logowanie' => 'Zaloguj się',
    'admin' => 'Panel administratora',
    'admin-galeria' => 'Edytuj galerię',
    'admin-posty' => 'Edytuj posty',
    'admin-rezerwacje' => 'Rezerwacje',
    'admin-logout' => 'Wylogowanie'
];

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if (array_key_exists($page, $allowedPages)) {
    $pageTitle = $allowedPages[$page];
    $filePath = "pages/" . $page . ".php";
} else {
    $filePath = null;
    $pageTitle = "Błąd 404";
}

include 'header.php';

if ($filePath && file_exists($filePath)) {
    include $filePath;
} else {
    echo "<h1> Błąd 404 </h1> <p>Strona o podanym adresie nie istnieje </p>";
}

include 'footer.php'; ?>