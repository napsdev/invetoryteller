<?php
namespace App\Models;
use App\Models\Database;
use PDO;
use PDOException;



class PaymentMethodsModel
{
    public function update($id, $name, $description, $value_added, $percentage)
    {
        $dbInstance = new Database();
        $db = $dbInstance->getConnection();

        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }

        if (!is_numeric($value_added) || $value_added < 0) {
            $message = "El valor agregado debe ser un número entero positivo.";
        }

        if (!is_numeric($percentage) || $percentage < 0 || $percentage > 100) {
            $message = "El porcentaje debe ser un número entre 0 y 100.";
        }

        if (!empty($message)){
            return $message;
        }else{
            try {
                $stmt = $db->prepare(
                    "UPDATE paymentmethods 
             SET name = :name, description = :description, value_added = :value_added, percentage = :percentage
             WHERE id = :id"
                );
                $stmt->execute([
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'value_added' => $value_added,
                    'percentage' => $percentage
                ]);

                return 'Actualizado correctamente.';
            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                return false;
            }
        }


    }
    public function list() {
        $dbInstance = new Database();
        $db = $dbInstance->getConnection();

        try {
            $query = "SELECT id, name, description, value_added, percentage FROM paymentmethods";
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }

    public function create($name,$description,$value_added,$percentage)
    {
        $message = "";
        $name = trim($name);
        if (empty($name)) {
            $message = "El nombre es obligatorio.";
        }

        if (!is_numeric($value_added) || $value_added < 0) {
            $message = "El valor agregado debe ser un número entero positivo.";
        }

        if (!is_numeric($percentage) || $percentage < 0 || $percentage > 100) {
            $message = "El porcentaje debe ser un número entre 0 y 100.";
        }

        if (!empty($message)){
            return $message;
        }else{
            try {
                $dbInstance = new Database();
                $db = $dbInstance->getConnection();

                $stmt = $db->prepare("INSERT INTO paymentmethods (name, description,value_added,percentage) VALUES (:name,:description,:value_added,:percentage)");
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'value_added' => (int)$value_added,
                    'percentage' => (float)$percentage
                ]);

                return "Creado con exito: ".$name;

            } catch (PDOException $e) {
                error_log("Error al crear: " . $e->getMessage());
                return $e->getMessage();
            }

        }

    }

    public function delete($id) {
        $dbInstance = new Database();
        $db = $dbInstance->getConnection();

        try {
            $stmt = $db->prepare("DELETE FROM paymentmethods WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            return 'Eliminado con exito';

        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}
