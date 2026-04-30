<?php
require_once __DIR__ . '/controllers/UserController.php';

function handleRequest() {
    $method = $_SERVER['REQUEST_METHOD'];
    $requestUri = $_SERVER['REQUEST_URI'];
    
    $requestUri = strtok($requestUri, '?');
    
    if (strpos($requestUri, '/api/v1') === 0) {
        $requestUri = substr($requestUri, 7);
    }
    
    $controller = new UserController();
    
    switch ($method) {
        case 'POST':
            if ($requestUri === '/register') {
                $controller->register();
            } elseif ($requestUri === '/login') {
                $controller->login();
            } else {
                sendNotFound();
            }
            break;
            
        case 'GET':
            if ($requestUri === '/users') {
                $controller->getAllUsers();
            } elseif (preg_match('/^\/users\/(\d+)$/', $requestUri, $matches)) {
                $controller->getUser($matches[1]);
            } else {
                sendNotFound();
            }
            break;
            
        case 'PUT':
        case 'PATCH':
            if (preg_match('/^\/users\/(\d+)$/', $requestUri, $matches)) {
                $controller->updateUser($matches[1]);
            } else {
                sendNotFound();
            }
            break;
            
        case 'DELETE':
            if (preg_match('/^\/users\/(\d+)$/', $requestUri, $matches)) {
                $controller->deleteUser($matches[1]);
            } else {
                sendNotFound();
            }
            break;
            
        default:
            sendNotFound();
            break;
    }
}

function sendNotFound() {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Маршрут не найден']);
    exit;
}
?>