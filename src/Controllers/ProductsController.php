<?php
namespace App\Controllers;

class ProductsController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/products.php';
    }
}