<?php
namespace App\Controllers;
use App\Models\SuppliersModel;


class SuppliersController
{
    private $SuppliersModel;
    public function __construct(){
        $this->SuppliersModel = new SuppliersModel();
    }
    public function index()
    {
        $table = $this->SuppliersModel->list();
        require_once __DIR__ . '/../Views/suppliers.php';
    }

    public function create()
    {
        $name = $_POST['name'] ?? null;
        $contact = $_POST['contact'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;

        $message = $this->SuppliersModel->create($name,$contact,$phone,$address);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/proveedores?message='.urlencode($message));
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $message = $this->SuppliersModel->delete($id);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/proveedores?message='.urlencode($message));
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        $contact = $_POST['contact'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;

        if ($id) {
            $message = $this->SuppliersModel->update($id, $name,$contact,$phone,$address);
        } else {
            $message = 'ID no proporcionado.';
        }
        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/proveedores?message=' . urlencode($message));

    }

}