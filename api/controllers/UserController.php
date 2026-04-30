<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный формат запроса'], 400);
        }
        
        $login = $input['login'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        
        $result = $this->userModel->create($login, $email, $password);
        
        if ($result['success']) {
            $this->sendResponse($result, 201);
        } else {
            $this->sendResponse($result, 400);
        }
    }
    
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный формат запроса'], 400);
        }
        
        $login = $input['login'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($login) || empty($password)) {
            $this->sendResponse(['success' => false, 'message' => 'Логин и пароль обязательны'], 400);
        }
        
        $user = findUserByLogin($login);
        
        if (!$user) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный логин или пароль'], 401);
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный логин или пароль'], 401);
        }
        
        $response = [
            'success' => true,
            'message' => 'Авторизация успешна',
            'data' => [
                'id' => $user['id'],
                'login' => $user['login'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ];
        
        $this->sendResponse($response);
    }
    
    public function getAllUsers() {
        $users = $this->userModel->getAll();
        $this->sendResponse(['success' => true, 'data' => $users]);
    }
    
    public function getUser($id) {
        if (!$id || !is_numeric($id)) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный ID пользователя'], 400);
        }
        
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            $this->sendResponse(['success' => false, 'message' => 'Пользователь не найден'], 404);
        }
        
        $this->sendResponse(['success' => true, 'data' => $user]);
    }
    
    public function updateUser($id) {
        if (!$id || !is_numeric($id)) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный ID пользователя'], 400);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный формат запроса'], 400);
        }
        
        $oldPassword = $input['old_password'] ?? '';
        $newPassword = $input['new_password'] ?? '';
        
        $result = $this->userModel->updatePassword($id, $oldPassword, $newPassword);
        
        if ($result['success']) {
            $this->sendResponse($result);
        } else {
            $this->sendResponse($result, 400);
        }
    }
    
    public function deleteUser($id) {
        if (!$id || !is_numeric($id)) {
            $this->sendResponse(['success' => false, 'message' => 'Неверный ID пользователя'], 400);
        }
        
        $result = $this->userModel->delete($id);
        
        if ($result['success']) {
            $this->sendResponse($result);
        } else {
            $this->sendResponse($result, 404);
        }
    }
}
?>