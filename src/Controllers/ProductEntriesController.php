<?php
namespace App\Controllers;

class ProductEntriesController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/productentries.php';
    }
}