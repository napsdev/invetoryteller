<?php
namespace App\Models;
use App\Models\Database;
use PDO;
use PDOException;

class CustomerModel
{
    private $db;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }

    public function update($id, $name, $phone, $address, $document, $contact)
    {
        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }
        if (!empty($message)){
            return $message;
        }else{
            try {
                $stmt = $this->db->prepare(
                    "UPDATE customers SET name = :name, phone = :phone, address = :address, document = :document, contact = :contact WHERE id = :id"
                );
                $stmt->execute([
                    'id' => $id,
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'document' => $document,
                    'contact' => $contact
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
            $query = "SELECT id,name,phone,address,document,contact FROM customers";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function create($name,$phone,$address,$document,$contact)
    {
        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }

        if (!empty($message)){
            return $message;
        }else{
            try {
                $stmt = $this->db->prepare("INSERT INTO customers (name,phone,address,document,contact) VALUES (:name,:phone,:address,:document,:contact)");
                $stmt->execute([
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'document' => $document,
                    'contact' => $contact
                ]);
                return $this->db->lastInsertId();
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
            $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            return 'Eliminado con exito';
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta o existe una factura asociada a este cliente.";
        }
    }

    public function get($id) {
        if (!is_numeric($id) || $id <= 0) {
            return "ID no válido.";
        }
        try {
            $stmt = $this->db->prepare("SELECT id, name, phone, address, document, contact FROM customers WHERE id = :id");
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