<?php

/** @var string $page */
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="css/nav.css">
    <?php
    $pageCss = "css/$page.css";
    if (file_exists($pageCss)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($pageCss, ENT_QUOTES, 'UTF-8') ?>">
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
                    <li><a href="home" class="<?= $page === 'home' ? 'active' : '' ?>">Strona Główna</a></li>
                    <li><a href="oferta" class="<?= $page === 'oferta' ? 'active' : '' ?>">Oferta</a></li>
                    <li><a href="dlaturysty" class="<?= $page === 'dlaturysty' ? 'active' : '' ?>">Dla Turysty</a></li>
                    <li><a href="galeria" class="<?= $page === 'galeria' ? 'active' : '' ?>">Galeria</a></li>
                    <li><a href="kontakt" class="<?= $page === 'kontakt' ? 'active' : '' ?>">Kontakt</a></li>
                </ul>
            </nav>
            <button class="burger" aria-label="Menu">☰</button>
        </div>
        <nav class="menu-mobile">
            <ul>
                <li><a href="home">Strona główna</a></li>
                <li><a href="oferta">Oferta</a></li>
                <li><a href="dlaturysty">Dla turysty</a></li>
                <li><a href="galeria">Galeria</a></li>
                <li><a href="kontakt">Kontakt</a></li>
            </ul>
        </nav>
    </header>