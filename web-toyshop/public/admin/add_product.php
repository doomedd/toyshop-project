<?php
session_start();
require_once "../../core/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = "";
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $manufacturer = trim($_POST['manufacturer']);
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);
    $category_id = (int)$_POST['category_id'];

    if ($name !== "" && $price > 0 && $category_id > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO products (name, manufacturer, price, description, image_url, category_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $manufacturer, $price, $description, $image_url, $category_id]);
        $message = "Товар успішно додано!";
    } else {
        $message = "Будь ласка, заповніть усі обов’язкові поля.";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати товар — ToyShop Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../components/header.php"; ?>

<div class="container">
    <h2>Додати товар</h2>

    <?php if ($message): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Назва товару *</label>
        <input type="text" name="name" required>

        <label>Виробник</label>
        <input type="text" name="manufacturer">

        <label>Ціна *</label>
        <input type="number" step="0.01" name="price" required>

        <label>Опис</label>
        <textarea name="description"></textarea>

        <label>URL зображення</label>
        <input type="text" name="image_url">

        <label>Категорія *</label>
        <select name="category_id" required>
            <option value="">Оберіть категорію</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Додати товар</button>
    </form>

    <br>
    <a href="dashboard.php">← Повернутися в адмінку</a>
</div>

</body>
</html>