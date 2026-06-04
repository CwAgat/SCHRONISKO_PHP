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


<section>
    <h1>Galeria</h1>
    <?php foreach ($gallery as $category => $photos): ?>
        <section>
            <div class="galeria">
                <h2><?= htmlspecialchars(ucfirst($category)) ?></h2>
                <div class="zdjecia">
                    <?php foreach ($photos as $photo): ?>
                        <img
                            src="img/galeria/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($photo['name']) ?>"
                            alt="<?= htmlspecialchars($photo['alt']) ?>">

                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>


    <div id="lightbox">
        <img id="lightbox-img" alt="Podgląd zdjęcia">
    </div>