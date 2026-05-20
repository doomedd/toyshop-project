<?php
session_start();
require_once "../../core/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: edit_product.php");
    exit;
}

$products = $pdo->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати товари — ToyShop Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../components/header.php"; ?>

<div class="container">
    <h2>Редагування товарів</h2>

    <?php if (empty($products)): ?>
        <p>Товарів немає.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Категорія</th>
                <th>Ціна</th>
                <th>Дії</th>
            </tr>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                    <td><?= $p['price'] ?> грн</td>
                    <td>
                        <a class="btn-small" href="edit_product_form.php?id=<?= $p['id'] ?>">Редагувати</a>
                        <a class="btn-small danger" href="edit_product.php?delete=<?= $p['id'] ?>">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">← Повернутися в адмінку</a>
</div>

</body>
</html>