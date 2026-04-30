<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/logger.php';

requireAuth();

$user = $auth->getCurrentUser();
$logger = new AuthLogger();
$logs = $logger->getLogs(50);

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth->logout();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Алкомаркет</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .welcome-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .welcome-card h2 {
            color: rgb(44, 62, 80);
            margin-bottom: 15px;
        }
        .user-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .user-info p {
            margin: 8px 0;
        }
        .logout-btn {
            background: rgb(238, 33, 10);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .logs-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .logs-section h3 {
            color: rgb(44, 62, 80);
            margin-bottom: 15px;
        }
        .logs-table {
            width: 100%;
            border-collapse: collapse;
        }
        .logs-table th,
        .logs-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .logs-table th {
            background: rgb(2, 41, 68);
            color: white;
        }
        .logs-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .logs-table tr:hover {
            background: #f5f5f5;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, rgb(2, 41, 68), rgb(44, 62, 80));
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card h4 {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        .stat-card .stat-value {
            font-size: 32px;
            font-weight: bold;
        }
        .admin-badge {
            background: rgb(238, 33, 10);
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
        <li><a href="../index.php">Главная</a></li>
        <li><a href="../catalog.php">Каталог</a></li>
        <li><a href="#" id="cart-link">Корзина (<span id="cart-count">0</span>)</a></li>
        <li><a href="dashboard.php">Личный кабинет</a></li>
        <li><a href="?action=logout" onclick="return confirm('Вы уверены, что хотите выйти?')">Выход</a></li>
    </ul>
    <hr>
    
    <div class="dashboard-container">
        <div class="welcome-card">
            <h2>
                Личный кабинет
                <?php if ($user['role'] === 'admin'): ?>
                    <span class="admin-badge">Администратор</span>
                <?php endif; ?>
            </h2>
            <div class="user-info">
                <p><strong>Логин:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Роль:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                <p><strong>Время входа:</strong> <?php echo date('d.m.Y H:i:s', $_SESSION['login_time']); ?></p>
            </div>
            <button class="logout-btn" onclick="if(confirm('Вы уверены, что хотите выйти?')) window.location.href='?action=logout'">Выйти из системы</button>
        </div>
        
        <?php if ($user['role'] === 'admin'): ?>
        <div class="stats-grid">
            <div class="stat-card">
                <h4>Всего зарегистрированных пользователей</h4>
                <div class="stat-value">
                    <?php 
                        $users = json_decode(file_get_contents(USERS_FILE), true);
                        echo count($users);
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h4>Записей в логах</h4>
                <div class="stat-value">
                    <?php 
                        $logFile = LOGS_FILE;
                        if (file_exists($logFile)) {
                            $lines = file($logFile);
                            echo count($lines);
                        } else {
                            echo '0';
                        }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="logs-section">
            <h3>Журнал событий авторизации (последние 50 записей)</h3>
            <?php if (empty($logs)): ?>
                <p>Логов пока нет.</p>
            <?php else: ?>
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>Дата и время</th>
                            <th>IP адрес</th>
                            <th>Логин</th>
                            <th>Действие</th>
                            <th>Детали</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <?php
                            preg_match('/\[(.*?)\].*?IP: (.*?) \| LOGIN: (.*?) \| ACTION: (.*?)(?: \| DETAILS: (.*))?/', $log, $matches);
                            if (count($matches) >= 5):
                                $date = $matches[1];
                                $ip = $matches[2];
                                $login = $matches[3];
                                $action = $matches[4];
                                $details = $matches[5] ?? '';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($date); ?></td>
                                <td><?php echo htmlspecialchars($ip); ?></td>
                                <td><?php echo htmlspecialchars($login); ?></td>
                                <td><?php echo htmlspecialchars($action); ?></td>
                                <td><?php echo htmlspecialchars($details); ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="welcome-card">
            <h3>Добро пожаловать в Алкомаркет!</h3>
            <p>Здесь вы можете отслеживать свои заказы и управлять профилем.</p>
            <p><a href="../catalog.html">Перейти в каталог товаров →</a></p>
        </div>
        <?php endif; ?>
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