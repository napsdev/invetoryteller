<?php
namespace App\Models;
use App\Models\Database;
use PDO;
use PDOException;

class ProductsModel
{
    private $db;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }
    public function update($id,$name,$purchase_price,$sales_price,$amount)
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
        if (!empty($message)) {
            return $message;
        } else {
            try {
                $stmt = $this->db->prepare("UPDATE products SET name=:name,purchase_price=:purchase_price,sales_price=:sales_price,amount=:amount WHERE id=:id");
                $stmt->execute([
                    'id' => $id,
                    'name' => $name,
                    'purchase_price' => (float)$purchase_price,
                    'sales_price' => (float)$sales_price,
                    'amount' => (int)$amount
                ]);
                return 'Actualizado correctamente.';
            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                return "Error en la consulta";
            }
        }
    }

    public function list() {
        try {
            $query = "SELECT id,name,purchase_price,sales_price,revenue,amount FROM products";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
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
                $stmt = $this->db->prepare("INSERT INTO products (name,purchase_price,sales_price,amount) VALUES (:name,:purchase_price,:sales_price,:amount)");
                $stmt->execute([
                    'name' => $name,
                    'purchase_price' => (int)$purchase_price,
                    'sales_price' => (int)$sales_price,
                    'amount' => (int)$amount
                ]);
                return "Creado con exito: ".$name;
            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                return "Error en la consulta";
            }
        }
    }

    public function delete($id) {
        if (!is_numeric($id) || $id <= 0) {
            return "ID no válido.";
        }
        try {
            $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            return 'Eliminado con exito';
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

}