<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['photo'])) {

    $file = $_FILES['photo'];
    $alt = trim($_POST['alt']);
    $category = trim($_POST['category']);

    if ($file['error'] === 0) {

        $uniqueName = uniqid() . "_" . basename($file['name']);
        $destination = "img/galeria/" . $category . "/" . $uniqueName;

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed)) {
            $_SESSION['msg'] = "wrong_type";
            header("Location: admin-galeria");
            exit();
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {

            $stmt = $conn->prepare("
                INSERT INTO photos (name, alt, category)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sss", $uniqueName, $alt, $category);
            $stmt->execute();

            header("Location: admin-galeria");
            exit();
        } else {
            $_SESSION['msg'] = "error_move";
            header("Location: admin-galeria");
            exit();
        }
    } else {
        $_SESSION['msg'] = "error_upload";
        header("Location: admin-galeria");
        exit();
    }
}
