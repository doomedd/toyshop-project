<?php
session_start();
require_once "../core/db.php";

if (!isset($_GET['id'])) {
    die("Товар не знайдено.");
}
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id
                       WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Товар не знайдено.");
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> — ToyShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "components/header.php"; ?>

<div class="container">
    <div class="product-page">
        <div class="product-card big">
            <h2><?= htmlspecialchars($product['name']) ?></h2>

            <?php if (!empty($product['image_url'])): ?>
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php endif; ?>

            <p><b>Категорія:</b> <?= htmlspecialchars($product['category_name']) ?></p>
            <p><b>Виробник:</b> <?= htmlspecialchars($product['manufacturer']) ?></p>
            <p><b>Ціна:</b> <?= $product['price'] ?> грн</p>

            <h3>Опис</h3>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <form method="post" action="cart.php">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <button type="submit">Додати в кошик</button>
            </form>

            <br>
            <a href="catalog.php">← Повернутися до каталогу</a>
        </div>
    </div>
</div>

</body>
</html>