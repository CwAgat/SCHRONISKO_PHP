<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */
$komunikat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['photo'])) {
    $title = trim($_POST['tytul']);
    $short_text = trim($_POST['short_text']);
    $full_text = trim($_POST['full_text']);
    $image = $_FILES['photo'];





    if (
        empty($title) ||
        empty($short_text) ||
        empty($full_text)
    ) {
        $komunikat = "Wszystkie pola są wymagane.";
    } else {

        if ($image['error'] === 0) {

            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            $extension = strtolower(
                pathinfo($image['name'], PATHINFO_EXTENSION)
            );

            if (!in_array($extension, $allowed)) {

                $_SESSION['msg'] = "wrong_type";
                header("Location: admin-posty-dodaj");
                exit();
            }

            $uniqueName = uniqid() . "_" . basename($image['name']);

            $destination = "img/posts/" . $uniqueName;

            if (move_uploaded_file($image['tmp_name'], $destination)) {

                $stmt = $conn->prepare("
                    INSERT INTO posts
                    (
                        title,
                        short_text,
                        full_text,
                        image
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param(
                    "ssss",
                    $title,
                    $short_text,
                    $full_text,
                    $uniqueName
                );

                $stmt->execute();

                $_SESSION['msg'] = "post_added";

                header("Location: admin-posty");
                exit();
            } else {

                $_SESSION['msg'] = "error_move";

                header("Location: admin-posty-dodaj");
                exit();
            }
        } else {

            $_SESSION['msg'] = "error_upload";

            header("Location: admin-posty-dodaj");
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


    <h2>Dodaj nowy post</h2>

    <form method="post"
        action=""
        enctype="multipart/form-data">

        <label>Tytuł</label>
        <input type="text" name="tytul" placeholder="Podaj tytuł" required class="tekst">
        <label>Krótki opis</label>
        <textarea
            name="short_text"
            rows="3"
            maxlength="115"
            class="tekst"
            placeholder="Max 115 znaków" required></textarea>
        <label>Pełny opis</label>
        <textarea
            name="full_text"
            rows="8"
            class="full_text"
            placeholder="Wpisz pełną treść posta" required></textarea>
        <label> Zdjęcie </label>
        <input type="file" name="photo" accept="image/*" required class="przegladaj">

        <button type="submit" class="dodaj">Dodaj post</button>
    </form>

</section>