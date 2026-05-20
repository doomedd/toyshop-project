<?php
session_start();
require_once "../core/db.php";

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($selected_category > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->execute([$selected_category]);
    $products = $stmt->fetchAll();
} else {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Каталог — ToyShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "components/header.php"; ?>

<div class="container">
    <h2>Каталог товарів</h2>

    <form method="get" class="filter-form">
        <label>Категорія:</label>
        <select name="category" onchange="this.form.submit()">
            <option value="0">Усі категорії</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $selected_category == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="product-list">
        <?php if (empty($products)): ?>
            <p>Товарів не знайдено.</p>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <?php if (!empty($p['image_url'])): ?>
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <?php endif; ?>
                    <p><b>Виробник:</b> <?= htmlspecialchars($p['manufacturer']) ?></p>
                    <p><b>Ціна:</b> <?= $p['price'] ?> грн</p>
                    <a href="product.php?id=<?= $p['id'] ?>">Переглянути</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>