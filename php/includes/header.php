<?php
$pageTitle = $pageTitle ?? 'Schronisko Kamieńczyk';
$pageStyle = $pageStyle ?? '';
$currentPage = $currentPage ?? '';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/nav.css">
    <?php if ($pageStyle): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($pageStyle, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>

</head>

<body>
    <header>
        <div class="nav">
            <div class="logo">
                <img id="logo" src="img/logo.png" alt="Logo Schroniska" />
                <strong>Schronisko Kamieńczyk</strong>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="index.php" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Strona Główna</a></li>
                    <li><a href="oferta.php" class="<?= $currentPage === 'oferta' ? 'active' : '' ?>">Oferta</a></li>
                    <li><a href="dlaturysty.php" class="<?= $currentPage === 'dlaturysty' ? 'active' : '' ?>">Dla Turysty</a></li>
                    <li><a href="galeria.php" class="<?= $currentPage === 'galeria' ? 'active' : '' ?>">Galeria</a></li>
                    <li><a href="kontakt.php" class="<?= $currentPage === 'kontakt' ? 'active' : '' ?>">Kontakt</a></li>
                </ul>
            </nav>
            <button class="burger" aria-label="Menu">☰</button>
        </div>
        <nav class="menu-mobile">
            <ul>
                <li><a href="index.php">O nas</a></li>
                <li><a href="oferta.php">Oferta</a></li>
                <li><a href="dlaturysty.php">Dla turysty</a></li>
                <li><a href="galeria.php">Galeria</a></li>
                <li><a href="kontakt.php">Kontakt</a></li>
            </ul>
        </nav>
    </header>
    <!-- testowy komentarz -->