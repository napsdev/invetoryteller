<?php
namespace App\Controllers;
use App\Models\InvoicesModel;

class DashboardController
{
    private $InvoicesModel;
    public function __construct(){
        $this->InvoicesModel = new InvoicesModel();
    }

    public function index()
    {
        $table = $this->InvoicesModel->list();
        require_once __DIR__ . '/../Views/dashboard.php';
    }
}