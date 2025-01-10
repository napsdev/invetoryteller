<?php
namespace App\Controllers;
use App\Models\InvoicesModel;
use App\Models\ProductsModel;
use App\Models\CustomerModel;
use App\Models\PaymentMethodsModel;

class InvoicesController
{
    private $InvoicesModel;
    public function __construct(){
        $this->InvoicesModel = new InvoicesModel();
    }

    public function index()
    {
        $ProductsModel = new ProductsModel();
        $listProducts = $ProductsModel->list();

        $CustomerModel = new CustomerModel();
        $listClients = $CustomerModel->list();

        $PaymentMethodsModel = new PaymentMethodsModel();
        $listPaymentsMethods = $PaymentMethodsModel->list();

        require_once __DIR__ . '/../Views/invoices.php';
    }

    public function create()
    {
        echo 'Hola';
    }

}