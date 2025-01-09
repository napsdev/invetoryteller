<?php
namespace App\Controllers;
use App\Models\ProductsModel;

class ProductsController
{
    public function index()
    {
        $ProductsModel = new ProductsModel();
        $table = $ProductsModel->list();
        require_once __DIR__ . '/../Views/products.php';
    }

    public function create()
    {
        $name = $_POST['name'] ?? null;
        $purchase_price = $_POST['purchase_price'] ?? null;
        $sales_price = $_POST['sales_price'] ?? null;
        $amount = $_POST['amount'] ?? null;

        $ProductsModel = new ProductsModel();
        $message = $ProductsModel->create($name,$purchase_price,$sales_price,$amount);

        header('Location: '.$_ENV['BASE_URL_PATH'].'/productos?message='.urlencode($message));
    }

}