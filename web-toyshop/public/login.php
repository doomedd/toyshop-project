<?php
session_start();
require_once "../core/db.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
            exit;
        }
        header("Location: index.php");
        exit;
    } else {
        $message = "Невірний логін або пароль!";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід — ToyShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "components/header.php"; ?>

<div class="container auth">
    <h2>Вхід</h2>

    <?php if (!empty($message)): ?>
        <p class="msg error"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Логін</label>
        <input type="text" name="username" required>

        <label>Пароль</label>
        <input type="password" name="password" required>

        <button type="submit">Увійти</button>
    </form>
</div>

</body>
</html>