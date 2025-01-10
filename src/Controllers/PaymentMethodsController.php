<?php
namespace App\Controllers;
use App\Models\PaymentMethodsModel;

class PaymentMethodsController
{
    private $PaymentMethodsModel;
    public function __construct(){
        $this->PaymentMethodsModel = new PaymentMethodsModel();
    }
    public function index()
    {
        $table = $this->PaymentMethodsModel->list();
        require_once __DIR__ . '/../Views/paymentmethods.php';
    }
    public function update()
    {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $value_added = $_POST['value_added'] ?? 0;
        $percentage = $_POST['percentage'] ?? 0;
        if ($id) {
            $message = $this->PaymentMethodsModel->update($id, $name, $description, $value_added, $percentage);
        } else {
            $message = 'ID no proporcionado.';
        }
        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/pagos?message=' . urlencode($message));
    }
    public function create()
    {
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $value_added = $_POST['value_added'] ?? null;
        $percentage = $_POST['percentage'] ?? null;
        $message = $this->PaymentMethodsModel->create($name,$description,$value_added,$percentage);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/pagos?message='.urlencode($message));
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $message = $this->PaymentMethodsModel->delete($id);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/pagos?message='.urlencode($message));
    }

}