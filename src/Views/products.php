<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Registrar productos</h3>
        <form action="<?= $_ENV['BASE_URL_PATH']?>/productos/create" method="post">
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
            <div class="form-group">
                <label for="barcod">Cod.Barras</label>
                <input type="text" class="form-control" id="barcod" name="barcod" value="NA">
            </div>
            <div class="form-group">
            <label for="cartridge">Cartucho (Check):</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" id="cartridge" name="cartridge">
                    </div>
                </div>
                <input type="number" class="form-control" id="cartridgevalue" name="cartridgevalue" value="0" required min="0">
            </div>
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
            <th>Cod. Barras</th>
            <th>Cartucho</th>
            <th>Val. Caja</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($table as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['purchase_price'])) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['sales_price'])) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['revenue'])) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['amount'])) ?></td>
                <td><?= htmlspecialchars($row['barcod']) ?></td>
                <td><?= htmlspecialchars(($row['cartridge'] == 1) ? "SI" : (($row['cartridge'] == 2) ? "NO" : "ERROR")) ?></td>
                <td><?= htmlspecialchars(formatThousands($row['cartridgevalue'])) ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Acciones">
                        <button type="button" class="btn btn-danger" onclick="deleteProduct(<?=$row['id']?>)">Eliminar</button>
                        <button type="button" class="btn btn-primary" onclick="editProducts(<?= htmlspecialchars(json_encode($row)) ?>)">Editar</button>
                    </div>

                    <form id="delete-form" action="<?= $_ENV['BASE_URL_PATH'].'/productos/delete' ?>" method="POST" style="display:none;">
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
    new DataTable('#products', {
        order: [],
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

    function deleteProduct(id) {
        const form = document.getElementById('delete-form');
        form.querySelector('[name="id"]').value = id;
        form.submit();
    }

    function editProducts(Product) {

        document.getElementById('name').value = Product.name || '';
        document.getElementById('barcod').value = Product.barcod || '';
        document.getElementById('purchase_price').value = Product.purchase_price || 0;
        document.getElementById('sales_price').value = Product.sales_price || 0;
        document.getElementById('amount').value = Product.amount || 0;

       document.getElementById('cartridge').checked = (Product.cartridge === 1);

        document.getElementById('cartridgevalue').value = Product.cartridgevalue || 0;

        const form = document.querySelector('form[action*="productos/"]');
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
