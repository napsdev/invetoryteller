<?php
namespace App\Controllers;

class PaymentMethodsController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/paymentmethods.php';
    }
}