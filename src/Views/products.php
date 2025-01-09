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
            <h3 class="text-center mb-4">Registrar productos</h3>
            <form action="<?= $_ENV['BASE_URL_PATH']?>productos/create" method="post">

                <div class="form-group">
                    <label for="name">Nombre del producto</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>


                <div class="form-group">
                    <label for="purchase_price">Valor de compra</label>
                    <input type="number" class="form-control" id="purchase_price" name="purchase_price" value="0" required min="0">
                </div>

                <div class="form-group">
                    <label for="sales_price">Valor de venta</label>
                    <input type="number" class="form-control" id="sales_price" name="sales_price" value="0" required min="0">
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
            <table id="products" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio de compra</th>
                    <th>Precio de venta</th>
                    <th>Ganancia</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($table as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['purchase_price']) ?></td>
                        <td><?= htmlspecialchars($row['sales_price']) ?></td>
                        <td><?= htmlspecialchars($row['revenue']) ?></td>
                        <td><?= htmlspecialchars($row['amount']) ?></td>
                        <td>
                            <form class="mb-1" action="<?= $_ENV['BASE_URL_PATH'].'/pagos/delete' ?>" method="POST">
                                <input type="hidden" name="id" value="<?=$row['id']?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                            <button type="button" class="btn btn-success" onclick="editProducts(<?= htmlspecialchars(json_encode($row)) ?>)">Editar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php include 'footer.php'; ?>
<script>
    new DataTable('#products', {
        scrollX: true,
        scrollY: "300px",
        language: {
            url: "../../public/js/dataTableEs.json",
        }
    });

    function editProducts(Product) {

        document.getElementById('name').value = Product.name || '';
        document.getElementById('purchase_price').value = Product.purchase_price || 0;
        document.getElementById('sales_price').value = Product.sales_price || 0;
        document.getElementById('amount').value = Product.amount || 0;

        const form = document.querySelector('form[action*="productos/create"]');
        form.action = "<?= $_ENV['BASE_URL_PATH'] ?>/productos/update";

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
        hiddenIdField.value = Product.id;
    }
</script>

</div>
</body>
</html>
