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


            <div class="d-flex justify-content-center align-items-center mt-5 mb-5">
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


<?php if (!empty($table)): ?>
<div class="mb-3">
        <table id="paymentsmethods" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Valor añadido</th>
                <th>Porcentaje</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($table as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['value_added']) ?></td>
                    <td><?= htmlspecialchars($row['percentage']) ?></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Acciones">
                            <button type="button" class="btn btn-danger" onclick="deletePayment(<?=$row['id']?>)">Eliminar</button>
                            <button type="button" class="btn btn-primary" onclick="editPaymentMethod(<?= htmlspecialchars(json_encode($row)) ?>)">Editar</button>
                        </div>

                        <form id="delete-form" action="<?= $_ENV['BASE_URL_PATH'].'/pagos/delete' ?>" method="POST" style="display:none;">
                            <input type="hidden" name="id" value="<?=$row['id']?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
</div>
<?php endif; ?>

</div>
<?php include 'footer.php'; ?>
<script>
    new DataTable('#paymentsmethods', {
        scrollX: true,
        scrollY: "300px",
        language: {
            url: "../../public/js/dataTableEs.json"
        }
    });

        function deletePayment(id) {
            const form = document.getElementById('delete-form');
            form.querySelector('[name="id"]').value = id;
            form.submit();
        }

        function editPaymentMethod(paymentMethod) {

            document.getElementById('name').value = paymentMethod.name || '';
            document.getElementById('description').value = paymentMethod.description || '';
            document.getElementById('value_added').value = paymentMethod.value_added || 0;
            document.getElementById('percentage').value = paymentMethod.percentage || 0;

            const form = document.querySelector('form[action*="pagos/create"]');
            form.action = "<?= $_ENV['BASE_URL_PATH'] ?>/pagos/update";

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
            hiddenIdField.value = paymentMethod.id;
    }
</script>
</body>
</html>
