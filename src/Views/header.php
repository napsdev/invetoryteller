<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
    exit;
}

function formatThousands($number)
{
    return number_format($number, 2, '.', ',');
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORDOBA ADMIN</title>
    <link rel="icon" type="image/x-icon" href="../../public/img/onepiece.ico">
    <!-- Bootstrap CSS -->
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Datatables CSS -->
    <link href="../../public/css/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="../../public/css/bootstrap-select.min.css">
</head>
