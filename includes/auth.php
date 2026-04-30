<?php
require_once 'config.php';
require_once 'logger.php';

class Auth {
    private $usersFile;
    private $logger;
    
    public function __construct() {
        $this->usersFile = USERS_FILE;
        $this->logger = $GLOBALS['logger'];
    }
    
    private function getUsers() {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        $content = file_get_contents($this->usersFile);
        return json_decode($content, true);
    }
    
    private function saveUsers($users) {
        file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }
    
    public function login($login, $password) {
    $login = trim($login);
    $user = findUserByLogin($login);
    
    if (!$user) {
        $this->logger->logFailedLogin($login, 'Пользователь не найден');
        return ['success' => false, 'message' => 'Неверный логин или пароль'];
    }
    
    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['login_time'] = time();
        
        $this->logger->logSuccessLogin($login);
        return ['success' => true, 'message' => 'Добро пожаловать!'];
    } else {
        $this->logger->logFailedLogin($login, 'Неверный пароль');
        return ['success' => false, 'message' => 'Неверный логин или пароль'];
    }
    }
    
    public function logout() {
        if (isset($_SESSION['user_login'])) {
            $this->logger->logLogout($_SESSION['user_login']);
        }
        
        $_SESSION = array();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        session_destroy();
        return ['success' => true, 'message' => 'Вы вышли из системы'];
    }
    
    public function register($login, $password, $email) {
        $login = trim($login);
        $email = trim($email);
        $users = $this->getUsers();
        
        if (strlen($login) < 3) {
            return ['success' => false, 'message' => 'Логин должен содержать минимум 3 символа'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Пароль должен содержать минимум 6 символов'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Неверный формат email'];
        }
        
        if (isset($users[$login])) {
            return ['success' => false, 'message' => 'Пользователь с таким логином уже существует'];
        }
        
        $newUser = [
            'id' => count($users) + 1,
            'login' => $login,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'role' => 'user',
            'created_at' => date('d-m-Y H:i:s')
        ];
        
        $users[$login] = $newUser;
        $this->saveUsers($users);
        
        $this->logger->logRegister($login);
        return ['success' => true, 'message' => 'Регистрация успешна! Теперь вы можете войти.'];
    }
    
    public function getCurrentUser() {
        if (!isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'login' => $_SESSION['user_login'],
            'role' => $_SESSION['user_role'],
            'email' => $_SESSION['user_email']
        ];
    }
}

$auth = new Auth();
?>