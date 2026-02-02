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
        $year = $_GET['year'] ?? date('Y');
        $table = $this->InvoicesModel->list($year);
        $tableProducts = $this->ProductsModel->list();
        $chart = $this->InvoicesModel->charts($year);
        $chartrevenue = $this->InvoicesModel->chartrevenue($year);
        $lastDayRevenue = $this->InvoicesModel->getLastDayRevenue($year);
        $lastdaycharts = $this->InvoicesModel->getlastdaycharts($year);
        require_once __DIR__ . '/../Views/dashboard.php';
    }
}