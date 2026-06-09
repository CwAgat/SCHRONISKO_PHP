<?php
/** @var mysqli $conn */

// dołączenie plików z funkcjami i formularzem
require_once 'rezerwacje-formularz.php';
require_once 'rezerwacje-funkcje.php';

// dzisiejsza data jako string 
$today     = date('Y-m-d');

// obecny i następny miesiąc żeby gość widział
$now       = new DateTime();
$nextMonth = (new DateTime())->modify('+1 month');

// dostepnosc dla obu miesiecy
$avail1 = getMonthAvailability($conn, (int)$now->format('Y'), (int)$now->format('m'));
$avail2 = getMonthAvailability($conn, (int)$nextMonth->format('Y'), (int)$nextMonth->format('m'));
?>

<section class="rezerwacje-section">
    <h1>Rezerwacje</h1>

    <?php if ($formSuccess): ?>
        <div class="alert alert-success">
            <strong>Rezerwacja złożona!</strong> Skontaktujemy się z Tobą wkrótce w celu potwierdzenia.
        </div>
    <?php endif; ?>

    <?php if ($formError): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <p class="rezerwacje-intro">
        Wybierz datę przyjazdu i wyjazdu na kalendarzu, a następnie wypełnij formularz rezerwacji.
        Logowanie nie jest wymagane.
    </p>

    <div class="calendar-legend">
        <span class="legend-item available">Dostępny</span>
        <span class="legend-item limited">Ograniczona dostępność</span>
        <span class="legend-item full">Brak miejsc</span>
        <span class="legend-item past">Przeszłość</span>
    </div>

    <!-- dwa kalendarze obok siebie: bieżący i następny miesiąc -->
    <div class="calendars-wrapper">
        <?= renderCalendar($avail1, (int)$now->format('Y'), (int)$now->format('m'), $today) ?>
        <?= renderCalendar($avail2, (int)$nextMonth->format('Y'), (int)$nextMonth->format('m'), $today) ?>
    </div>

    
    <div id="selection-info" class="selection-info" style="display:none;">
        <p id="selection-text"></p>
        <button id="btn-reset" type="button">Zmień daty</button>
    </div>

    <div id="rooms-section" style="display:none;">
        <h2>Dostępne pokoje</h2>
        <div id="rooms-list" class="rooms-list"></div>
        <p id="no-rooms-msg" style="display:none;" class="alert alert-error">
            Brak wolnych pokoi w wybranym terminie.
        </p>
    </div>

    <!-- formularz rezerwacji — pokazywany dopiero po wyborze pokoju -->
    <form id="reservation-form" method="POST" action="" style="display:none;" class="reservation-form">
        <h2>Dane rezerwacji</h2>

        <div id="form-summary" class="form-summary"></div>

        <input type="hidden" name="room_id"   id="input-room-id">
        <input type="hidden" name="date_from" id="input-date-from">
        <input type="hidden" name="date_to"   id="input-date-to">

        <div class="form-group">
            <label for="guest_name">Imię i nazwisko</label>
            <!-- value po to żeby nie usuwało danych przy każdym odświeżeniu strony -->
            <input type="text" name="guest_name" id="guest_name"
                   value="<?= htmlspecialchars($_POST['guest_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Imie Nazwisko" required>
        </div>

        <div class="form-group">
            <label for="guest_email">Adres e-mail</label>
            <input type="email" name="guest_email" id="guest_email"
                   value="<?= htmlspecialchars($_POST['guest_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="adres@email.com" required>
        </div>

        <div class="form-group">
            <label for="guest_phone">Numer telefonu</label>
            <input type="tel" name="guest_phone" id="guest_phone"
                   value="<?= htmlspecialchars($_POST['guest_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="123 456 789" required>
        </div>

        <div class="form-group">
            <label for="num_guests">Liczba osób</label>
            <!-- max jest dodatkowo ograniczane przez JS do pojemności wybranego pokoju -->
            <input type="number" name="num_guests" id="num_guests" min="1" max="3"
                   value="<?= htmlspecialchars($_POST['num_guests'] ?? '1', ENT_QUOTES, 'UTF-8') ?>"
                   required>
        </div>

        <button type="submit" class="btn-submit">Zarezerwuj</button>
    </form>
</section>

<!-- JS obsługuje klikanie kalendarza, zapytanie przeglądarki po pokoje i wypełnianie formularza -->
<script src="js/rezerwacje.js"></script>
