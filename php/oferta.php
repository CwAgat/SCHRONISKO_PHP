<?php
$pageTitle = 'Oferta';
$pageStyle = '../css/oferta.css';
$currentPage = 'oferta';
include 'includes/header.php';
?>


<section class="oferta">
    <div class="oferta-inner">
        <div class="oferta-text">
            <h1>Noclegi</h1>
            <p>Dysponujemy 19 miejscami noclegowymi w pokojach 2- i 3-osobowych.</p>
            <h3>Cennik</h3>
            <ul>
                <li>pokój trzyosobowy (łazienka na dwa pokoje) - <b>50 zł/os</b></li>
                <li>pokój dwuosobowy (łazienka i podwójne łóżko) - <b>65 zł/os</b></li>
                <li>przy większej grupie możliwe są 4 dostawki - <b>45 zł/os</b></li>
            </ul>
        </div>
        <div class="oferta-photo">
            <img class="photo" src="img/nocleg.png" alt="nocleg">
        </div>
    </div>
</section>
<section class="oferta oferta-reverse">
    <div class="oferta-inner">
        <div class="oferta-text">
            <h1>Informacje praktyczne</h1>
            <p>Do dyspozycji Gości bufet oraz sala z kominkiem.
                Przy schronisku szałas z ogniskiem i barem, po ciężkich wędrówkach można się w nim zrelaksować przy
                dobrym piwie i kiełbasce z ogniska.
                Możliwość zorganizowania imprezy plenerowej (niezależnie od pogody).
                W schronisku znajduje się kuchnia turystyczna z czajnikiem.
            </p>
        </div>
        <div class="oferta-photo">
            <img class="photo" src="img/kamien.png" alt="nocleg">
        </div>
    </div>
</section>
<section class="oferta">
    <div class="oferta-inner">
        <div class="oferta-text">
            <h1>Kuchnia</h1>
            <p>Zapraszamy do skorzystania z pysznej kuchni. Jadalnia znajduje się w samym schronisku, ale również
                można zjeść w "Szałasie Sielanka", a także usiąść na zewnątrz, podziwiając piękne widoki.
                Nasze menu zawiera zarówno przekąski na szybko, jak i pyszne domowe obiady. Menu zamieszczone na
                stronie nie zawiera wszystkich serwowanych przez nas dań.
            </p>
        </div>
        <div class="oferta-photo">
            <img class="photo" src="img/stolowka.png" alt="nocleg">
        </div>
    </div>
</section>

<section class="menu-pdf">
    <h1>Nasze menu</h1>
    <iframe src="img/menu.pdf" title="Menu schroniska"></iframe>
</section>

<?php include 'footer.php'; ?>
