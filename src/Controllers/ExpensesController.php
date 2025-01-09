<?php
namespace App\Controllers;

class ExpensesController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/expenses.php';
    }
}