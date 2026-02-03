<?php
namespace App\Controllers;
use App\Models\InvoicesModel;
use App\Models\ProductsModel;

class SalesByProductReportController
{
    private $InvoicesModel;
    private $ProductsModel;
    public function __construct(){
        $this->InvoicesModel = new InvoicesModel();
        $this->ProductsModel = new ProductsModel();
    }

    public function index()
    {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $reportData = $this->InvoicesModel->getSalesByProduct($startDate, $endDate);

        require_once __DIR__ . '/../Views/sales_by_product.php';
    }

}
