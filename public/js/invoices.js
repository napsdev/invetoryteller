//Preview logic start
let products = [];
let totalSum = 0;
let totalSend = 0;

const productSelect = document.getElementById("product_id");
const amountInput = document.getElementById("amount");
const addProductButton = document.getElementById("addProduct");
const productList = document.getElementById("productList");
const paymentMethodsSelect = document.getElementById("paymentmethods_id");
const totalSendElement = document.getElementById("totalSend");
const totalProductsElement = document.getElementById("totalProducts");
const totalSumElement = document.getElementById("totalSum");

addProductButton.addEventListener("click", () => {
    const productId = productSelect.value;
    const productName = productSelect.options[productSelect.selectedIndex].text;
    const productPrice = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price);
    const amount = parseInt(amountInput.value, 10);
    if (!productId || amount <= 0 || isNaN(productPrice)) {
        alert("Por favor selecciona un producto válido, una cantidad mayor a 0 y un precio correcto.");
        return;
    }
    const totalPrice = productPrice * amount;
    products.push({ id: productId, name: productName, price: productPrice, amount: amount, total: totalPrice });
    totalSum += totalPrice;
    updateProductList();
    calculateTotalSend();
});

paymentMethodsSelect.addEventListener("change", () => {
    calculateTotalSend();
});

function updateProductList() {
    productList.innerHTML = "";
    products.forEach((product, index) => {
        const listItem = document.createElement("li");
        listItem.className = "list-group-item d-flex justify-content-between align-items-center";
        listItem.innerHTML = `
            ${product.name} - Cantidad: ${product.amount.toLocaleString()} 
            - Precio Unitario: $${product.price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} 
            - Total: $${product.total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
            <button class="btn btn-danger btn-sm" onclick="removeProduct(${index})">Eliminar</button>
        `;
        productList.appendChild(listItem);
    });
}

function calculateTotalSend() {
    const selectedOption = paymentMethodsSelect.options[paymentMethodsSelect.selectedIndex];
    const dataPrice = parseFloat(selectedOption.dataset.price) || 0;
    const dataPercentage = parseFloat(selectedOption.dataset.percentage) || 0;
    const percentageValue = (totalSum * dataPercentage) / 100;
    totalSend = dataPrice + percentageValue;
    total = totalSum + dataPrice + percentageValue;
    totalSendElement.innerHTML = `Envio: $${totalSend.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    totalProductsElement.innerHTML = `Productos: $${totalSum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    totalSumElement.innerHTML = `Total: $${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function removeProduct(index) {
    totalSum -= products[index].total;
    products.splice(index, 1);
    updateProductList();
    calculateTotalSend();
}
//Preview logic end



document.getElementById('productForm').addEventListener('submit', function (event) {
    const productsInput = document.getElementById('productsInput');
    productsInput.value = JSON.stringify(products);
});
$(document).ready(function() {
    $('.selectpicker').selectpicker();
});