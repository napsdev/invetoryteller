<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Ingreso cantidad de productos</h3>
        <form action="<?= $_ENV['BASE_URL_PATH']?>entradas/create" method="post">
            <div class="form-group">
                <label for="product_id">Producto:</label>
                <select class="selectpicker" data-live-search="true" id="product_id" name="product_id">
                    <?php if (!empty($listProducts)): ?>
                                <?php foreach ($listProducts as $row): ?>
                                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name'])?></option>
                                <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Cantidad</label>
                <input type="number" class="form-control" id="amount" name="amount" value="0" required min="0">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>
</div>
<?php if (!empty($table)): ?>
    <div class="mb-3">
        <table id="productentries" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Monto Total Producto</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($table as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars(formatThousands($row['amount'])) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars(formatThousands($row['totalamount'])) ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" onclick="deleteProductentries(<?=$row['id']?>)">Eliminar</button>
                        </div>

                        <form id="delete-form" action="<?= $_ENV['BASE_URL_PATH'].'/entradas/delete' ?>" method="POST" style="display:none;">
                            <input type="hidden" name="id" value="<?=$row['id']?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php include 'footer.php'; ?>
<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });

    new DataTable('#productentries', {
        scrollX: true,
        scrollY: "300px",
        language: {
            url: "../../public/js/dataTableEs.json"
        }
    });

    function deleteProductentries(id) {
        const form = document.getElementById('delete-form');
        form.querySelector('[name="id"]').value = id;
        form.submit();
    }
</script>
</div>
</body>
</html>
