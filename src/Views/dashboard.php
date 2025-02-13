<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
    
<?php if (!empty($table)): ?>
<div class="mb-3">
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
