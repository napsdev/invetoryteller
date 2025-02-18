<?php
namespace App\Controllers;
use App\Models\ProductsModel;

class ProductsController
{
    private $ProductsModel;
    public function __construct(){
        $this->ProductsModel = new ProductsModel();
    }
    public function index()
    {
        $table = $this->ProductsModel->list();
        require_once __DIR__ . '/../Views/products.php';
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $barcod = $_POST['barcod'] ?? null;
        $purchase_price = $_POST['purchase_price'] ?? 0;
        $sales_price = $_POST['sales_price'] ?? 0;
        $amount = $_POST['amount'] ?? 0;
        $cartridgevalue = $_POST['cartridgevalue'] ?? null;
        $cartridge = $_POST['cartridge'] ?? null;

        if ($id) {
            $message = $this->ProductsModel->update($id,$name,$purchase_price,$sales_price,$amount,$barcod,$cartridge,$cartridgevalue);
        } else {
            $message = 'ID no proporcionado.';
        }
        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/productos?message=' . urlencode($message));
    }
    public function create()
    {
        $name = $_POST['name'] ?? null;
        $barcod = $_POST['barcod'] ?? null;
        $purchase_price = $_POST['purchase_price'] ?? null;
        $sales_price = $_POST['sales_price'] ?? null;
        $amount = $_POST['amount'] ?? null;
        
        $cartridgevalue = $_POST['cartridgevalue'] ?? null;
        $cartridge = $_POST['cartridge'] ?? null;


        $message = $this->ProductsModel->create($name,$purchase_price,$sales_price,$amount,$barcod,$cartridge,$cartridgevalue);

        header('Location: '.$_ENV['BASE_URL_PATH'].'/productos?message='.urlencode($message));
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $message = $this->ProductsModel->delete($id);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/productos?message='.urlencode($message));
    }

}