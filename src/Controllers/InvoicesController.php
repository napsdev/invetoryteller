<?php
namespace App\Controllers;

class InvoicesController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/invoices.php';
    }
}