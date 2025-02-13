<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Registrar cliente</h3>
        <form action="<?= $_ENV['BASE_URL_PATH']?>/clientes/create" method="post">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Telefono</label>
                <input type="text" class="form-control" id="phone" name="phone" required value="N/A">
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" class="form-control" id="address" name="address" required value="N/A">
            </div>
            <div class="form-group">
                <label for="document">Documento</label>
                <input type="text" class="form-control" id="document" name="document" required value="N/A">
            </div>
            <div class="form-group">
                <label for="contact">Correo</label>
                <input type="text" class="form-control" id="contact" name="contact" required value="">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>
</div>
<?php if (!empty($table)): ?>
    <div class="mb-3">
        <table id="customers" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Dirección</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($table as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['document']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" onclick="deleteClient(<?=$row['id']?>)">Eliminar</button>
                            <button type="button" class="btn btn-primary" onclick="editClient(<?= htmlspecialchars(json_encode($row)) ?>)">Editar</button>
                        </div>

                        <form id="delete-form" action="<?= $_ENV['BASE_URL_PATH'].'/clientes/delete' ?>" method="POST" style="display:none;">
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
<!-- Buttons table -->
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/dataTables.buttons.min.js"></script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/jszip.min.js"></script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/buttons.html5.min.js"></script>
<script>
    new DataTable('#customers', {
        order: [],
        scrollX: true,
        scrollY: "300px",
        language: {
            url: "<?= $_ENV['BASE_URL_PATH']?>/public/js/dataTableEs.json"
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

    function deleteClient(id) {
        const form = document.getElementById('delete-form');
        form.querySelector('[name="id"]').value = id;
        form.submit();
    }

    function editClient(Client) {

        document.getElementById('name').value = Client.name || '';
        document.getElementById('phone').value = Client.phone || '';
        document.getElementById('address').value = Client.address || '';
        document.getElementById('document').value = Client.document || '';
        document.getElementById('contact').value = Client.contact || '';

        const form = document.querySelector('form[action*="clientes/"]');
        form.action = "<?= $_ENV['BASE_URL_PATH'] ?>/clientes/update";

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
        hiddenIdField.value = Client.id;
    }
</script>
</div>
</body>
</html>
