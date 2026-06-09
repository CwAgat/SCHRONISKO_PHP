<?php
/** @var mysqli $conn */

require_once 'rezerwacje-formularz.php';
require_once 'rezerwacje-funkcje.php';

$today     = date('Y-m-d');
$now       = new DateTime();
$nextMonth = (new DateTime())->modify('+1 month');

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

    <!-- legenda -->
    <div class="calendar-legend">
        <span class="legend-item available">Dostępny</span>
        <span class="legend-item limited">Ograniczona dostępność</span>
        <span class="legend-item full">Brak miejsc</span>
        <span class="legend-item past">Przeszłość</span>
    </div>

    <!-- kalendarze -->
    <div class="calendars-wrapper">
        <?= renderCalendar($avail1, (int)$now->format('Y'), (int)$now->format('m'), $today) ?>
        <?= renderCalendar($avail2, (int)$nextMonth->format('Y'), (int)$nextMonth->format('m'), $today) ?>
    </div>

    <div id="selection-info" class="selection-info" style="display:none;">
        <p id="selection-text"></p>
        <button id="btn-reset" type="button">Zmień daty</button>
    </div>

    <!-- lista dostępnych pokoi -->
    <div id="rooms-section" style="display:none;">
        <h2>Dostępne pokoje</h2>
        <div id="rooms-list" class="rooms-list"></div>
        <p id="no-rooms-msg" style="display:none;" class="alert alert-error">
            Brak wolnych pokoi w wybranym terminie.
        </p>
    </div>

    <!-- formularz -->
    <form id="reservation-form" method="POST" action="" style="display:none;" class="reservation-form">
        <h2>Dane rezerwacji</h2>

        <div id="form-summary" class="form-summary"></div>

        <input type="hidden" name="room_id"   id="input-room-id">
        <input type="hidden" name="date_from" id="input-date-from">
        <input type="hidden" name="date_to"   id="input-date-to">

        <div class="form-group">
            <label for="guest_name">Imię i nazwisko</label>
            <input type="text" name="guest_name" id="guest_name"
                   value="<?= htmlspecialchars($_POST['guest_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Jan Kowalski" required>
        </div>

        <div class="form-group">
            <label for="guest_email">Adres e-mail</label>
            <input type="email" name="guest_email" id="guest_email"
                   value="<?= htmlspecialchars($_POST['guest_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="jan@example.com" required>
        </div>

        <div class="form-group">
            <label for="guest_phone">Numer telefonu</label>
            <input type="tel" name="guest_phone" id="guest_phone"
                   value="<?= htmlspecialchars($_POST['guest_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="123 456 789" required>
        </div>

        <div class="form-group">
            <label for="num_guests">Liczba osób</label>
            <input type="number" name="num_guests" id="num_guests" min="1" max="3"
                   value="<?= htmlspecialchars($_POST['num_guests'] ?? '1', ENT_QUOTES, 'UTF-8') ?>"
                   required>
        </div>

        <button type="submit" class="btn-submit">Zarezerwuj</button>
    </form>
</section>

