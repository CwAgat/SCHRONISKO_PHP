<?php
$pageTitle = 'Strona główna';
$pageStyle = '../css/landing.css';
$currentPage = 'home';
include 'includes/header.php';
?>


<section class="welcome">
    <h1>Schronisko Kamieńczyk</h1>
    <p>Tam gdzie cisza i spokój spotykają się z naturą</p>
</section>

<section class="about">
    <div class="content">
        <h2>Krótko o schronisku</h2>
        <p>
            Schronisko Kamieńczyk to jedno z najbardziej malowniczych miejsc w polskich Karkonoszach, położone w sercu dzikiej przyrody, tuż przy słynnym Wodospadzie Kamieńczyk – najwyższym w Sudetach (27 m). Historia tego miejsca sięga XIX wieku, kiedy przy wodospadzie powstało pierwsze niewielkie schronisko dla turystów. Obecny obiekt został wybudowany w 1997 roku przez Janinę i Jerzego Sieleckich jako pierwsze prywatne schronisko górskie po II wojnie światowej, kontynuując wieloletnią tradycję gościnności w sercu gór.
            Schronisko znajduje się przy głównym szlaku na Szrenicę, zaledwie 2 km od Szklarskiej Poręby, co czyni je idealną bazą wypadową na górskie wędrówki piesze i rowerowe oraz na trasy narciarstwa zjazdowego i biegowego. Oferujemy 19 miejsc noclegowych w komfortowych pokojach dwu- i trzyosobowych, bufet oraz przytulną salę z kominkiem. Przy schronisku znajduje się obszerny szałas z ogniskiem i barem, gdzie można usmażyć kiełbaskę, wypić piwko i zrelaksować się po trudach wędrówki. Organizujemy również imprezy plenerowe niezależnie od pogody.
            To tutaj cisza karkonoskich lasów spotyka się z szumem wodospadu, tworząc miejsce pełne spokoju i niezapomnianej atmosfery, do którego wraca się nie raz.
        </p>
    </div>
</section>

<main>
    <section class="news">
        <h2>Aktualności</h2>

        <div class="cards">
            <article class="card">
                <div class="photo" style="background-image:url('img/zimowykamien.jpg');"></div>
                <div class="text">
                    <h3>Zimowa promocja - 20% taniej!</h3>
                    <p class="short-text">W lutym oferujemy specjalne ceny na noclegi. Idealna okazja na
                        zimowy wypoczynek...</p>
                    <p class="full-text">W lutym oferujemy specjalne ceny na noclegi. Idealna okazja na
                        zimowy wypoczynek w górach. Zarezerwuj pobyt minimum na 3 noce, a otrzymasz 20%
                        rabatu. W cenie: śniadanie, dostęp do sauny i wypożyczalnia sprzętu narciarskiego.
                        Promocja trwa do końca lutego! Nie przegap tej okazji - miejsca ograniczone.</p>
                    <a class="card-link" href="#">Rozwiń →</a>
                </div>
            </article>
            <article class="card">
                <div class="photo" style="background-image:url('img/gwiazdy.jpg');"></div>
                <div class="text">
                    <h3>Weekend z gwiazdami</h3>
                    <p class="short-text">15 lutego zapraszamy na nocną obserwację gwiazd z teleskopem.
                        Liczba miejsc ograniczona...</p>
                    <p class="full-text">15 lutego zapraszamy na nocną obserwację gwiazd z profesjonalnym
                        teleskopem. W programie: obserwacja planet, gwiazd podwójnych, mgławic i galaktyk.
                        Prowadzenie: licencjonowany przewodnik astronomiczny. Początek o godzinie 20:00. W
                        cenie: ciepła herbata i poczęstunek. Liczba miejsc ograniczona do 15 osób - rezerwuj
                        już dziś! Warunkiem uczestnictwa jest bezchmurna pogoda.</p>
                    <a class="card-link" href="#">Rozwiń →</a>
                </div>
            </article>
            <article class="card">
                <div class="photo" style="background-image:url('img/zimowykamienczyk.jpg');"></div>
                <div class="text">
                    <h3>Zimowy Wodospad Kamieńczyk</h3>
                    <p class="short-text">Odkryj Wodospad Kamieńczyk w zimowej odsłonie. Lodowe sople i
                        zamarznięta kaskada...</p>
                    <p class="full-text">Odkryj Wodospad Kamieńczyk w zimowej odsłonie! Najwyższy wodospad
                        polskich Karkonoszy (27 metrów) zimą zamienia się w bajkową krainę lodu. Gigantyczne
                        sople, zamarznięte kaskady i lodowa grota tworzą niezapomniany widok. Trasa prowadzi
                        przez ośnieżony las bukowy - jak z zimowej baśni. Odległość ze schroniska: 2 km,
                        czas spaceru: 45 minut. UWAGA: zimą przy wodospadzie może być bardzo ślisko -
                        koniecznie zabierz raki lub kijki trekkingowe. Najpiękniej po mocnych mrozach w
                        styczniu i lutym!</p>
                    <a class="card-link" href="#">Rozwiń →</a>
                </div>
            </article>
            <article class="card">
                <div class="photo" style="background-image:url('img/dania.webp');"></div>
                <div class="text">
                    <h3>Nowe dania w karcie</h3>
                    <p class="short-text">Rozszerzyliśmy menu o tradycyjne potrawy regionalne. Spróbuj
                        naszych domowych specjałów...</p>
                    <p class="full-text">Rozszerzyliśmy menu o tradycyjne potrawy regionalne. Spróbuj
                        naszych domowych pierogów, żurku i placków ziemniaczanych przygotowanych według
                        starych, rodzinnych receptur. Wszystkie dania gotujemy ze świeżych produktów od
                        lokalnych gospodarzy. W ofercie również wegańskie i wegetariańskie opcje.
                        Szczególnie polecamy nasze domowe ciasta - wypiekane codziennie rano!</p>
                    <a class="card-link" href="#">Rozwiń →</a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
