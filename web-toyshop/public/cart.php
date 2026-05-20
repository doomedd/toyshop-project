<?php
session_start();
require_once "../core/db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit;
}

if (isset($_GET['plus'])) {
    $id = (int)$_GET['plus'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    }
    header("Location: cart.php");
    exit;
}

if (isset($_GET['minus'])) {
    $id = (int)$_GET['minus'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

$products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $rows = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();

    foreach ($rows as $p) {
        $p['quantity'] = $_SESSION['cart'][$p['id']];
        $p['sum'] = $p['quantity'] * $p['price'];
        $total += $p['sum'];
        $products[] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Кошик — ToyShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "components/header.php"; ?>

<div class="container">
    <h2>Ваш кошик</h2>

    <?php if (empty($products)): ?>
        <p>Кошик порожній.</p>
        <a href="catalog.php">← Повернутися до каталогу</a>
    <?php else: ?>
        <table>
            <tr>
                <th>Назва</th>
                <th>Ціна</th>
                <th>Кількість</th>
                <th>Сума</th>
                <th>Дії</th>
            </tr>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= $p['price'] ?> грн</td>
                    <td>
                        <a class="btn-small" href="cart.php?minus=<?= $p['id'] ?>">−</a>
                        <?= $p['quantity'] ?>
                        <a class="btn-small" href="cart.php?plus=<?= $p['id'] ?>">+</a>
                    </td>
                    <td><?= $p['sum'] ?> грн</td>
                    <td>
                        <a class="btn-small danger" href="cart.php?delete=<?= $p['id'] ?>">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Загальна сума: <?= $total ?> грн</h3>
        <p><i>Оформлення замовлення можна додати окремо.</i></p>

        <a href="catalog.php">← Продовжити покупки</a>
    <?php endif; ?>
</div>

</body>
</html>