<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}
?>

<section class="admin-dashboard">

    <div class="admin-header">
        <h1>Panel Administratora</h1>
        <p>Witaj, <?= htmlspecialchars($_SESSION['email']) ?></p>
    </div>
    <div class="admin-content">
        <div class="admin-cards">

            <a href="admin-rezerwacje" class="admin-card">
                <h2>Rezerwacje</h2>
                <p>Zarządzaj rezerwacjami gości</p>
            </a>

            <a href="admin-galeria" class="admin-card">
                <h2>Galeria</h2>
                <p>Dodawaj i usuwaj zdjęcia</p>
            </a>

            <a href="admin-posty" class="admin-card">
                <h2>Posty</h2>
                <p>Zarządzaj aktualnościami</p>
            </a>

        </div>

        <div class="stats">
            <div class="stat-box">
                <h3>Może jakieś staty z bazy???????</h3>
            </div>
        </div>
        <div>
</section>