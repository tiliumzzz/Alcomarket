<?php
define('USERS_FILE', __DIR__ . '/../../users.json');

function initUsersFile() {
    $usersDir = dirname(USERS_FILE);
    if (!file_exists($usersDir)) {
        mkdir($usersDir, 0777, true);
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
        file_put_contents(USERS_FILE, json_encode($defaultUsers, JSON_PRETTY_PRINT));
    }
}

function getUsers() {
    initUsersFile();
    $content = file_get_contents(USERS_FILE);
    return json_decode($content, true);
}

function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function findUserByLogin($login) {
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['login'] === $login) {
            return $user;
        }
    }
    return null;
}

function findUserByEmail($email) {
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function findUserById($id) {
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['id'] == $id) {
            return $user;
        }
    }
    return null;
}
?>