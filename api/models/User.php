<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $users;
    
    public function __construct() {
        $this->users = getUsers();
    }
    
    public function getAll() {
        return array_values($this->users);
    }
    
    public function getById($id) {
        $user = findUserById($id);
        if ($user) {
            unset($user['password_hash']);
            return $user;
        }
        return null;
    }
    
    public function create($login, $email, $password) {
        if (empty($login) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Все поля обязательны для заполнения'];
        }
        
        if (strlen($login) < 3) {
            return ['success' => false, 'message' => 'Логин должен содержать минимум 3 символа'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Пароль должен содержать минимум 6 символов'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Неверный формат email'];
        }
        
        if (findUserByLogin($login)) {
            return ['success' => false, 'message' => 'Пользователь с таким логином уже существует'];
        }
        
        if (findUserByEmail($email)) {
            return ['success' => false, 'message' => 'Пользователь с таким email уже существует'];
        }
        
        $users = getUsers();
        $newId = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;
        
        $newUser = [
            'id' => $newId,
            'login' => $login,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $users[] = $newUser;
        saveUsers($users);
        
        $response = $newUser;
        unset($response['password_hash']);
        
        return ['success' => true, 'data' => $response];
    }
    
    public function updatePassword($id, $oldPassword, $newPassword) {
        $user = findUserById($id);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь не найден'];
        }
        
        if (empty($oldPassword) || empty($newPassword)) {
            return ['success' => false, 'message' => 'Текущий и новый пароль обязательны'];
        }
        
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'Новый пароль должен содержать минимум 6 символов'];
        }
        
        if (!password_verify($oldPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Неверный текущий пароль'];
        }
        
        $users = getUsers();
        foreach ($users as &$u) {
            if ($u['id'] == $id) {
                $u['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
                break;
            }
        }
        saveUsers($users);
        
        return ['success' => true, 'message' => 'Пароль успешно изменён'];
    }
    
    public function delete($id) {
        $user = findUserById($id);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь не найден'];
        }
        
        $users = getUsers();
        $filteredUsers = array_filter($users, function($u) use ($id) {
            return $u['id'] != $id;
        });
        
        saveUsers(array_values($filteredUsers));
        
        return ['success' => true, 'message' => 'Пользователь успешно удалён'];
    }
}
?>