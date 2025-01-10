<?php
namespace App\Controllers;
use App\Models\CustomerModel;

class CustomerController
{
    private $CustomerModel;
    public function __construct(){
        $this->CustomerModel = new CustomerModel();
    }
    public function index()
    {
        $table = $this->CustomerModel->list();
        require_once __DIR__ . '/../Views/customers.php';
    }

    public function create()
    {
        $name = $_POST['name'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;
        $document = $_POST['document'] ?? null;
        $contact = $_POST['contact'] ?? null;

        $message = $this->CustomerModel->create($name,$phone,$address,$document,$contact);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/clientes?message='.urlencode($message));
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $message = $this->CustomerModel->delete($id);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/clientes?message='.urlencode($message));
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;
        $document = $_POST['document'] ?? null;
        $contact = $_POST['contact'] ?? null;

        if ($id) {
            $message = $this->CustomerModel->update($id, $name, $phone, $address, $document, $contact);
        } else {
            $message = 'ID no proporcionado.';
        }
        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/clientes?message=' . urlencode($message));

    }
}