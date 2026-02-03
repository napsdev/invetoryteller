<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php require_once 'navbar.php'; ?>
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4">VENTAS POR PRODUCTO</h3>
                <form action="" method="GET" class="form-inline mb-4 justify-content-center">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="start_date" class="mr-2">Fecha Inicio:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $startDate ?>" required>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="end_date" class="mr-2">Fecha Fin:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $endDate ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Buscar</button>
                </form>

                <?php if (empty($reportData)): ?>
                    <div class="alert alert-info text-center">No se encontraron ventas en el rango de fechas seleccionado.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table  id="productssale" class="table table-bordered table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad Vendida</th>
                                    <th class="text-right">Ingresos Totales</th>
                                    <th class="text-center">Stock Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="text-center"><?= $row['sold_amount'] ?></td>
                                        <td class="text-right">$<?= number_format($row['total_sales'], 2) ?></td>
                                        <td class="text-center font-weight-bold <?= $row['current_stock'] <= 5 ? 'text-danger' : 'text-success' ?>">
                                            <?= $row['current_stock'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>
<?php include 'footer.php'; ?>
<!-- Buttons table -->
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/dataTables.buttons.min.js"></script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/jszip.min.js"></script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/buttons.html5.min.js"></script>
<script>
    new DataTable('#productssale', {
        order: [[1, 'desc']],
        scrollX: true,
        scrollY: "300px",
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

</body>
</html>
