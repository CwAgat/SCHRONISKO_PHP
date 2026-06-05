<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['photo_id'])) {
    $photo_id = (int) $_POST['photo_id'];
    $stmt = $conn->prepare("SELECT name, category FROM photos WHERE id = ?");
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        $file_name = $row['name'];
        $category = $row['category'];

        $stmt = $conn->prepare("DELETE FROM photos WHERE id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();

        // usunięcie pliku z dysku
        $path = "img/galeria/" . $category . "/" . $file_name;

        if (file_exists($path)) {
            unlink($path);
        }
    }
}
header("Location: admin-galeria");
exit();
