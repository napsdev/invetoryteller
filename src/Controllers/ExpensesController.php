<?php
namespace App\Controllers;
use App\Models\ExpensesModel;


class ExpensesController
{
    private $ExpensesModel;
    public function __construct(){
        $this->ExpensesModel = new ExpensesModel();
    }
    public function index()
    {
        $table = $this->ExpensesModel->list();
        require_once __DIR__ . '/../Views/expenses.php';
    }

    public function create()
    {
        $description = $_POST['description'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $date = $_POST['date'] ?? null;

        $message = $this->ExpensesModel->create($description, $amount, $date);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/gastos?message='.urlencode($message));
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $message = $this->ExpensesModel->delete($id);
        header('Location: '.$_ENV['BASE_URL_PATH'].'/gastos?message='.urlencode($message));
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $description = $_POST['description'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $date = $_POST['date'] ?? null;

        if ($id) {
            $message = $this->ExpensesModel->update($id, $description, $amount, $date);
        } else {
            $message = 'ID no proporcionado.';
        }
        header('Location: ' . $_ENV['BASE_URL_PATH'] . '/gastos?message=' . urlencode($message));
    }


}