<?php
namespace App\Controllers;
use App\Models\PaymentMethodsModel;

class PaymentMethodsController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/paymentmethods.php';
    }
    public function create()
    {
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $value_added = $_POST['value_added'] ?? null;
        $percentage = $_POST['percentage'] ?? null;

        $userModel = new PaymentMethodsModel();
        $message = $userModel->create($name,$description,$value_added,$percentage);

        header('Location: '.$_ENV['BASE_URL_PATH'].'/pagos?message='.urlencode($message));

    }

}