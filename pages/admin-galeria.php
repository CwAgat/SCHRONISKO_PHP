<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}
?>
<?php

/** @var mysqli $conn */
$sql = "
SELECT
    id,
    name,
    alt,
    category
FROM photos
ORDER BY category
";
$result = $conn->query($sql);

$gallery = [];
while ($row = $result->fetch_assoc()) {
    $gallery[$row['category']][] = $row;
}
?>

<section class="upload_photo">

    <?php if (isset($_SESSION['msg'])): ?>

        <p class="msg">

            <?php
            if ($_SESSION['msg'] === 'error_move') {
                echo "Błąd podczas przenoszenia pliku.";
            } elseif ($_SESSION['msg'] === 'error_upload') {
                echo "Błąd podczas przesyłania pliku na server.";
            } elseif ($_SESSION['msg'] === 'wrong_type') {
                echo "Nieprawidłowy rodzaj pliku.";
            }
            ?>

        </p>

    <?php endif; ?>
    <?php unset($_SESSION['msg']); ?>
    <h2>Dodaj nowe zdjęcie</h2>

    <form method="post"
        action="index.php?page=admin-galeria-dodaj"
        enctype="multipart/form-data">

        <input type="file" name="photo" accept="image/*" required class="przegladaj">

        <input type="text" name="alt" placeholder="Tekst alternatywny" class="opis">
        <label id="kategoria">Kategoria</label>
        <select name="category">
            <option value="schronisko">Schronisko</option>
            <option value="okolica">Okolica</option>
            <option value="szalas">Szalas</option>
        </select>

        <button type="submit" class="wgraj">Wgraj zdjęcie</button>
    </form>

</section>

<section class="galeria-box">
    <h1>Podgląd galerii</h1>
    <?php foreach ($gallery as $category => $photos): ?>
        <section>
            <div class=" galeria">
                <h2><?= htmlspecialchars(ucfirst($category)) ?></h2>
                <div class="zdjecia">
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-box">
                            <img
                                src="img/galeria/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($photo['name']) ?>"
                                alt="<?= htmlspecialchars($photo['alt']) ?>">

                            <form method="post" action="index.php?page=admin-galeria-usun"
                                onsubmit="return confirm('Na pewno usunąć zdjęcie?');">

                                <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">

                                <button class="usun" type="submit">Usuń</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
</section>


<div id="lightbox">
    <img id="lightbox-img" alt="Podgląd zdjęcia">
</div>