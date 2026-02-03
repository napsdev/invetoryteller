<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
    
<div class="row">
    <div class="col-12">
        <h1 class="text-center">INFORMACIÓN DEL NEGOCIO <?= $_GET['year'] ?? date('Y') ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">EN ESPERA</h3>
            <div style="max-height: 300px; overflow-y: auto;">
            <?php if (!empty($table)): ?>
                <?php foreach ($table as $row): ?>
                    <?php if ($row['pending_call'] == 1): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['customername']) ?></h5>                            
                                <p class="card-text"><?= htmlspecialchars($row['date']) ?></p>
                                <p class="card-text">Cod. <?= htmlspecialchars($row['trackingcode']) ?></p>
                            
                                <div class="btn-group" role="group" aria-label="Acciones" id="actionsinvoice">
                                    <form action="<?= $_ENV['BASE_URL_PATH']?>/pdf" method="post" target="_blank">
                                        <input type="text" name="id" value="<?= $row['id'] ?>" hidden>
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
    </div>

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">AGOTADOS</h3>
            <div style="max-height: 300px; overflow-y: auto;">
            <?php if (!empty($tableProducts)): ?>
                <?php foreach ($tableProducts as $row): ?>
                    <?php if ($row['amount'] < $_ENV['QUANTITY_OF_PRODUCTS']): ?>
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
</div>


<!-- Charts -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card p-4 shadow-sm" style="height: 400px;">
            <h3 class="text-center mb-4">GANANCIAS (-GASTOS)
            <?php
            if (!empty($chartrevenue)) {
                $totalRevenue = 0; 
                foreach ($chartrevenue as $row) {
                    $totalRevenue += $row['net_income']; 
                }
                echo formatThousands($totalRevenue); 
            }
            ?>
            </h3>
            <p class="text-center mb-4">GANANCIAS ULTIMO DÍA REGISTRADO:
                <?php
                if (!empty($lastDayRevenue)) {
                echo $lastDayRevenue['last_day'].' | '.formatThousands($lastDayRevenue['net_income']);
                } else {
                echo "No disponible";
                }
                ?>
            </p>
            <div style="height: 100%;">
                <canvas id="revenue"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card p-4 shadow-sm" style="height: 400px;">
        <h3 class="text-center mb-4">VENTAS 
            <?php
            if (!empty($chart)) {
                $totalRevenue = 0; 
                foreach ($chart as $row) {
                    $totalRevenue += $row['total_neto']; 
                }
                echo formatThousands($totalRevenue); 
            }
            ?>
            </h3>
            <p class="text-center mb-4">VENTAS ULTIMO DÍA REGISTRADO:
                <?php
                if (!empty($lastdaycharts)) {
                    echo $lastdaycharts['last_day'].' | '.formatThousands($lastdaycharts['total_neto']);
                } else {
                    echo "No disponible";
                }
                ?>
            </p>
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
            labels: [
            <?php if (!empty($chartrevenue)): ?>
            <?php $count = count($chartrevenue); ?>
            <?php $i = 0; ?>
            <?php foreach ($chartrevenue as $row): ?>
                '<?= htmlspecialchars($row['month']) ?>'<?= (++$i < $count) ? ',' : ''; ?> 
            <?php endforeach; ?>
            <?php endif; ?>
                ],
            datasets: [{
                label: 'Ventas',
                data: [
                    <?php if (!empty($chartrevenue)): ?>
                    <?php $count = count($chartrevenue); ?>
                    <?php $i = 0; ?>
                    <?php foreach ($chartrevenue as $row): ?>
                        '<?= htmlspecialchars($row['net_income']) ?>'<?= (++$i < $count) ? ',' : ''; ?> 
                    <?php endforeach; ?>
                    <?php endif; ?>
                    ],
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
            labels: [
            <?php if (!empty($chart)): ?>
            <?php $count = count($chart); ?>
            <?php $i = 0; ?>
            <?php foreach ($chart as $row): ?>
                '<?= htmlspecialchars($row['mes']) ?>'<?= (++$i < $count) ? ',' : ''; ?> 
            <?php endforeach; ?>
            <?php endif; ?>
                ],
            datasets: [{
                label: 'Ventas',
                data: [
                    <?php if (!empty($chart)): ?>
                    <?php $count = count($chart); ?>
                    <?php $i = 0; ?>
                    <?php foreach ($chart as $row): ?>
                        '<?= htmlspecialchars($row['total_neto']) ?>'<?= (++$i < $count) ? ',' : ''; ?> 
                    <?php endforeach; ?>
                    <?php endif; ?>
                    ],
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

<div class="row mb-3 mt-3">
    <div class="col-12">
        <form action="<?= $_ENV['BASE_URL_PATH']?>/table" method="post" target="_blank">
            <div class="form-group">
                <label for="search">GANANCIA Y VENTA POR DÍA (SIN CONTAR GASTOS)</label>
                <input type="date" class="form-control" id="search" name="search" required="required">
                <button type="submit" class="btn btn-success mt-3">BUSCAR</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-3 mt-3">
    <div class="col-12">
        <form action="<?= $_ENV['BASE_URL_PATH']?>/table?year=<?= $_GET['year'] ?>" method="post" target="_blank">
            <div class="form-group">
                <button type="submit" class="btn btn-primary mt-3">UBICACIÓN DEL DINERO</button>
            </div>
        </form>
    </div>
</div>

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
                    <form action="<?= $_ENV['BASE_URL_PATH']?>/pdf" method="post" target="_blank">
                        <input type="text" name="id" value="<?= $row['id'] ?>" hidden>
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
                    <form action="<?= $_ENV['BASE_URL_PATH']?>/pdf/<?= $row['id'] ?>" method="get" target="_blank">
                                        <button type="submit" class="btn btn-primary">ENV</button>
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
