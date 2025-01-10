<?php
namespace App\Controllers;
use App\Models\ProductEntriesModel;
use App\Models\ProductsModel;
class ProductEntriesController
{
    private $ProductEntriesModel;
    public function __construct(){
        $this->ProductEntriesModel = new ProductEntriesModel();
    }
    public function index()
    {
        $ProductsModel = new ProductsModel();
        $listProducts = $ProductsModel->list();
        require_once __DIR__ . '/../Views/productentries.php';
    }

    public function create()
    {
        $product_id = $_POST['product_id'] ?? null;
        $amount = $_POST['amount'] ?? 0;
        if ($product_id) {
            $message = $this->ProductEntriesModel->create($product_id, $amount);
        } else {
            $message = 'Producto no proporcionado.';
        }
        header('Location: '.$_ENV['BASE_URL_PATH'].'/entradas?message='.urlencode($message));
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $message = $this->ProductEntriesModel->delete($id);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/entradas?message='.urlencode($message));
    }
}