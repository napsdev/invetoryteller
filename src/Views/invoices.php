<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<form action="<?= $_ENV['BASE_URL_PATH']?>salidas/create" method="post">


<div class="d-flex justify-content-center align-items-center mt-5 mb-1" id="products">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Productos</h3>

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
                <input type="number" class="form-control" id="amount" name="amount" value="1" required min="1">
            </div>

            <button type="button" class="btn btn-success btn-block">Agregar</button>

    </div>
</div>

<div class="d-flex justify-content-center align-items-center mt-1 mb-1" id="customer_id">

    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Seleccione el cliente</h3>
        <div class="form-group">
            <label for="customer_id">Cliente:</label>
            <select class="selectpicker" data-live-search="true" id="customer_id" name="customer_id">
                <?php if (!empty($listClients)): ?>
                    <?php foreach ($listClients as $row): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name'])." / ".htmlspecialchars($row['contact'])?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="register">No registrado (Check):</label>
            <div class="input-group mb-3" id="register">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" id="newCustomer" name="newCustomer" aria-label="Checkbox para cliente nuevo">
                    </div>
                </div>
                <input type="text" class="form-control" id="newCustomername" name="newCustomername" placeholder="Nombre cliente" aria-label="Campo de texto con checkbox">
            </div>
        </div>

        <div class="form-group">
            <label for="newCustomercontact">Correo</label>
            <input type="email" class="form-control" id="newCustomercontact" name="newCustomercontact" placeholder="Correo">
        </div>

    </div>
</div>

<div class="d-flex justify-content-center align-items-center mt-1 mb-1" id="products">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Medio de pago</h3>

        <div class="form-group">
            <label for="paymentmethods_id">Producto:</label>
            <select class="selectpicker" data-live-search="true" id="paymentmethods_id" name="paymentmethods_id">
                <?php if (!empty($listPaymentsMethods)): ?>
                    <?php foreach ($listPaymentsMethods as $row): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name'])?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="pending_call" name="pending_call">
            <label class="form-check-label" for="exampleCheck1">Pendiente</label>
        </div>

    </div>
</div>

<div class="d-flex justify-content-center align-items-center mt-1 mb-1" id="products">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">VALORES</h3>
        <h3 class="text-left mb-4">ENVIO: 0</h3>
        <h3 class="text-left mb-4">TOTAL: 0</h3>
    </div>
</div>

<div class="d-flex justify-content-center align-items-center mt-1 mb-5" id="products">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">PRODUCTOS AÑADIDOS</h3>

        <button type="button" class="btn btn-dark btn-block">Cotizar</button>
        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
    </div>
</div>

</form>



<?php include 'footer.php'; ?>
<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });
</script>
</div>
</body>
</html>
