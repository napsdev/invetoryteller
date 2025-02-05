<?php
namespace App\Models;
use App\Models\Database;
use PDO;
use PDOException;

class ExpensesModel
{
    private $db;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }

    public function update($id, $description, $amount)
    {
        $message = "";
        $description = trim($description);
        if (empty($description)) {
            $message = "La descripción es obligatoria.";
        }
        if (!is_numeric($amount) || $amount < 0) {
            $message = "El valor debe ser un número entero positivo.";
        }
        if (!empty($message)){
            return $message;
        }else{
            try {
                $stmt = $this->db->prepare(
                    "UPDATE expenses SET description = :description, amount = :amount WHERE id = :id"
                );
                $stmt->execute([
                    'id' => $id,
                    'description' => $description,
                    'amount' => $amount,
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
            $query = "SELECT id,description,amount,date FROM expenses";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function create($description,$amount)
    {
        $message = "";
        $description = trim($description);
        if (empty($description)) {
            $message = "La descripción es obligatoria.";
        }
        if (!is_numeric($amount) || $amount < 0) {
            $message = "El valor debe ser un número entero positivo.";
        }

        if (!empty($message)){
            return $message;
        }else{
            try {
                $stmt = $this->db->prepare("INSERT INTO expenses (description,amount) VALUES (:description,:amount)");
                $stmt->execute([
                    'description' => $description,
                    'amount' => $amount,
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
            $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            return 'Eliminado con exito';
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta o exietncia una relación con este gasto.";
        }
    }

}