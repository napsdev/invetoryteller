<?php
function formatThousands($number)
{
    return (intval($number) == $number) ? number_format($number, 0, '', ',') : number_format($number, 2, '.', ',');
}

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORDOBA ADMIN</title>
    <link rel="icon" type="image/x-icon" href="<?= $_ENV['BASE_URL_PATH'] ?>/public/img/onepiece.ico">
    <link href="<?= $_ENV['BASE_URL_PATH'] ?>/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $_ENV['BASE_URL_PATH'] ?>/public/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $_ENV['BASE_URL_PATH'] ?>/public/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="<?= $_ENV['BASE_URL_PATH'] ?>/public/css/styles.css">
</head>
