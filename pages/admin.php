<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */

// statystyki rezerwacji
$stats = $conn->query("
    SELECT
        SUM(status = 'confirmed') AS confirmed,
        SUM(status = 'pending')   AS pending,
        SUM(status = 'cancelled') AS cancelled
    FROM reservations
")->fetch_assoc();

// ilość nieprzeczytanych wiadomości
$unreadCount = $conn->query("SELECT COUNT(*) AS cnt FROM contact_messages WHERE is_read = 0")->fetch_assoc()['cnt'];


?>

<section class="admin-dashboard">
    <div class="admin-header">
        <h1>Panel Administratora</h1>
        <p>Witaj, <?= htmlspecialchars($_SESSION['email']) ?></p>
    </div>

    <div class="admin-content">

        <!-- kafelki -->
        <div class="admin-cards">
            <a href="admin-rezerwacje" class="admin-card">
                <h2>Rezerwacje</h2>
                <p>Zarządzaj rezerwacjami gości</p>
            </a>
            <a href="admin-kontakt" class="admin-card">
                <h2>Wiadomości</h2>
                <p>Przeglądaj wiadomości kontaktowe</p>
                <?php if ($unreadCount > 0): ?>
                    <span class="card-badge"><?= (int)$unreadCount ?> nowych</span>
                <?php endif; ?>
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

        <!-- statystyki rezerwacji -->
        <h2 class="section-title">Rezerwacje</h2>
        <div class="stats-grid">
            <div class="stat-box stat-pending">
                <span class="stat-number"><?= (int)($stats['pending'] ?? 0) ?></span>
                <span class="stat-label">Oczekujące</span>
            </div>
            <div class="stat-box stat-confirmed">
                <span class="stat-number"><?= (int)($stats['confirmed'] ?? 0) ?></span>
                <span class="stat-label">Potwierdzone</span>
            </div>
            <div class="stat-box stat-cancelled">
                <span class="stat-number"><?= (int)($stats['cancelled'] ?? 0) ?></span>
                <span class="stat-label">Anulowane</span>
            </div>
            <div class="stat-box stat-messages">
                <span class="stat-number"><?= (int)$unreadCount ?></span>
                <span class="stat-label">Nowe wiadomości</span>
            </div>
        </div>

    </div>
</section>
