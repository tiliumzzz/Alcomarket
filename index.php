<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$user = isAuthenticated() ? $auth->getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Алкомаркет - Главная</title>
</head>
<body>
    <ul>
        <li><a href="index.php">Главная</a></li>
        <li><a href="catalog.php">Каталог</a></li>
        <li><a href="#" id="cart-link">Корзина (<span id="cart-count">0</span>)</a></li>
        <?php if ($user): ?>
            <li><a href="auth/dashboard.php">Личный кабинет (<?php echo htmlspecialchars($user['login']); ?>)</a></li>
            <li><a href="auth/logout.php">Выход</a></li>
        <?php else: ?>
            <li><a href="auth/login.php">Вход</a></li>
            <li><a href="auth/register.php">Регистрация</a></li>
        <?php endif; ?>
    </ul>    
    <hr>
    <h1>Алкомаркет</h1>
    <p>Добро пожаловать в Алкомаркет! У нас лучший выбор алкогольной продукции.</p>
    
    <?php if (!$user): ?>
    <div style="background: #e8f4f8; padding: 15px; margin: 20px auto; max-width: 500px; border-radius: 10px; text-align: center;">
        <p>Для оформления заказа необходимо <a href="auth/login.php">войти</a> или <a href="auth/register.php">зарегистрироваться</a>.</p>
    </div>
    <?php else: ?>
    <div style="background: #d4edda; padding: 15px; margin: 20px auto; max-width: 500px; border-radius: 10px; text-align: center;">
        <p>Добро пожаловать, <?php echo htmlspecialchars($user['login']); ?>! <a href="catalog.php">Перейти к покупкам →</a></p>
    </div>
    <?php endif; ?>
    
    <hr>
    <p>© Все права защищены</p>

    <div id="cart-modal" style="display: none;">
        <div class="cart-content">
            <h2>Корзина</h2>
            <div id="cart-items"></div>
            <div class="cart-total">
                <strong>Итого: <span id="cart-total">0</span> ₽</strong>
            </div>
            <button id="checkout-btn">Оплатить</button>
            <button id="clear-cart-btn">Очистить корзину</button>
            <button id="close-cart">Закрыть</button>
        </div>
    </div>

    <script src="cart.js"></script>
</body>
</html>