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
