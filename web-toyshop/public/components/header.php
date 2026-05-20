<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="header">
    <div class="container header-inner">
        <a href="index.php" class="logo">ToyShop</a>

        <nav>
            <a href="catalog.php">Каталог</a>
            <a href="cart.php">Кошик</a>

            <?php if (isset($_SESSION['username'])): ?>
                <span class="user">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="logout.php">Вийти</a>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin/dashboard.php">Адмінка</a>
                <?php endif; ?>

            <?php else: ?>
                <a href="login.php">Вхід</a>
                <a href="register.php">Реєстрація</a>
            <?php endif; ?>
        </nav>
    </div>
</div>