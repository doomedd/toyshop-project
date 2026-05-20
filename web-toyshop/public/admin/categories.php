<?php
session_start();
require_once "../../core/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);
    if ($name !== "") {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        $message = "Категорію додано!";
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Категорії — ToyShop Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../components/header.php"; ?>

<div class="container">
    <h2>Керування категоріями</h2>

    <?php if ($message): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <h3>Додати категорію</h3>
    <form method="post">
        <input type="text" name="name" placeholder="Назва категорії" required>
        <button type="submit">Додати</button>
    </form>

    <hr>

    <h3>Список категорій</h3>

    <?php if (empty($categories)): ?>
        <p>Категорій немає.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Дії</th>
            </tr>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td>
                        <a class="btn-small danger" href="categories.php?delete=<?= $cat['id'] ?>">Видалити</a>
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