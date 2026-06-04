
<?php
$allowedPages = [
    'home' => 'Strona główna',
    'oferta' => 'Oferta',
    'dlaturysty' => 'Dla Turysty',
    'galeria' => 'Galeria',
    'kontakt' => 'Kontakt'
];

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if (array_key_exists($page, $allowedPages)) {
    $pageTitle = $allowedPages[$page];
    $filePath = "pages/" . $page . ".php";
} else {
    $filePath = null;
    $pageTitle = "Błąd 404";
}
?>

<?php include 'header.php'; ?>

<?php
if ($filePath && file_exists($filePath)) {
    include $filePath;
} else {
    echo "<h1> Błąd 404 </h1> <p>Strona o podanym adresie nie istnieje </p>";
}
?>
<?php include 'footer.php'; ?>