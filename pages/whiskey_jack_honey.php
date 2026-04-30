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
    <title>Алкомаркет - Jack Daniel's Honey</title>
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
        <h1>Виски Jack Daniel's Honey</h1>
        
        <a href="https://static.decanter.ru/upload/images/413079/413079-viski-jack-daniels-honey-1-l-mb.jpg" target="_blank">
            <img src="https://static.decanter.ru/upload/images/413079/413079-viski-jack-daniels-honey-1-l-mb.jpg" alt="Jack Daniel's Honey" width="300" height="500">
        </a>
        
        <h2>Описание</h2>
        <p class="short-description">Jack Daniel's Honey — это купаж оригинального виски Jack Daniel's Old No. 7 и медового ликера. Напиток имеет мягкий сладковатый вкус с нотками меда и ванили.</p>

        <h2>Характеристики</h2>
        <ul>
            <li>Крепость: 35%</li>
            <li>Объем: 1 л</li>
            <li>Страна: США</li>
            <li>Тип: Медовый ликер на основе бурбона</li>
            <li>Выдержка: не менее 4 лет</li>
        </ul>
        
        <div class="product-price" data-price="2500">Цена: 2500 ₽</div>
        <button class="add-to-cart-page" data-id="1" data-name="Виски Jack Daniel's Honey (Медовый)" data-price="2500">Добавить в корзину</button>
        
        <h2>Подробное описание товара</h2>  
        <p class="full-description">Jack Daniel's Tennessee Honey - это уникальный напиток, который сочетает в себе благородный виски и натуральный медовый ликер. Напиток производится в Линчберге, штат Теннесси, с использованием родниковой воды и тщательно отобранного зерна. Мед придает виски дополнительную мягкость и сладость, делая его идеальным для употребления в чистом виде или в коктейлях. Этот напиток, впервые представленный в 2011 году, быстро завоевал мировую популярность благодаря своему гармоничному и не приторно-сладкому вкусу. Секрет его успеха — в идеальном балансе: характерная крепость и дубовые ноты классического виски Джек Дэниэлс смягчаются и дополняются сладостью натурального меда и пряностью корицы. Jack Daniel's Honey обладает округлым, теплым вкусом с ванильно-карамельными акцентами и долгим, гладким послевкусием.</p>
        <hr>
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