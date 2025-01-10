<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use App\Router;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;
use App\Controllers\InvoicesController;
use App\Controllers\PaymentMethodsController;
use App\Controllers\ProductsController;
use App\Controllers\ProductEntriesController;
use App\Controllers\CustomerController;
use App\Controllers\ExpensesController;
use App\Controllers\SuppliersController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$router = new Router();

$router->addRoute('GET', '/', function () {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
});

//LOGIN
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

//MAIN
$router->addRoute('GET', '/salidas', function () {
    $controller = new DashboardController();
    $controller->index();
});

//BUSINESS INFORMATION
$router->addRoute('GET', '/repsalidas', function () {
    $controller = new InvoicesController();
    $controller->index();
});

//PAYMENT METHODS
$router->addRoute('GET', '/pagos', function () {
    $controller = new PaymentMethodsController();
    $controller->index();
});

$router->addRoute('POST', '/pagos/create', function () {
    $controller = new PaymentMethodsController();
    $controller->create();
});

$router->addRoute('POST', '/pagos/delete', function () {
    $controller = new PaymentMethodsController();
    $controller->delete();
});

$router->addRoute('POST', '/pagos/update', function () {
    $controller = new PaymentMethodsController();
    $controller->update();
});

//PRODUCTS
$router->addRoute('GET', '/productos', function () {
    $controller = new ProductsController();
    $controller->index();
});

$router->addRoute('POST', '/productos/create', function () {
    $controller = new ProductsController();
    $controller->create();
});

$router->addRoute('POST', '/productos/delete', function () {
    $controller = new ProductsController();
    $controller->delete();
});

$router->addRoute('POST', '/productos/update', function () {
    $controller = new ProductsController();
    $controller->update();
});

//PRODUCT ADD STOCK
$router->addRoute('GET', '/entradas', function () {
    $controller = new ProductEntriesController();
    $controller->index();
});

//CUSTOMERS
$router->addRoute('GET', '/clientes', function () {
    $controller = new CustomerController();
    $controller->index();
});

//EXPENSES
$router->addRoute('GET', '/gastos', function () {
    $controller = new ExpensesController();
    $controller->index();
});

//SUPPLIERS
$router->addRoute('GET', '/proveedores', function () {
    $controller = new SuppliersController();
    $controller->index();
});



//Create or update user with environment variables
$router->addRoute('GET', '/syncuser', function () {
    $controller = new LoginController();
    $controller->syncUserFromEnv();
});


$router->dispatch();
