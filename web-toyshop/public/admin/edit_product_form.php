<?php
session_start();
require_once "../../core/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Товар не знайдено.");
}
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Товар не знайдено.");
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $manufacturer = trim($_POST['manufacturer']);
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);
    $category_id = (int)$_POST['category_id'];

    $stmt = $pdo->prepare("
        UPDATE products 
        SET name=?, manufacturer=?, price=?, description=?, image_url=?, category_id=?
        WHERE id=?
    ");
    $stmt->execute([$name, $manufacturer, $price, $description, $image_url, $category_id, $id]);

    $message = "Товар оновлено!";
    $product['name'] = $name;
    $product['manufacturer'] = $manufacturer;
    $product['price'] = $price;
    $product['description'] = $description;
    $product['image_url'] = $image_url;
    $product['category_id'] = $category_id;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати товар — ToyShop Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../components/header.php"; ?>

<div class="container">
    <h2>Редагувати товар</h2>

    <?php if ($message): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Назва товару *</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Виробник</label>
        <input type="text" name="manufacturer" value="<?= htmlspecialchars($product['manufacturer']) ?>">

        <label>Ціна *</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

        <label>Опис</label>
        <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>

        <label>URL зображення</label>
        <input type="text" name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>">

        <label>Категорія *</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Оновити товар</button>
    </form>

    <br>
    <a href="edit_product.php">← Повернутися до списку</a>
</div>

</body>
</html>