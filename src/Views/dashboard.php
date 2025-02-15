<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
    
<div class="row">
    <div class="col-12">
        <h1 class="text-center">INFORMACIÓN DEL NEGOCIO</h1>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">EN ESPERA</h3>
            <?php if (!empty($table)): ?>
                <?php foreach ($table as $row): ?>
                    <?php if ($row['pending_call'] == 1): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['customername']) ?></h5>                            
                                <p class="card-text"><?= htmlspecialchars($row['date']) ?></p>
                                <p class="card-text">Cod. <?= htmlspecialchars($row['trackingcode']) ?></p>
                            
                                <div class="btn-group" role="group" aria-label="Acciones" id="actionsinvoice">
                                    <form action="<?= $_ENV['BASE_URL_PATH']?>/pdf/<?= $row['id'] ?>" method="get" target="_blank">
                                        <button type="submit" class="btn btn-primary">Ver</button>
                                    </form>
                                    <form action="<?= $_ENV['BASE_URL_PATH']?>/salidas/anular" method="post">
                                        <input type="text" name="id" value="<?= $row['id'] ?>" hidden>
                                        <button type="submit" class="btn btn-danger">AN</button>
                                    </form>
                                    <form action="<?= $_ENV['BASE_URL_PATH']?>/salidas/aprobar" method="post">
                                        <input type="text" name="id" value="<?= $row['id'] ?>" hidden>
                                        <button type="submit" class="btn btn-success">AP</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">AGOTADOS</h3>
            <?php if (!empty($tableProducts)): ?>
                <?php foreach ($tableProducts as $row): ?>
                    <?php if ($row['amount'] < 1): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['amount']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- Charts -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card p-4 shadow-sm" style="height: 400px;">
            <h3 class="text-center mb-4">GANANCIAS</h3>
            <div style="height: 100%;">
                <canvas id="revenue"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card p-4 shadow-sm" style="height: 400px;">
            <h3 class="text-center mb-4">VENTAS</h3>
            <div style="height: 100%;">
                <canvas id="total"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('revenue').getContext('2d');
    var revenue = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Ventas',
                data: [0, 19, 3, 5, 2, 3, 20, 10, 15, 5, 10, 15],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('total').getContext('2d');
    var total = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Ventas',
                data: [0, 19, 3, 5, 2, 3, 20, 10, 15, 5, 10, 15],
                backgroundColor: 'rgba(54, 235, 126, 0.2)',
                borderColor: 'rgb(0, 255, 47)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
<!-- Charts end -->

<?php if (!empty($table)): ?>
<div class="mb-3 mt-3">
    <h2 class="text-center">FACTURAS</h2>
    <table id="invoices" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Ganancia</th>
            <th>Fecha</th>
            <th>F.pago</th>
            <th>Seguimiento</th>
            <th>Estado</th>
            <th>Pendiente</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($table as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['customername']) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['total'])) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['revenue'])) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['paymentmethodname']) ?></td>
                <td><?= htmlspecialchars($row['trackingcode']) ?></td>
                <td><?= htmlspecialchars(($row['status'] == 1) ? "OK" : (($row['status'] == 3) ? "ANULADA" : "ESPERA")) ?></td>
                <td><?= htmlspecialchars(($row['pending_call']) == 2 ? "N/A" : "PENDIENTE") ?></td>
                <td>
                <div class="btn-group" role="group" aria-label="Acciones" id="actionsinvoice">
                    <form action="<?= $_ENV['BASE_URL_PATH']?>/pdf/<?= $row['id'] ?>" method="get" target="_blank">
                        <button type="submit" class="btn btn-primary">Ver</button>
                    </form>
                    <form action="<?= $_ENV['BASE_URL_PATH']?>/salidas/anular" method="post">
                        <input type="text" name="id" value="<?= $row['id'] ?>" hidden>
                        <button type="submit" class="btn btn-danger">AN</button>
                    </form>
                    <form action="<?= $_ENV['BASE_URL_PATH']?>/salidas/aprobar" method="post">
                        <input type="text" name="id" value="<?= $row['id'] ?>" hidden>
                        <button type="submit" class="btn btn-success">AP</button>
                    </form>
                </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>

<!-- Buttons table -->
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/dataTables.buttons.min.js"></script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/jszip.min.js"></script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/buttons.html5.min.js"></script>
<script>
    new DataTable('#invoices', {
    order: [],
    scrollX: true,
    scrollY: "500px",
    language: {
        url: "<?= $_ENV['BASE_URL_PATH']?>/public/js/dataTableEs.json",
    },
    dom: 'Blftip',
    buttons: [
            {
                extend: 'copy',
                text: 'Copiar',
                className: 'btn btn-outline-primary'
            },
            { 
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-outline-success'
            }
        ]
    });
</script>

</div>
</body>
</html>
