<?php
namespace App\Controllers;

class CustomerController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/customers.php';
    }
}