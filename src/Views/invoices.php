<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<form action="<?= $_ENV['BASE_URL_PATH']?>/salidas/create" method="post" id="productForm">
<div class="container mt-5 mb-1" id="products">
<div class="row justify-content-center">

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Productos</h3>
            <div class="form-group">
                <select class="selectpicker" data-live-search="true" id="product_id" name="product_id">
                    <?php if (!empty($listProducts)): ?>
                        <?php foreach ($listProducts as $row): ?>
                            <option value="<?= htmlspecialchars($row['id']) ?>" data-price="<?= htmlspecialchars($row['sales_price'])?>"><?= htmlspecialchars($row['name'])?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Cantidad</label>
                <input type="number" class="form-control" id="amount" name="amount" value="1" required min="1">
            </div>
            <button type="button" class="btn btn-success btn-block" id="addProduct">Agregar</button>
    </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Seleccione el cliente</h3>
        <div class="form-group">
            <select class="selectpicker" data-live-search="true" id="customer_id" name="customer_id">
                <option value="">Seleccione</option>
                <?php if (!empty($listClients)): ?>
                    <?php foreach ($listClients as $row): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name'])." / ".htmlspecialchars($row['contact'])?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="newCustomer">No registrado (Check):</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" id="newCustomer" name="newCustomer">
                    </div>
                </div>
                <input type="text" class="form-control" id="newCustomername" name="newCustomername" placeholder="Nombre cliente">
            </div>
        </div>
        <div class="form-group">
            <label for="newCustomercontact">Correo</label>
            <input type="email" class="form-control" id="newCustomercontact" name="newCustomercontact" placeholder="Correo">
        </div>
    </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4" >Medio de pago</h3>
        <div class="form-group">
            <select class="selectpicker" data-live-search="true" id="paymentmethods_id" name="paymentmethods_id" required>
                <option value="">Seleccione</option>
                <?php if (!empty($listPaymentsMethods)): ?>
                    <?php foreach ($listPaymentsMethods as $row): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>" data-price="<?= htmlspecialchars($row['value_added'])?>" data-percentage="<?= htmlspecialchars($row['percentage'])?>"><?= htmlspecialchars($row['name'])?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="pending_call" name="pending_call">
            <label class="form-check-label" for="pending_call">Pendiente</label>
        </div>
        <div class="form-group">
            <label for="trackingcode">Cod.Seguimiento</label>
            <input type="text" class="form-control" id="trackingcode" name="trackingcode">
        </div>
    </div>
    </div>


    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">VALORES</h3>
        <h3 class="text-left mb-4" id="totalSend"></h3>
        <h3 class="text-left mb-4" id="totalProducts"></h3>
        <h3 class="text-left mb-4" id="totalSum"></h3>
    </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">PRODUCTOS AÑADIDOS</h3>
        <ul class="list-group mb-3" id="productList">
            <!-- products -->
        </ul>
        <button type="button" class="btn btn-dark btn-block">Cotizar</button>
        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
    </div>
    </div>

    <!-- products JSON -->
    <input type="hidden" name="products" id="productsInput">

</div>
</div>
</form>

<?php include 'footer.php'; ?>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/invoices.js"></script>

</div>
</body>
</html>
