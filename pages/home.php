<?php

/** @var mysqli $conn */
$sql = "

SELECT
    id,
    title,
    short_text,
    full_text,
    image,
    created_at
FROM posts
ORDER BY created_at DESC
";
$result = $conn->query($sql);

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
            <?php while ($post = $result->fetch_assoc()): ?>

                <article class="card">
                    <div class="photo"
                        style="background-image:url('img/posts/<?= htmlspecialchars($post['image']) ?>');">
                    </div>

                    <div class="text">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>

                        <p class="short-text">
                            <?= htmlspecialchars($post['short_text']) ?>
                        </p>

                        <p class="full-text">
                            <?= nl2br(htmlspecialchars($post['full_text'])) ?>
                        </p>

                        <a class="card-link" href="#">Rozwiń →</a>
                    </div>
                </article>

            <?php endwhile; ?>
            </article>
        </div>
    </section>
</main>