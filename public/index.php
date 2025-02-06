<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/') ?: '/';

$routes = [
    '/' => ['controller' => 'App\Controllers\Authentication', 'action' => 'loginForm'],
    '/index.php' => ['controller' => 'App\Controllers\Authentication', 'action' => 'loginForm'],
    '/index' => ['controller' => 'App\Controllers\Authentication', 'action' => 'loginForm'],
    '/login' => ['controller' => 'App\Controllers\Authentication', 'action' => 'loginForm'],
    '/Login' => ['controller' => 'App\Controllers\Authentication', 'action' => 'loginForm'],
    '/logout' => ['controller' => 'App\Controllers\Authentication', 'action' => 'logout'],
    '/Logout' => ['controller' => 'App\Controllers\Authentication', 'action' => 'logout'],

    '/manager/dashboard' => ['controller' => 'App\Controllers\Manager', 'action' => 'dashboard'],
    '/manager/create-employee' => ['controller' => 'App\Controllers\Manager', 'action' => 'createEmployee'],
    '/manager/edit-employee' => ['controller' => 'App\Controllers\Manager', 'action' => 'editEmployee'],
    '/manager/delete-employee' => ['controller' => 'App\Controllers\Manager', 'action' => 'deleteEmployee'],
    '/manager/manage-requests' => ['controller' => 'App\Controllers\Manager', 'action' => 'manageRequests'],
    '/manager/update-request' => ['controller' => 'App\Controllers\Manager', 'action' => 'updateRequestStatus'],

    '/employee/dashboard' => ['controller' => 'App\Controllers\Employee', 'action' => 'dashboard'],
    '/employee/create-request' => ['controller' => 'App\Controllers\Employee', 'action' => 'createRequest'],
    '/employee/delete-request' => ['controller' => 'App\Controllers\Employee', 'action' => 'deleteRequest']
];

if (isset($routes[$requestUri])) {
    $route = $routes[$requestUri];
    $controllerName = $route['controller'];
    $actionName = $route['action'];

    $controller = new $controllerName();

    $controller->$actionName($_GET);
} else {
    http_response_code(404);
    echo "404 - Page not found";
}
