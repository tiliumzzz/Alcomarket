<?php
require_once __DIR__ . '/controllers/UserController.php';

function handleRequest() {
    $method = $_SERVER['REQUEST_METHOD'];
    $requestUri = $_SERVER['REQUEST_URI'];
    
    $requestUri = strtok($requestUri, '?');

    error_log("Original URI: " . $requestUri);

    if (preg_match('#/api/index\.php/(.*)$#', $requestUri, $matches)) {
        $path = '/' . $matches[1];
    } else {
        $path = $requestUri;
    }

    if (strpos($path, '/api/v1') === 0) {
        $path = substr($path, 7);
        } elseif (strpos($path, '/v1') === 0) {
        $path = substr($path, 3);
    }

    if ($path === '') {
        $path = '/';
    }
  
    error_log("Processed path: " . $path);
  
    $controller = new UserController();
  
    switch ($method) {
        case 'POST':
            if ($path === '/register') {
                $controller->register();
            } elseif ($path === '/login') {
                $controller->login();
            } else {
                 sendNotFound($path);
            }
            break;

        case 'GET':
            if ($path === '/users') {
                $controller->getAllUsers();
            } elseif (preg_match('/^\/users\/(\d+)$/', $path, $matches)) {
                $controller->getUser($matches[1]);
            } else {
                sendNotFound($path);
            }
            break;

        case 'PUT':
        case 'PATCH':
            if (preg_match('/^\/users\/(\d+)$/', $path, $matches)) {
                $controller->updateUser($matches[1]);
            } else {
                sendNotFound($path);
            }
            break;
    
        case 'DELETE':
            if (preg_match('/^\/users\/(\d+)$/', $path, $matches)) {
                $controller->deleteUser($matches[1]);
            } else {
                sendNotFound($path);
            }
            break;

        default:
        sendNotFound($path);
        break;
    }
}

function sendNotFound($path = '') {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Маршрут не найден',
        'path' => $path 
    ]);
    exit;
}
?>