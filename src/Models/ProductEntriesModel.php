<?php

namespace App\Models;
use App\Models\Database;
use App\Models\ProductsModel;
use PDO;
use PDOException;

class ProductEntriesModel
{
    private $db;
    private $productInstance;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
        $this->productInstance = new ProductsModel();
    }

    public function list() {
        try {
            $query = "SELECT productentries.id,products.name,products.amount as totalamount,productentries.amount,productentries.date FROM productentries INNER join products on products.id = productentries.product_id order by productentries.id desc";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function create($product_id, $amount)
    {
        $message = "";

        if (!is_numeric($amount) || $amount < 0) {
            $message = "El valor agregado debe ser un número entero positivo.";
        }

        if (!empty($message)){
            return $message;
        }else{
            try {

                $Product = $this->productInstance->updateamount($product_id,$amount);

                if($Product){
                    $stmt = $this->db->prepare("INSERT INTO productentries (product_id,amount) VALUES (:product_id,:amount)");
                    $stmt->execute([
                        'product_id' => (int)$product_id,
                        'amount' => (float)$amount
                    ]);
                    return "Creado con exito";
                }else{
                    return "Error al actualizar producto.";
                }


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

                $ProductEntries = $this->get($id);
                $Product = $this->productInstance->updateamount($ProductEntries[0]['product_id'], -abs($ProductEntries[0]['amount']));
            if($Product) {
                $stmt = $this->db->prepare("DELETE FROM productentries WHERE id = :id");
                $stmt->execute([
                    'id' => $id
                ]);
                return 'Eliminado con exito';
            } else {
                return "Error al actualizar producto.";
            }
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    private function get($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return "ID no válido.";
        }

        try {
            $stmt = $this->db->prepare("SELECT id,product_id,amount,date FROM productentries WHERE id = :id");
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