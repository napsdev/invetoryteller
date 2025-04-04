<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Registrar gasto</h3>
        <form action="<?= $_ENV['BASE_URL_PATH']?>/gastos/create" method="post">
            <div class="form-group">
                <label for="description">Descripción</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="form-group">
                <label for="amount">Valor</label>
                <input type="number" class="form-control" id="amount" name="amount" required value="0">
            </div>
            <div class="form-group">
                <label for="date">Fecha</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>
</div>
<?php if (!empty($table)): ?>
    <div class="mb-3">
        <table id="expenses" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Descripción</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($table as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td id="ThousandsSeparator"><?= htmlspecialchars(formatThousands($row['amount'])) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" onclick="deleteExpenses(<?=$row['id']?>)">Eliminar</button>
                            <button type="button" class="btn btn-primary" onclick="editExpenses(<?= htmlspecialchars(json_encode($row)) ?>)">Editar</button>
                        </div>
                        <form id="delete-form" action="<?= $_ENV['BASE_URL_PATH'].'/gastos/delete' ?>" method="POST" style="display:none;">
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
    new DataTable('#expenses', {
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

    function deleteExpenses(id) {
        const form = document.getElementById('delete-form');
        form.querySelector('[name="id"]').value = id;
        form.submit();
    }

    function editExpenses(Expense) {
        document.getElementById('description').value = Expense.description || '';
        document.getElementById('amount').value = Expense.amount || '';

        if (Expense.date) {
            let date = new Date(Expense.date);
            if (!isNaN(date.getTime())) {
                let formattedDate = date.toISOString().split('T')[0];
                document.getElementById('date').value = formattedDate;
            }
        }

        const form = document.querySelector('form[action*="gastos/"]');
        form.action = "<?= $_ENV['BASE_URL_PATH'] ?>/gastos/update";

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
        hiddenIdField.value = Expense.id;
    }

</script>
</div>
</body>
</html>
