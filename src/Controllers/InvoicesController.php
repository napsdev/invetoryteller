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
        $products = json_decode($_POST['products'], true);
        $OptionCustomerSelect = $_POST['newCustomer'] ?? null;
        $name = $_POST['newCustomername']?? null;
        $contact = $_POST['newCustomercontact'] ?? null;
        $customer_id = $_POST['customer_id'] ?? null;
        $paymentmethods_id = $_POST['paymentmethods_id'] ?? null;
        $trackingcode = $_POST['trackingcode'] ?? null;

        if (isset($_POST['pending_call'])) {
            $pending_call = 1; //check
        }else{
            $pending_call = 2;
        }

        $message = $this->InvoicesModel->create($customer_id,$name,$contact,$products,$pending_call,$paymentmethods_id,$OptionCustomerSelect, $trackingcode);;

        if(is_numeric($message)){
            header('Location: ' . $_ENV['BASE_URL_PATH'] . '/pdf/' .$message);
        }else{
            header('Location: ' . $_ENV['BASE_URL_PATH'] . '/salidas?message=' . urlencode($message));
        }

    }

    public function quote()
    {
        $products = json_decode($_POST['products'], true);
        $OptionCustomerSelect = $_POST['newCustomer'] ?? null;
        $name = $_POST['newCustomername']?? null;
        $contact = $_POST['newCustomercontact'] ?? null;
        $customer_id = $_POST['customer_id'] ?? null;
        $paymentmethods_id = $_POST['paymentmethods_id'] ?? null;
        $trackingcode = $_POST['trackingcode'] ?? null;

        if (isset($_POST['pending_call'])) {
            $pending_call = 1; //check
        }else{
            $pending_call = 2;
        }

        $message = $this->InvoicesModel->quote($customer_id,$name,$contact,$products,$pending_call,$paymentmethods_id,$OptionCustomerSelect, $trackingcode);;

        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/salidas?message=' . urlencode($message));


    }

}