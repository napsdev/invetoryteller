<?php
namespace App\Models;
use App\Models\Database;
use PDO;
use PDOException;

class SuppliersModel
{
    private $db;

    public function __construct()
    {
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }

    public function update($id, $name,$contact,$phone,$address)
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
                    "UPDATE suppliers SET name = :name, contact = :contact, phone = :phone, address = :address WHERE id = :id"
                );
                $stmt->execute([
                    'id' => $id,
                    'name' => $name,
                    'contact' => $contact,
                    'phone' => $phone,
                    'address' => $address
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
            $query = "SELECT id,name,contact,phone,address FROM suppliers";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function create($name,$contact,$phone,$address)
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
                $stmt = $this->db->prepare("INSERT INTO suppliers (name,contact,phone,address) VALUES (:name,:contact,:phone,:address)");
                $stmt->execute([
                    'name' => $name,
                    'contact' => $contact,
                    'phone' => $phone,
                    'address' => $address
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
            $stmt = $this->db->prepare("DELETE FROM suppliers WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            return 'Eliminado con exito';
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta o exietncia de una relación con este proveedor";
        }
    }
}