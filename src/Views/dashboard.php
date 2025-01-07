<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
    exit;
}

echo "Bienvenido, " . $_SESSION['username'] . "!";
?>

<form action="<?= $_ENV['BASE_URL_PATH'] ?>/logout" method="GET">
    <button type="submit">Cerrar sesión</button>
</form>