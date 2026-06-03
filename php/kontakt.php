<?php
$pageTitle = 'Kontakt - Schronisko Kamieńczyk';
$pageStyle = '../css/kontakt.css';
$currentPage = 'kontakt';
include 'includes/header.php';
?>


<main class="kontakt-page">
    <section class="kontakt-info">
        <div class="info-container">
            <div class="info-box">
                <h2>Informacje kontaktowe</h2>
                <p><strong>Adres:</strong></p>
                <p>Schronisko Kamieńczyk</p>
                <p>ul. Kolejowa 1</p>
                <p>Szklarska Poręba 58-530</p>
                <p style="margin-top: 15px;"><strong>Telefon:</strong></p>
                <p>75 75 260 85</p>
                <p style="margin-top: 15px;"><strong>Email:</strong></p>
                <p>kontakt@kamienczyk.pl</p>
            </div>
            <div class="map-box">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11989.92782690305!2d15.500720469663712!3d50.82057119305635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470edb26e28a5663%3A0xd7ba675a290e4f25!2sSchronisko%20Kamie%C5%84czyk!5e0!3m2!1spl!2spl!4v1768947600482!5m2!1spl!2spl"
                    width="100%" height="100%" style="border:0; border-radius: 12px;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <section class="kontakt-form-section">
        <h2>Formularz kontaktowy</h2>
        <form class="kontakt-form" method="post" action="">
            <div class="form-row">
                <input type="text" name="name" placeholder="Imię i nazwisko" required>
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <input type="text" name="subject" placeholder="Temat" required>
            <textarea name="message" placeholder="Wiadomość" rows="6" required></textarea>
            <button type="submit" class="submit-btn">Wyślij</button>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
