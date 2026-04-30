<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
$user = isAuthenticated() ? $auth->getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="../style.css">
    <meta charset="UTF-8">
    <title>Алкомаркет - Ром Barcelo Anejo</title>
</head>
<body>
    <ul>
        <li><a href="../index.php">Главная</a></li>
        <li><a href="../catalog.php">Каталог</a></li>
        <li><a href="#" id="cart-link">Корзина (<span id="cart-count">0</span>)</a></li>
        <?php if ($user): ?>
            <li><a href="../auth/dashboard.php">Личный кабинет (<?php echo htmlspecialchars($user['login']); ?>)</a></li>
            <li><a href="../auth/logout.php">Выход</a></li>
        <?php else: ?>
            <li><a href="../auth/login.php">Вход</a></li>
            <li><a href="../auth/register.php">Регистрация</a></li>
        <?php endif; ?>
    </ul>
    <hr>
    <div class="product-page">
        <h1>Ром Barcelo Anejo</h1>

        <a href="https://cdn.amwine.ru/upload/resize_cache/iblock/7e5/620_620_1/7e5538d1ba4e40078e374c2e2797474c.png" target="_blank">
            <img src="https://cdn.amwine.ru/upload/resize_cache/iblock/7e5/620_620_1/7e5538d1ba4e40078e374c2e2797474c.png" alt="Barcelona Añejo" width="200" height="500">
        </a>

        <h2>Описание</h2>
        <p class="short-description">Barcelo Anejo — выдержанный ром из Доминиканской Республики. Обладает насыщенным вкусом с нотами карамели, дуба и тропических фруктов.</p>

        <h2>Характеристики</h2>
        <ul>
            <li>Крепость: 38%</li>
            <li>Объем: 0.7 л</li>
            <li>Страна: Доминиканская Республика</li>
            <li>Выдержка: 5 лет</li>
            <li>Тип: Выдержанный ром</li>
        </ul>
        <div class="product-price" data-price="2800">Цена: 2800 ₽</div>
        <button class="add-to-cart-page" data-id="3" data-name="Ром Barcelo Anejo" data-price="2800">Добавить в корзину</button>
        <hr>
        <h2>Подробное описание товара</h2>
        <p class="full-description">Этот ром изготавливается из дистиллятов сахарного тростника, которые выдерживаются в течение нескольких лет в бочках из американского дуба, что придает ему глубину и характер. Его аромат — это сложная симфония нот обожженного дуба, орехов, сушеной травы и сладкой карамели. Вкус — насыщенный, цветочно-фруктовый, с доминирующими акцентами карамели, ванили, орехов и специй, переходящими в согревающее шоколадно-сливочное послевкусие. Благодаря своей многогранности, Barcelo Añejo идеально подходит как для употребления в чистом виде в качестве дижестива, так и для создания сложных коктейлей.</p>

        <p>© Все права защищены</p>
    </div>
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
    <script src="../cart.js"></script>
</body>
</html>