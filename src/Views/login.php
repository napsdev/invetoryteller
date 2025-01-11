<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORDOBA ADMIN</title>
    <!-- Bootstrap CSS -->
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php if (!empty($error)): ?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto mt-3" style="max-width: 500px;" role="alert">
        <?= $error ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Iniciar sesión</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">USUARIO</label>
                <input type="text" class="form-control" id="username"  name="username">
            </div>
            <div class="form-group">
                <label for="password">CONTRASEÑA</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Enviar</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