<script>
(function () {
    let selectedFrom = null;
    let selectedTo   = null;
    let step         = 'from';

    const selectionInfo = document.getElementById('selection-info');
    const selectionText = document.getElementById('selection-text');
    const btnReset      = document.getElementById('btn-reset');
    const roomsSection  = document.getElementById('rooms-section');
    const roomsList     = document.getElementById('rooms-list');
    const noRoomsMsg    = document.getElementById('no-rooms-msg');
    const form          = document.getElementById('reservation-form');
    const formSummary   = document.getElementById('form-summary');
    const inputRoomId   = document.getElementById('input-room-id');
    const inputDateFrom = document.getElementById('input-date-from');
    const inputDateTo   = document.getElementById('input-date-to');
    const inputGuests   = document.getElementById('num_guests');

    function formatDate(dateStr) {
        const [y, m, d] = dateStr.split('-');
        return `${d}.${m}.${y}`;
    }

    function updateHighlight() {
        document.querySelectorAll('.day').forEach(cell => {
            cell.classList.remove('sel-from', 'sel-to', 'in-range');
        });
        if (selectedFrom) {
            const c = document.querySelector(`.day[data-date="${selectedFrom}"]`);
            if (c) c.classList.add('sel-from');
        }
        if (selectedTo) {
            const c = document.querySelector(`.day[data-date="${selectedTo}"]`);
            if (c) c.classList.add('sel-to');
        }
        if (selectedFrom && selectedTo) {
            document.querySelectorAll('.day[data-date]').forEach(cell => {
                const d = cell.dataset.date;
                if (d > selectedFrom && d < selectedTo) cell.classList.add('in-range');
            });
        }
    }

    function showSelectionInfo() {
        if (selectedFrom && !selectedTo) {
            selectionText.textContent = `Przyjazd: ${formatDate(selectedFrom)} — wybierz datę wyjazdu`;
            selectionInfo.style.display = 'block';
        } else if (selectedFrom && selectedTo) {
            selectionText.textContent = `Przyjazd: ${formatDate(selectedFrom)}  |  Wyjazd: ${formatDate(selectedTo)}`;
            selectionInfo.style.display = 'block';
        } else {
            selectionInfo.style.display = 'none';
        }
    }

    function clearRooms() {
        roomsSection.style.display = 'none';
        roomsList.innerHTML = '';
        noRoomsMsg.style.display = 'none';
        form.style.display = 'none';
    }

    function fetchRooms(from, to) {
        roomsSection.style.display = 'block';
        roomsList.innerHTML = '<p class="loading">Ładowanie dostępnych pokoi…</p>';
        noRoomsMsg.style.display = 'none';
        form.style.display = 'none';

        fetch(`pages/rezerwacje-sprawdz.php?date_from=${from}&date_to=${to}`)
            .then(r => r.json())
            .then(data => {
                roomsList.innerHTML = '';
                if (!data.rooms || data.rooms.length === 0) {
                    noRoomsMsg.style.display = 'block';
                    return;
                }
                data.rooms.forEach(room => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'room-card';
                    btn.innerHTML = `
                        <span class="room-name">${room.name}</span>
                        <span class="room-capacity">${room.capacity}-osobowy</span>
                        <span class="room-price">${room.price_per_person} zł/os/noc</span>
                    `;
                    btn.addEventListener('click', () => selectRoom(room, from, to));
                    roomsList.appendChild(btn);
                });
            })
            .catch(() => {
                roomsList.innerHTML = '<p class="alert alert-error">Błąd połączenia. Odśwież stronę i spróbuj ponownie.</p>';
            });
    }

    let currentRoom = null;
    let currentFrom = null;
    let currentTo   = null;

    function countNights(from, to) {
        return Math.round((new Date(to) - new Date(from)) / 86400000);
    }

    function updatePriceSummary() {
        if (!currentRoom) return;
        const nights = countNights(currentFrom, currentTo);
        const guests = parseInt(inputGuests.value) || 1;
        const total  = currentRoom.price_per_person * guests * nights;
        formSummary.innerHTML = `
            <strong>${currentRoom.name}</strong> (${currentRoom.capacity}-osobowy)
            &nbsp;|&nbsp; ${formatDate(currentFrom)} → ${formatDate(currentTo)}
            &nbsp;|&nbsp; ${nights} ${nights === 1 ? 'noc' : 'noce/nocy'}
            &nbsp;|&nbsp; ${currentRoom.price_per_person} zł/os/noc
            <br><span class="price-total">Szacowana cena: <strong>${total} zł</strong></span>
        `;
    }

    function selectRoom(room, from, to) {
        document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');

        currentRoom = room;
        currentFrom = from;
        currentTo   = to;

        inputRoomId.value   = room.id;
        inputDateFrom.value = from;
        inputDateTo.value   = to;
        inputGuests.max     = room.capacity;
        if (parseInt(inputGuests.value) > room.capacity) inputGuests.value = room.capacity;

        updatePriceSummary();
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    inputGuests.addEventListener('input', updatePriceSummary);

    function reset() {
        selectedFrom = null;
        selectedTo   = null;
        step         = 'from';
        updateHighlight();
        showSelectionInfo();
        clearRooms();
    }

    document.querySelectorAll('.day[data-date]').forEach(cell => {
        cell.addEventListener('click', function () {
            const date = this.dataset.date;
            if (step === 'from') {
                selectedFrom = date;
                selectedTo   = null;
                step         = 'to';
                clearRooms();
            } else {
                if (date <= selectedFrom) {
                    selectedFrom = date;
                    selectedTo   = null;
                } else {
                    selectedTo = date;
                    step       = 'from';
                    fetchRooms(selectedFrom, selectedTo);
                }
            }
            updateHighlight();
            showSelectionInfo();
        });
    });

    btnReset.addEventListener('click', reset);
})();
</script>
