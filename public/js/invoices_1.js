//Bar Code
function searchAndAddProduct(barcodeBuscado) {
    const select = document.getElementById("product_id");
    const opciones = select.options;
    barcodeBuscado = barcodeBuscado.trim().toLowerCase();
    let encontrado = false;
    for (let i = 0; i < opciones.length; i++) {
        let textoOpcion = opciones[i].textContent.trim().toLowerCase();

        if (textoOpcion.includes(barcodeBuscado)) {
            select.value = opciones[i].value;
            $(select).selectpicker('refresh');
            encontrado = true;
            break;
        }
    }
    if (encontrado) {
        document.getElementById("addProduct").click();
    } else {
        alert("Producto no encontrado: " + barcodeBuscado);
    }
}
let scannedCode = "";

document.addEventListener("keydown", function (event) {
    scannedCode = scannedCode.trim();

    const amount = document.getElementById("amount");
    const newCustomername = document.getElementById("newCustomername");
    const newCustomercontact = document.getElementById("newCustomercontact");
    const trackingcode = document.getElementById("trackingcode");
    const searchInputs = document.querySelectorAll('input[type="search"]');

    if (document.activeElement === newCustomername 
        || document.activeElement === newCustomercontact 
        || document.activeElement === trackingcode
        || document.activeElement === amount
        || [...searchInputs].includes(document.activeElement)) {
        return;
    };


    if (event.key === "Enter") {
        if (scannedCode.length > 1) {
            searchAndAddProduct(scannedCode);
            scannedCode = "";
        }
        return;
    }
    if (event.key.length === 1) {
        scannedCode += event.key;
    }
});




//Preview logic start
let products = [];
let totalSum = 0;
let totalSend = 0;
let cartridgeSum = 0;

const productSelect = document.getElementById("product_id");
const amountInput = document.getElementById("amount");
const addProductButton = document.getElementById("addProduct");
const productList = document.getElementById("productList");
const paymentMethodsSelect = document.getElementById("paymentmethods_id");
const totalSendElement = document.getElementById("totalSend");
const totalProductsElement = document.getElementById("totalProducts");
const totalSumElement = document.getElementById("totalSum");
const cartridgeSumElement = document.getElementById("cartridgeSum");

addProductButton.addEventListener("click", () => {
    if (amountInput.value <= 0) {
        alert("Por favor selecciona una cantidad mayor a 0.");
        return;
    }

    const productId = productSelect.value;
    const productName = productSelect.options[productSelect.selectedIndex].text;
    const productPrice = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price);
    const cartridgeValue = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.cartridgevalue);
    const cartridgeAmount = parseInt(productSelect.options[productSelect.selectedIndex].dataset.cartridge, 10);
    const amount = parseInt(amountInput.value, 10);

    if (!productId || amount <= 0 || isNaN(productPrice)) {
        alert("Por favor selecciona un producto válido, una cantidad mayor a 0 y un precio correcto.");
        return;
    }

    const existingProduct = products.find(product => product.id === productId);
    if (existingProduct) {
        existingProduct.amount += amount;
        existingProduct.total = existingProduct.amount * existingProduct.price;
    } else {
        let finalPrice = productPrice;

        const totalPrice = finalPrice * amount;
        products.push({ id: productId, name: productName, price: finalPrice, cartridge: cartridgeAmount, amount, total: totalPrice, cartridgeValue });
    }

    updateCartridgeSum();
    updateProductList();
    calculateTotalSend();
    amountInput.value = 1;
});

function updateCartridgeSum() {
    cartridgeSum = products.reduce((sum, product) => sum + (product.cartridge * product.amount), 0);
    
    if (cartridgeSum >= 10) {
        products.forEach(product => {
            product.price = product.cartridgeValue;
            product.total = product.amount * product.price;
        });
    } else {

        products.forEach(product => {
            const originalPrice = parseFloat(productSelect.querySelector(`option[value="${product.id}"]`).dataset.price);
            product.price = originalPrice;
            product.total = product.amount * product.price;
        });
    }

    cartridgeSumElement.innerHTML = `Cartuchos: ${cartridgeSum}`;
}

function updateProductList() {
    productList.innerHTML = "";
    products.forEach((product, index) => {
        const listItem = document.createElement("li");
        listItem.className = "list-group-item d-flex justify-content-between align-items-center";
        listItem.innerHTML = `
            <div class="w-100">
                <div class="text-left">
                    <strong>${product.name}</strong><br>
                    <small class="text-muted">
                        Precio Unitario: $${product.price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} |
                        Total: $${product.total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </small>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <button class="btn btn-outline-secondary btn-sm mr-2" onclick="updateAmount(${index}, -1)">&#8722;</button>
                    <span class="font-weight-bold mx-2">${product.amount.toLocaleString()}</span>
                    <button class="btn btn-outline-secondary btn-sm ml-2" onclick="updateAmount(${index}, 1)">&#43;</button>
                    <button class="btn btn-danger btn-sm ml-3" onclick="removeProduct(${index})">X</button>
                </div>
            </div>
        `;

        productList.appendChild(listItem);
    });

    updateCartridgeSum();
}

function calculateTotalSend() {
    // Asegurar que totalSum se actualiza correctamente
    totalSum = products.reduce((sum, product) => sum + product.total, 0);

    const selectedOption = paymentMethodsSelect.options[paymentMethodsSelect.selectedIndex];

    if (!selectedOption || !selectedOption.dataset.price) {
        totalSendElement.innerHTML = "Método de pago: $0.00";
        totalProductsElement.innerHTML = `Productos: $${totalSum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        totalSumElement.innerHTML = `Total: $${totalSum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        return;
    }

    const dataPrice = parseFloat(selectedOption.dataset.price) || 0;
    const dataPercentage = parseFloat(selectedOption.dataset.percentage) || 0;
    const percentageValue = (totalSum * dataPercentage) / 100;

    totalSend = dataPrice + percentageValue;
    total = totalSum + totalSend;

    // Mostrar valores actualizados
    totalSendElement.innerHTML = `Método de pago: $${totalSend.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    totalProductsElement.innerHTML = `Productos: $${totalSum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    totalSumElement.innerHTML = `Total: $${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

// Asegurar que se ejecuta cuando cambia el método de pago
paymentMethodsSelect.addEventListener("change", calculateTotalSend);



function updateAmount(index, change) {
    if (products[index].amount + change > 0) {
        products[index].amount += change;
        products[index].total = products[index].amount * products[index].price;
        updateCartridgeSum();
        updateProductList();
        calculateTotalSend();
    } else {
        removeProduct(index);
    }
}

function removeProduct(index) {
    products.splice(index, 1);
    updateCartridgeSum();
    updateProductList();
    calculateTotalSend();
}
//Preview logic end


$(document).ready(function() {
    $('.selectpicker').selectpicker();
});