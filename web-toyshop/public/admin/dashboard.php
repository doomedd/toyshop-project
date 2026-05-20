<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Адмін‑панель — ToyShop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../components/header.php"; ?>

<div class="container">
    <h2>Адмін‑панель</h2>

    <ul class="admin-menu">
        <li><a href="add_product.php">Додати товар</a></li>
        <li><a href="categories.php">Категорії</a></li>
        <li><a href="edit_product.php">Редагувати товари</a></li>
    </ul>
</div>

</body>
</html>