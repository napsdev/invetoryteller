<?php
namespace App\Controllers;
use App\Models\UserModel;

class LoginController
{
    public function login()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($username) || empty($password)) {
                $error = 'Usuario y contraseña son requeridos.';
            } else {
                try {
                    $user = new UserModel();
                    $userAUTH = $user->getUser($username);

                    if ($userAUTH && password_verify($password, $userAUTH['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $userAUTH['id'];
                        $_SESSION['username'] = $userAUTH['username'];

                        header('Location: '.$_ENV['BASE_URL_PATH'].'/salidas');
                        exit;
                    } else {
                        $error = 'Usuario o contraseña incorrectos.';
                    }
                } catch (\Exception $e) {
                    $error = 'Hubo un problema al procesar tu solicitud.';
                    error_log($e->getMessage());
                }
            }
        }

        require_once __DIR__ . '/../Views/login.php';
    }


    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
        exit;
    }

    public function syncUserFromEnv()
    {
        $username = $_ENV['ADMIN_USERNAME'] ?? null;
        $password = $_ENV['ADMIN_PASSWORD'] ?? null;

        if ($username && $password) {
            $userModel = new UserModel();
            $userModel->createOrUpdateUser($username, $password);
        }
    }
}