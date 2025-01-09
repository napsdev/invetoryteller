<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Registrar formas de pago</h3>
        <form action="#" method="post">
            <div class="form-group">
                <label for="ejemplo1">EJEMPLO 1</label>
                <input type="email" class="form-control" id="ejemplo1" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="ejemplo2">EJEMPLO 2</label>
                <input type="password" class="form-control" id="ejemplo2">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="ejemplo3">
                <label class="form-check-label" for="ejemplo3">EJEMPLO 3</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Enviar</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
