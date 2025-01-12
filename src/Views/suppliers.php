<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Registrar proveedor</h3>
        <form action="<?= $_ENV['BASE_URL_PATH']?>proveedores/create" method="post">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="contact">Contacto</label>
                <input type="text" class="form-control" id="contact" name="contact" required value="N/A">
            </div>
            <div class="form-group">
                <label for="phone">Telefono</label>
                <input type="text" class="form-control" id="phone" name="phone" required value="N/A">
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" class="form-control" id="address" name="address" required value="N/A">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>
</div>
<?php if (!empty($table)): ?>
    <div class="mb-3">
        <table id="suppliers" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Telefono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($table as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" onclick="deleteSupplier(<?=$row['id']?>)">Eliminar</button>
                            <button type="button" class="btn btn-primary" onclick="editSupplier(<?= htmlspecialchars(json_encode($row)) ?>)">Editar</button>
                        </div>

                        <form id="delete-form" action="<?= $_ENV['BASE_URL_PATH'].'/proveedores/delete' ?>" method="POST" style="display:none;">
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
    new DataTable('#suppliers', {
        scrollX: true,
        scrollY: "300px",
        language: {
            url: "../../public/js/dataTableEs.json"
        }
    });

    function deleteSupplier(id) {
        const form = document.getElementById('delete-form');
        form.querySelector('[name="id"]').value = id;
        form.submit();
    }

    function editSupplier(Supplier) {

        document.getElementById('name').value = Supplier.name || '';
        document.getElementById('contact').value = Supplier.contact || '';
        document.getElementById('phone').value = Supplier.phone || 0;
        document.getElementById('address').value = Supplier.address || 0;

        const form = document.querySelector('form[action*="proveedores/create"]');
        form.action = "<?= $_ENV['BASE_URL_PATH'] ?>/proveedores/update";

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.textContent = 'Modificar';
        submitButton.classList.remove('btn-primary');
        submitButton.classList.add('btn-warning');

        let hiddenIdField = form.querySelector('input[name="id"]');
        if (!hiddenIdField) {
            hiddenIdField = document.createElement('input');
            hiddenIdField.type = 'hidden';
            hiddenIdField.name = 'id';
            form.appendChild(hiddenIdField);
        }
        hiddenIdField.value = Supplier.id;
    }
</script>

</div>
</body>
</html>
