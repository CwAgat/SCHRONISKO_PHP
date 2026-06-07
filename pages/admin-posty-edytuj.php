<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */
if (!isset($_GET['id'])) {
    header("Location: admin");
    exit();
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();


$post = $result->fetch_assoc();

$komunikat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['tytul']);
    $short_text = trim($_POST['short_text']);
    $full_text = trim($_POST['full_text']);

    if (
        empty($title) ||
        empty($short_text) ||
        empty($full_text)
    ) {
        $komunikat = "Wszystkie pola są wymagane.";
    } else {

        // czy wybrano nowe zdjęcie?
        if (!empty($_FILES['photo']['name'])) {

            $image = $_FILES['photo'];

            if ($image['error'] === 0) {

                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                $extension = strtolower(
                    pathinfo($image['name'], PATHINFO_EXTENSION)
                );

                if (!in_array($extension, $allowed)) {

                    $_SESSION['msg'] = "wrong_type";
                    header("Location: admin-posty-edytuj?id=" . $id);
                    exit();
                }

                $uniqueName = uniqid() . "_" . basename($image['name']);
                $destination = "img/posts/" . $uniqueName;

                if (move_uploaded_file($image['tmp_name'], $destination)) {

                    // usunięcie starego zdjęcia
                    if (
                        !empty($post['image']) &&
                        file_exists("img/posts/" . $post['image'])
                    ) {
                        unlink("img/posts/" . $post['image']);
                    }

                    $stmt = $conn->prepare("
                        UPDATE posts
                        SET
                            title = ?,
                            short_text = ?,
                            full_text = ?,
                            image = ?
                        WHERE id = ?
                    ");

                    $stmt->bind_param(
                        "ssssi",
                        $title,
                        $short_text,
                        $full_text,
                        $uniqueName,
                        $id
                    );

                    $stmt->execute();

                    $_SESSION['msg'] = "post_updated";

                    header("Location: admin-posty");
                    exit();
                } else {

                    $_SESSION['msg'] = "error_move";
                    header("Location: admin-posty-edytuj?id=" . $id);
                    exit();
                }
            } else {

                $_SESSION['msg'] = "error_upload";
                header("Location: admin-posty-edytuj?id=" . $id);
                exit();
            }
        } else {

            // aktualizacja bez zmiany zdjęcia

            $stmt = $conn->prepare("
                UPDATE posts
                SET
                    title = ?,
                    short_text = ?,
                    full_text = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "sssi",
                $title,
                $short_text,
                $full_text,
                $id
            );

            $stmt->execute();

            $_SESSION['msg'] = "post_updated";

            header("Location: admin-posty");
            exit();
        }
    }
}
?>

<?php
echo $komunikat;
?>

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
<section class="dodaj_post">


    <h2>Edytuj post</h2>

    <form method="post"
        action=""
        enctype="multipart/form-data">

        <label>Tytuł</label>
        <input type="text" name="tytul" placeholder="Podaj tytuł" required class="tekst" value="<?= htmlspecialchars($post['title']) ?>">
        <label>Krótki opis</label>
        <textarea
            name="short_text"
            rows="3"
            maxlength="115"
            class="tekst"
            placeholder="Max 115 znaków" required><?= htmlspecialchars($post['short_text']) ?></textarea>
        <label>Pełny opis</label>
        <textarea
            name="full_text"
            rows="8"
            class="full_text"
            placeholder="Wpisz pełną treść posta" required><?= htmlspecialchars($post['full_text']) ?></textarea>
        <label> Zdjęcie </label>
        <div class="current-image">
            <div>
                <p>Aktualne zdjęcie:</p>
                <img
                    src="img/posts/<?= htmlspecialchars($post['image']) ?>"
                    width="250"
                    alt="">
            </div>
            <input type="file" name="photo" accept="image/*" class="przegladaj">
        </div>

        <button type="submit" class="dodaj">Zapisz post</button>
        <a href="admin-posty" class="anuluj">Anuluj</a>
    </form>

</section>