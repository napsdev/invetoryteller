<?php
namespace App\Models;
use PDO;
use PDOException;

class Database_old
{
    private $connection;

    public function __construct()
    {
        $host = $_ENV['DB_HOST_OLD'];
        $dbname = $_ENV['DB_NAME_OLD'];
        $user = $_ENV['DB_USER_OLD'];
        $password = $_ENV['DB_PASSWORD_OLD'];

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("SET time_zone = '-05:00'");
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die("Error de conexión");
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
