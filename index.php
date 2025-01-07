<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use App\Router;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$router = new Router();

$router->addRoute('GET', '/', function () {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
});

$router->addRoute('GET', '/login', function () {
    $controller = new LoginController();
    $controller->login();
});

$router->addRoute('POST', '/login', function () {
    $controller = new LoginController();
    $controller->login();
});

$router->addRoute('GET', '/logout', function () {
    $controller = new LoginController();
    $controller->logout();
});

$router->addRoute('GET', '/dashboard', function () {
    $controller = new DashboardController();
    $controller->index();
});

$router->addRoute('GET', '/syncuser', function () {
    $controller = new LoginController();
    $controller->syncUserFromEnv();
});


$router->dispatch();
