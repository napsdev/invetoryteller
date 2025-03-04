<!DOCTYPE html>
<html lang="es">
<?php include 'header.php'; ?>
<body class="bg-light">
<div class="container">
<?php include 'navbar.php'; ?>
<div id="alertContainer"></div>
<form action="<?= $_ENV['BASE_URL_PATH']?>/salidas/create" method="post" id="productForm" target="_blank">
<div class="container mt-5 mb-1" id="products">
<div class="row justify-content-center">

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Productos</h3>
            <div class="form-group">
                <select class="selectpicker" data-live-search="true" id="product_id" name="product_id">
                    <?php if (!empty($listProducts)): ?>
                        <?php foreach ($listProducts as $row): ?>
                            <option value="<?= htmlspecialchars($row['id']) ?>" 
                            data-cartridge="<?= htmlspecialchars(($row['cartridge'] == 1) ? 1 : (($row['cartridge'] == 2) ? 0 : 0))?>" 
                            data-cartridgevalue="<?= htmlspecialchars(($row['cartridge'] == 1) ? $row['cartridgevalue'] : $row['sales_price'])?>" 
                            data-price="<?= htmlspecialchars($row['sales_price'])?>">
                            <?= htmlspecialchars($row['name'])." - ".htmlspecialchars($row['barcod'])?></option>
                        <?php endforeach;?>
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
        <h3 class="text-left mb-4" id="cartridgeSum"></h3>
        <h3 class="text-left mb-4" id="totalSum"></h3>
    </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">PRODUCTOS AÑADIDOS</h3>
        <ul class="list-group mb-3" id="productList">
            <!-- products -->
        </ul>
        <button type="button" class="btn btn-dark btn-block" name="quote" id="quoteButton">Cotizar</button>
        <button type="button" class="btn btn-primary btn-block" id="registerButton">Registrar</button>
    </div>
    </div>

    <!-- products JSON -->
    <input type="hidden" name="products" id="productsInput">

</div>
</div>
</form>

<?php include 'footer.php'; ?>
<script>
    document.getElementById('quoteButton').addEventListener('click', function () {
        document.getElementById('productForm').action = "<?= $_ENV['BASE_URL_PATH']?>/salidas/quote";
        document.getElementById('productForm').target = "_blank";
        const productsInput = document.getElementById('productsInput');
        productsInput.value = JSON.stringify(products);
        document.getElementById('productForm').submit();
    });

    function showAlert(message, type = "warning") {
        const alertContainer = document.getElementById("alertContainer");
        alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show mx-auto mt-3" style="max-width: 500px;" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.remove()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    }

    document.getElementById('registerButton').addEventListener('click', function () {

        document.getElementById('productForm').action = "<?= $_ENV['BASE_URL_PATH']?>/salidas/create";
        document.getElementById('productForm').target = "_self";
        const checkbox = document.getElementById("newCustomer");
        const nameField = document.getElementById("newCustomername").value.trim();
        const emailField = document.getElementById("newCustomercontact").value.trim();
        const customerSelect = document.getElementById("customer_id");
        const selectedValue = customerSelect.value.trim();
        const paymentSelect = document.getElementById("paymentmethods_id");
        const selectedValuePay = paymentSelect.value.trim();

        if (checkbox.checked) {
            if (nameField === "" || emailField === "") {
                showAlert("Debe llenar ambos campos (Nombre y Correo) si el checkbox está marcado.", "warning");
                return false;
            }
            if (!validateEmail(emailField)) {
                showAlert("Ingrese un correo válido.", "warning");
                return false;
            }
        }else if(selectedValue === ""){
            showAlert("Debe seleccionar un cliente válido.", "warning");
            return false;
        }
        if (selectedValuePay === "") {
            showAlert("Debe seleccionar un método de pago válido.", "warning");
            return false;
        }
        if (products.length === 0) {
            showAlert("Debe agregar al menos un producto.", "warning");
            return false;
        }

        document.getElementById('productForm').target = "_blank";
        const productsInput = document.getElementById('productsInput');
        productsInput.value = JSON.stringify(products);
        document.getElementById('productForm').submit();
        location.reload();
    });
</script>
<script src="<?= $_ENV['BASE_URL_PATH'] ?>/public/js/invoices_3.js"></script>

</div>
</body>
</html>
