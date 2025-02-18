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
    public function update($id,$name,$purchase_price,$sales_price,$amount,$barcod,$cartridge,$cartridgevalue)
    {
        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }
        if (empty($barcod)) {
            $message = "El Cod. Barras es obligatorio.";
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
        if (!is_numeric($cartridgevalue) || $cartridgevalue < 0) {
            $message = "El valor del cartucho debe ser un número entero positivo.";
        }
        if (isset($cartridge)){
            $cartridge = 1;
        }else{
            $cartridge = 2;
        }

        if (!empty($message)) {
            return $message;
        } else {
            try {
                $stmt = $this->db->prepare("UPDATE products SET name=:name,purchase_price=:purchase_price,sales_price=:sales_price,amount=:amount,amount=:amount,barcod=:barcod,cartridge=:cartridge,cartridgevalue=:cartridgevalue WHERE id=:id");
                $stmt->execute([
                    'id' => $id,
                    'name' => $name,
                    'purchase_price' => (float)$purchase_price,
                    'sales_price' => (float)$sales_price,
                    'amount' => (int)$amount,
                    'barcod' =>$barcod,
                    'cartridge' => $cartridge,
                    'cartridgevalue' => (int)$cartridgevalue
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
            $query = "SELECT id,name,purchase_price,sales_price,revenue,amount,barcod,cartridge,cartridgevalue FROM products order by id desc";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }
    public function create($name,$purchase_price,$sales_price,$amount,$barcod,$cartridge,$cartridgevalue)
    {
        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }
        if (empty($barcod)) {
            $message = "El Cod. Barras es obligatorio.";
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
        if (!is_numeric($cartridgevalue) || $cartridgevalue < 0) {
            $message = "El valor del cartucho debe ser un número entero positivo.";
        }
        if (isset($cartridge)){
            $cartridge = 1;
        }else{
            $cartridge = 2;
        }
        if (!empty($message)){
            return $message;
        }else{
            try {
                $stmt = $this->db->prepare("INSERT INTO products (name,purchase_price,sales_price,amount,barcod,cartridge,cartridgevalue) VALUES (:name,:purchase_price,:sales_price,:amount,:barcod,:cartridge,:cartridgevalue)");
                $stmt->execute([
                    'name' => $name,
                    'purchase_price' => (int)$purchase_price,
                    'sales_price' => (int)$sales_price,
                    'amount' => (int)$amount,
                    'barcod' =>$barcod,
                    'cartridge' => $cartridge,
                    'cartridgevalue' => (int)$cartridgevalue
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
            //could be optimized with ON CASCADE DELETE
            $stmt = $this->db->prepare("DELETE FROM productentries WHERE product_id = :id");
            $stmt->execute([
                'id' => $id
            ]);

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

    public function updateamount($id,$amount)
    {
        if (!is_numeric($id) || $id <= 0) {
            return "ID no válido.";
        }

        try {
        $product = $this->get($id);

        if (empty($product)) {
            return "Producto no encontrado con ID: ".$id;
        }else{

            $currentAmount = $product[0]['amount'];
            $newAmount = $currentAmount + $amount;

            $stmt = $this->db->prepare("UPDATE products SET amount = :amount WHERE id = :id");
            $stmt->execute([
                'amount' => $newAmount,
                'id' => $id
            ]);
            return true;
        }

        } catch (PDOException $e) {
            error_log("Error al actualziar stock: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function get($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return "ID no válido.";
        }

        try {
            $stmt = $this->db->prepare("SELECT id,name,purchase_price,sales_price,revenue,amount,cartridge,cartridgevalue FROM products WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

}