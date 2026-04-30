<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (isAuthenticated()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $result = $auth->login($_POST['login'], $_POST['password']);
        
        if ($result['success']) {
            $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dashboard.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'auth_required':
            $error = 'Для доступа к этой странице необходимо авторизоваться';
            break;
        case 'register_success':
            $success = 'Регистрация успешна! Теперь вы можете войти.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в личный кабинет - Алкомаркет</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: rgb(44, 62, 80);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: rgb(44, 62, 80);
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: rgb(2, 41, 68);
        }
        .auth-button {
            width: 100%;
            padding: 12px;
            background: rgb(2, 41, 68);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .auth-button:hover {
            background: rgb(238, 33, 10);
        }
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        .auth-links a {
            color: rgb(2, 41, 68);
            text-decoration: none;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .nav-links {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-links a {
            margin: 0 10px;
            color: rgb(2, 41, 68);
            text-decoration: none;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <ul>
        <li><a href="../index.php">Главная</a></li>
        <li><a href="../catalog.php">Каталог</a></li>
        <li><a href="#" id="cart-link">Корзина (<span id="cart-count">0</span>)</a></li>
        <li><a href="login.php">Вход</a></li>
        <li><a href="register.php">Регистрация</a></li>
    </ul>
    <hr>
    
    <div class="auth-container">
        <h2>Вход в личный кабинет</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="auth-button">Войти</button>
        </form>
        
        <div class="auth-links">
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
            <p><a href="../index.html">Вернуться на главную</a></p>
        </div>
        
        <div class="nav-links">
            <hr>
            <p><small>Тестовые учетные записи:</small></p>
            <p><small>admin / admin123 | user / user123 | demo / demo123</small></p>
        </div>
    </div>
    
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
    
    <script src="../cart.js"></script>
</body>
</html>