<?php
namespace App\Models;
use App\Models\Database;
use PDO;
use PDOException;

class ProductsModel
{
    public function list() {
        $dbInstance = new Database();
        $db = $dbInstance->getConnection();

        try {
            $query = "SELECT id,name,purchase_price,sales_price,revenue,amount FROM products";
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }
    public function create($name,$purchase_price,$sales_price,$amount)
    {
        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }

        if (!is_numeric($purchase_price) || $purchase_price < 0) {
            $message = "El precio de compra debe ser un número entero positivo.";
        }

        if (!is_numeric($sales_price) || $sales_price < 0) {
            $message = "El precio de venta debe ser un número entero positivo.";
        }

        if (!is_numeric($amount) || $amount < 0) {
            $message = "La cantidad debe ser un número entero positivo.";
        }

        if (!empty($message)){
            return $message;
        }else{
            try {
                $dbInstance = new Database();
                $db = $dbInstance->getConnection();

                $stmt = $db->prepare("INSERT INTO products (name,purchase_price,sales_price,amount) VALUES (:name,:purchase_price,:sales_price,:amount)");
                $stmt->execute([
                    'name' => $name,
                    'purchase_price' => (int)$purchase_price,
                    'sales_price' => (int)$sales_price,
                    'amount' => (int)$amount
                ]);

                return "Creado con exito: ".$name;

            } catch (PDOException $e) {
                error_log("Error al crear: " . $e->getMessage());
                return $e->getMessage();
            }

        }

    }

}