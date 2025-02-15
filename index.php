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
use App\Controllers\PdfController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$router = new Router();

$router->addRoute('GET', '/', function () {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
});


//PDF
$router->addRoute('GET', '/pdf/{id}', function ($id) {
    $controller = new PdfController();
    $controller->create($id);
});

//TABLES
$router->addRoute('GET', '/table', function () {
    $controller = new InvoicesController();
    $controller->table();
});

$router->addRoute('POST', '/table', function () {
    $controller = new InvoicesController();
    $controller->table();
});

//MAIN
$router->addRoute('GET', '/salidas', function () {
    $controller = new InvoicesController();
    $controller->index();
});

$router->addRoute('POST', '/salidas/create', function () {
    $controller = new InvoicesController();
    $controller->create();
});

$router->addRoute('POST', '/salidas/quote', function () {
    $controller = new InvoicesController();
    $controller->quote();
});

$router->addRoute('POST', '/salidas/anular', function () {
    $controller = new InvoicesController();
    $controller->cancel();
});

$router->addRoute('POST', '/salidas/aprobar', function () {
    $controller = new InvoicesController();
    $controller->approve();
});

//BUSINESS INFORMATION
$router->addRoute('GET', '/repsalidas', function () {
    $controller = new DashboardController();
    $controller->index();
});
//MAIN END

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
//LOGIN END



//CRUDS
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

$router->addRoute('POST', '/entradas/create', function () {
    $controller = new ProductEntriesController();
    $controller->create();
});

$router->addRoute('POST', '/entradas/delete', function () {
    $controller = new ProductEntriesController();
    $controller->delete();
});

//CUSTOMERS
$router->addRoute('GET', '/clientes', function () {
    $controller = new CustomerController();
    $controller->index();
});

$router->addRoute('POST', '/clientes/create', function () {
    $controller = new CustomerController();
    $controller->create();
});

$router->addRoute('POST', '/clientes/delete', function () {
    $controller = new CustomerController();
    $controller->delete();
});

$router->addRoute('POST', '/clientes/update', function () {
    $controller = new CustomerController();
    $controller->update();
});

//EXPENSES
$router->addRoute('GET', '/gastos', function () {
    $controller = new ExpensesController();
    $controller->index();
});

$router->addRoute('POST', '/gastos/create', function () {
    $controller = new ExpensesController();
    $controller->create();
});

$router->addRoute('POST', '/gastos/delete', function () {
    $controller = new ExpensesController();
    $controller->delete();
});

$router->addRoute('POST', '/gastos/update', function () {
    $controller = new ExpensesController();
    $controller->update();
});

//SUPPLIERS
$router->addRoute('GET', '/proveedores', function () {
    $controller = new SuppliersController();
    $controller->index();
});

$router->addRoute('POST', '/proveedores/create', function () {
    $controller = new SuppliersController();
    $controller->create();
});

$router->addRoute('POST', '/proveedores/delete', function () {
    $controller = new SuppliersController();
    $controller->delete();
});

$router->addRoute('POST', '/proveedores/update', function () {
    $controller = new SuppliersController();
    $controller->update();
});
//CRUDS END






//Create or update user with environment variables
$router->addRoute('GET', '/syncuser', function () {
    //$controller = new LoginController();
    //$controller->syncUserFromEnv();
});
//Create or update user with environment variables end

$router->dispatch();
