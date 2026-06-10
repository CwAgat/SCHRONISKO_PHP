// Cały kod jest owinięty w IIFE : (function(){ ... })()
// Dzięki temu zmienne nie "wyciekają" do globalnego scope i nie kolidują z innymi skryptami.
(function () {

    // zapamiętujemy co wybrał użytkownik
    let selectedFrom = null;  
    let selectedTo   = null;  
    let step         = 'from'; 
                               

    // elementy DOM
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



    // tu też zmiana formatu na czytelniejszy
    function formatDate(dateStr) {
        const [y, m, d] = dateStr.split('-');
        return `${d}.${m}.${y}`;
    }

    // zaznaczenie daty wybranej
    function updateHighlight() {
        //czyszczenie klas z wszystkich dni
        document.querySelectorAll('.day').forEach(cell => {
            cell.classList.remove('sel-from', 'sel-to', 'in-range');
        });

        // zaznaczenie dnia przyjazdu
        if (selectedFrom) {
            const c = document.querySelector(`.day[data-date="${selectedFrom}"]`);
            if (c) c.classList.add('sel-from');
        }

        // zaznaczenie dnia wyjzadu
        if (selectedTo) {
            const c = document.querySelector(`.day[data-date="${selectedTo}"]`);
            if (c) c.classList.add('sel-to');
        }

        // zaznaczamy dni między przyjazdem a wyjazdem 
        if (selectedFrom && selectedTo) {
            document.querySelectorAll('.day[data-date]').forEach(cell => {
                const d = cell.dataset.date;
                // d > selectedFrom && d < selectedTo — dni wewnątrz zakresu (bez krańców)
                if (d > selectedFrom && d < selectedTo) cell.classList.add('in-range');
            });
        }
    }

    // tekst nad formularzem
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

    // zanim użytkownik wybierze datę to nie widać listy pokoi ani formularrza
    function clearRooms() {
        roomsSection.style.display = 'none';
        roomsList.innerHTML = '';
        noRoomsMsg.style.display = 'none';
        form.style.display = 'none';
    }

    // pobieranie wolnych pokoi po wyborze dni przez użtkownika

    // wywołuje akcje z pliku rezerwacje-sprawdz który zwraca json z lista dostepnych pokoi
    function fetchRooms(from, to) {
        roomsSection.style.display = 'block';
        roomsList.innerHTML = '<p class="loading">Ładowanie dostępnych pokoi…</p>';
        noRoomsMsg.style.display = 'none';
        form.style.display = 'none';

        // fetch to wbudowane API przeglądarki do AJAX,  wysyła zapytanie do serwera bez przeładowania strony
        fetch(`pages/rezerwacje-sprawdz.php?date_from=${from}&date_to=${to}`)
            .then(r => r.json())   
            .then(data => {
                roomsList.innerHTML = '';

                if (!data.rooms || data.rooms.length === 0) {
                    noRoomsMsg.style.display = 'block'; 
                    return;
                }

                // karta/button dla każdego pokou
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

    //wyybór pokoju i uzupełnienie formularza

    let currentRoom = null;
    let currentFrom = null;
    let currentTo   = null;

    // oblicza liczbę nocy między dwiema datami
    // 86400000 ms = 1 dzień; Math.round na wypadek zmiany czasu (DST)
    function countNights(from, to) {
        return Math.round((new Date(to) - new Date(from)) / 86400000);
    }
    // aktualizuje blok podsumowania (nazwa pokoju, daty, cena) wewnątrz formularza
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
        // zdejmujemy zaznaczenie ze wszystkich kart, zaznaczamy klikniętą
        document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');

        currentRoom = room;
        currentFrom = from;
        currentTo   = to;

        // uzupełniamy ukryte pola, które trafią do PHP razem z danymi gościa
        inputRoomId.value   = room.id;
        inputDateFrom.value = from;
        inputDateTo.value   = to;

        // ograniczamy max gości do pojemności pokoju
        inputGuests.max = room.capacity;
        if (parseInt(inputGuests.value) > room.capacity) inputGuests.value = room.capacity;

        updatePriceSummary();
        form.style.display = 'block';
       
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

   
    inputGuests.addEventListener('input', updatePriceSummary);

    // ewentualna zmiana daty
    function reset() {
        selectedFrom = null;
        selectedTo   = null;
        step         = 'from';
        updateHighlight();
        showSelectionInfo();
        clearRooms();
    }


    // nasłuchujemy klikania tyylko na komórkach z atrybutem data-date ustawionym w pliku rezerwacje-funkcje.php
    document.querySelectorAll('.day[data-date]').forEach(cell => {
        cell.addEventListener('click', function () {
            const date = this.dataset.date;  

            if (step === 'from') {
                // pierwsze kliknięcie = data przyjazdu
                selectedFrom = date;
                selectedTo   = null;
                step         = 'to';       // następne kliknięcie to data wyjazdu
                clearRooms();              
            } else {
                // drugie kliknięcie = data wyjazdu
                if (date <= selectedFrom) {
                    // kliknięto datę wcześniejszą lub tę samą co przyjazd
                    // wtedy dziłą jak nowe klikniecie
                    selectedFrom = date;
                    selectedTo   = null;
                } else {
                    // jak wszystko sie zgadza to uruchamiamy wyszukiwanie pokoi 
                    selectedTo = date;
                    step       = 'from';   // resetujemy krok na wypadek kolejnego wyboru
                    fetchRooms(selectedFrom, selectedTo);
                }
            }

            updateHighlight();
            showSelectionInfo();
        });
    });

    btnReset.addEventListener('click', reset);
})();
