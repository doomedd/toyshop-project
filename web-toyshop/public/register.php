<?php
session_start();
require_once "../core/db.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $message = "Такий логін вже існує!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed]);
        $message = "Реєстрація успішна! Тепер можете увійти.";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація — ToyShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "components/header.php"; ?>

<div class="container auth">
    <h2>Реєстрація</h2>

    <?php if (!empty($message)): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Логін</label>
        <input type="text" name="username" required>

        <label>Пароль</label>
        <input type="password" name="password" required>

        <button type="submit">Зареєструватися</button>
    </form>
</div>

</body>
</html>