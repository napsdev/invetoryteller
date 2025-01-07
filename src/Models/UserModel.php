<?php
namespace App\Models;
use App\Models\Database;
use InvalidArgumentException;
use PDO;
use PDOException;


class UserModel
{
    public function getUser($username)
    {
        if (empty($username)) {
            throw new InvalidArgumentException("El nombre de usuario no puede estar vacío.");
        }
            $dbInstance = new Database();
            $db = $dbInstance->getConnection();

            $stmt = $db->prepare("SELECT id, username, password FROM user WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                return $user;
            }

            return null;
    }


    public function createOrUpdateUser($username, $password)
    {
        try {
            $dbInstance = new Database();
            $db = $dbInstance->getConnection();

            $user = $this->getUser($username);

            if ($user) {

                if (!password_verify($password, $user['password'])) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE user SET password = :password WHERE username = :username");
                    $stmt->execute([
                        'password' => $hashedPassword,
                        'username' => $username,
                    ]);
                }
            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
                $stmt->execute([
                    'username' => $username,
                    'password' => $hashedPassword,
                ]);
            }
        } catch (PDOException $e) {
            error_log("Error al crear o actualizar el usuario: " . $e->getMessage());
        }
    }
}
