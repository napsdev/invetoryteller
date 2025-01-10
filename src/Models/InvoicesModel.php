<?php
namespace App\Models;
use App\Models\Database;
use App\Models\ProductsModel;
use PDO;
use PDOException;
class InvoicesModel
{
    private $db;
    private $productInstance;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
        $this->productInstance = new ProductsModel();
    }

    public function create()
    {
    }

    public function list()
    {
    }
}