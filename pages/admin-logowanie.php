<?php

/** @var mysqli $conn */
$komunikat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $haslo = trim($_POST['haslo']);


    if (empty($email) || empty($haslo)) {
        $komunikat = "<p style='color:red;'>Wszystkie pola są wymagane!</p>";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, email, pass FROM admin_users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($haslo, $user['pass'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                header("Location: admin");
                exit();
            } else {
                $komunikat = "Błędne hasło.";
            }
        } else {
            $komunikat = "Użytkownik o takim adresie e-mail nie istnieje";
        }
    }
}

?>
<?php if (!empty($komunikat)): ?>
    <p class="komunikat">
        <?= htmlspecialchars($komunikat) ?>
    </p>
<?php $komunikat = "";
endif; ?>

<section class="logowanie-form-section">
    <h2>Zaloguj się</h2>
    <form class="logowanie-form" method="post" action="">
        <div class="form-row">
            <input type="email" name="email" placeholder="Adres e-mail" required>
            <input type="password" name="haslo" placeholder="Hasło" required>
        </div>
        <button type="submit" class="submit-btn">Wyślij</button>
    </form>
</section>