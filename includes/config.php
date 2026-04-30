<?php
session_start();
define('SITE_NAME', 'Алкомаркет');
define('LOGIN_PAGE', '/auth/login.php');
define('DASHBOARD_PAGE', '/auth/dashboard.php');

define('USERS_FILE', __DIR__ . '/../users.json');
define('LOGS_DIR', __DIR__ . '/../logs');
define('LOGS_FILE', LOGS_DIR . '/auth.log');

if (!file_exists(LOGS_DIR)) {
    mkdir(LOGS_DIR, 0777, true);
}

function getUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $content = file_get_contents(USERS_FILE);
    $users = json_decode($content, true);
    
    if (!is_array($users)) {
        return [];
    }
    
    return $users;
}

function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function findUserByLogin($login) {
    $users = getUsers();
    
    foreach ($users as $key => $user) {
        if (isset($user['login']) && $user['login'] === $login) {
            return $user;
        }
        if ($key === $login && isset($user['login']) && $user['login'] === $login) {
            return $user;
        }
    }
    return null;
}

function findUserByEmail($email) {
    $users = getUsers();
    foreach ($users as $user) {
        if (isset($user['email']) && $user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function findUserById($id) {
    $users = getUsers();
    foreach ($users as $user) {
        if (isset($user['id']) && $user['id'] == $id) {
            return $user;
        }
    }
    return null;
}

if (!file_exists(USERS_FILE)) {
    $defaultUsers = [
        [
            'id' => 1,
            'login' => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'email' => 'admin@alkomarket.ru',
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'login' => 'user',
            'password_hash' => password_hash('user123', PASSWORD_DEFAULT),
            'email' => 'user@example.com',
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 3,
            'login' => 'demo',
            'password_hash' => password_hash('demo123', PASSWORD_DEFAULT),
            'email' => 'demo@example.com',
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    saveUsers($defaultUsers);
}

function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function isAuthenticated() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_login']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: ' . LOGIN_PAGE . '?error=auth_required');
        exit;
    }
}
?>