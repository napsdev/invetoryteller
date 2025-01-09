<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<?php if (!empty($_GET['message'])): ?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto mt-3" style="max-width: 500px;" role="alert">
        <?= $_GET['message'] ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
<div class="d-flex justify-content-center align-items-center mt-5">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Registrar formas de pago</h3>
        <form action="<?= $_ENV['BASE_URL_PATH']?>pagos/create" method="post">

            <div class="form-group">
                <label for="name">Nombre del método de pago</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>


            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>


            <div class="form-group">
                <label for="value_added">Valor agregado</label>
                <input type="number" class="form-control" id="value_added" name="value_added" value="0" required min="0">
            </div>


            <div class="form-group">
                <label for="percentage">Porcentaje</label>
                <input type="number" class="form-control" id="percentage" name="percentage" value="0.00" required step="0.01" min="0" max="100">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>
</div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
