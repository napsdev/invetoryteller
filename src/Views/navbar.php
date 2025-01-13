<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Sr.Cordoba</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>salidas">SALIDAS</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>repsalidas">REPORTES</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>pagos">F.PAGO</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>productos">PRODUCTOS</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>entradas">ENTRADAS</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>clientes">CLIENTES</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>gastos">GASTOS</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $_ENV['BASE_URL_PATH'].'/'?>proveedores">PROVEEDORES</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="<?= $_ENV['BASE_URL_PATH'] ?>/logout">CERRAR SESIÓN</a></li>
        </ul>
    </div>
</nav>
<?php if (!empty($_GET['message'])): ?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto mt-3" style="max-width: 500px;" role="alert">
        <?= $_GET['message'] ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

