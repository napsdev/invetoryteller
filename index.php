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
use App\Controllers\MigrateController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$router = new Router();

function checkAuth() {
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/login');
        exit();
    }
}

$router->addRoute('GET', '/', function () {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
});

//Migrate old-database to new-database
$router->addRoute('GET', '/migrate', function () {
    checkAuth();
    $controller = new MigrateController();
    $controller->migrate();
});


//PDF
$router->addRoute('GET', '/pdf/{id}', function ($id) {
    checkAuth();
    $controller = new PdfController();
    $controller->create($id);
});

$router->addRoute('POST', '/pdf', function () {
    checkAuth();
    $controller = new PdfController();
    $controller->search();
});

//TABLES
$router->addRoute('GET', '/table', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->table();
});

$router->addRoute('POST', '/table', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->table();
});

//MAIN
$router->addRoute('GET', '/salidas', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->index();
});

$router->addRoute('POST', '/salidas/create', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->create();
});

$router->addRoute('POST', '/salidas/quote', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->quote();
});

$router->addRoute('POST', '/salidas/anular', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->cancel();
});

$router->addRoute('POST', '/salidas/aprobar', function () {
    checkAuth();
    $controller = new InvoicesController();
    $controller->approve();
});

//BUSINESS INFORMATION
$router->addRoute('GET', '/repsalidas', function () {
    checkAuth();
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
    checkAuth();
    $controller = new PaymentMethodsController();
    $controller->index();
});

$router->addRoute('POST', '/pagos/create', function () {
    checkAuth();
    $controller = new PaymentMethodsController();
    $controller->create();
});

$router->addRoute('POST', '/pagos/delete', function () {
    checkAuth();
    $controller = new PaymentMethodsController();
    $controller->delete();
});

$router->addRoute('POST', '/pagos/update', function () {
    checkAuth();
    $controller = new PaymentMethodsController();
    $controller->update();
});

//PRODUCTS
$router->addRoute('GET', '/productos', function () {
    checkAuth();
    $controller = new ProductsController();
    $controller->index();
});

$router->addRoute('POST', '/productos/create', function () {
    checkAuth();
    $controller = new ProductsController();
    $controller->create();
});

$router->addRoute('POST', '/productos/delete', function () {
    checkAuth();
    $controller = new ProductsController();
    $controller->delete();
});

$router->addRoute('POST', '/productos/update', function () {
    checkAuth();
    $controller = new ProductsController();
    $controller->update();
});

//PRODUCT ADD STOCK
$router->addRoute('GET', '/entradas', function () {
    checkAuth();
    $controller = new ProductEntriesController();
    $controller->index();
});

$router->addRoute('POST', '/entradas/create', function () {
    checkAuth();
    $controller = new ProductEntriesController();
    $controller->create();
});

$router->addRoute('POST', '/entradas/delete', function () {
    checkAuth();
    $controller = new ProductEntriesController();
    $controller->delete();
});

//CUSTOMERS
$router->addRoute('GET', '/clientes', function () {
    checkAuth();
    $controller = new CustomerController();
    $controller->index();
});

$router->addRoute('POST', '/clientes/create', function () {
    checkAuth();
    $controller = new CustomerController();
    $controller->create();
});

$router->addRoute('POST', '/clientes/delete', function () {
    checkAuth();
    $controller = new CustomerController();
    $controller->delete();
});

$router->addRoute('POST', '/clientes/update', function () {
    checkAuth();
    $controller = new CustomerController();
    $controller->update();
});

//EXPENSES
$router->addRoute('GET', '/gastos', function () {
    checkAuth();
    $controller = new ExpensesController();
    $controller->index();
});

$router->addRoute('POST', '/gastos/create', function () {
    checkAuth();
    $controller = new ExpensesController();
    $controller->create();
});

$router->addRoute('POST', '/gastos/delete', function () {
    checkAuth();
    $controller = new ExpensesController();
    $controller->delete();
});

$router->addRoute('POST', '/gastos/update', function () {
    checkAuth();
    $controller = new ExpensesController();
    $controller->update();
});

//SUPPLIERS
$router->addRoute('GET', '/proveedores', function () {
    checkAuth();
    $controller = new SuppliersController();
    $controller->index();
});

$router->addRoute('POST', '/proveedores/create', function () {
    checkAuth();
    $controller = new SuppliersController();
    $controller->create();
});

$router->addRoute('POST', '/proveedores/delete', function () {
    checkAuth();
    $controller = new SuppliersController();
    $controller->delete();
});

$router->addRoute('POST', '/proveedores/update', function () {
    checkAuth();
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
