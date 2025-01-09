<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORDOBA ADMIN</title>
    <!-- Bootstrap CSS -->
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
</head>
