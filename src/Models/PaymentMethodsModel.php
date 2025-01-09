<?php
namespace App\Models;
use App\Models\Database;
use PDOException;


class PaymentMethodsModel
{
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
}
