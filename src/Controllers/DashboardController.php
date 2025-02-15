<?php
namespace App\Controllers;
use App\Models\InvoicesModel;
use App\Models\ProductsModel;

class DashboardController
{
    private $InvoicesModel;
    private $ProductsModel;
    public function __construct(){
        $this->InvoicesModel = new InvoicesModel();
        $this->ProductsModel = new ProductsModel();
    }

    public function index()
    {
        $table = $this->InvoicesModel->list();
        $tableProducts = $this->ProductsModel->list();
        require_once __DIR__ . '/../Views/dashboard.php';
    }
}