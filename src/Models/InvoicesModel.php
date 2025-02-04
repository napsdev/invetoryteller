<?php
namespace App\Models;
use App\Models\Database;
use App\Models\ProductsModel;
use App\Models\CustomerModel;
use App\Models\PaymentMethodsModel;
use PDO;
use PDOException;
class InvoicesModel
{
    private $db;
    private $productInstance;
    private $paymentMethodsInstance;
    private $customersInstance;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
        $this->productInstance = new ProductsModel();
        $this->paymentMethodsInstance = new PaymentMethodsModel();
        $this->customersInstance = new CustomerModel();
    }

    public function create($customer_id,$name,$contact,$products,$pending_call,$paymentmethods_id,$OptionCustomerSelect, $trackingcode)
    {
        if (isset($OptionCustomerSelect)) {
            //New Customer
            $phone = 'N/A';
            $address = 'N/A';
            $document = 'N/A';

            if(empty(trim($name)) || empty(trim($contact))){
                $message = 'El nombre del cliente o el correo no puede estar vacio.';
            }
        } else {
            if(!is_numeric(($customer_id))){
                $message = 'Debe seleccionar un cliente.';
            }
        }
        if (!empty($products)) {
            $total = 0;
            $revenue = 0;
            $productsArray = [];
            //save the total shipping value -> products(jsonmysql)
            foreach ($products as $product) {
                $productinfo = $this->productInstance->get($product['id']);
                //$this->productInstance->updateamount($product['id'],$product['amount']);
                if (!empty($productinfo) && is_array($productinfo)) {
                    $productData = $productinfo[0];
                    $total += $productData['sales_price'] * $product['quantity'];
                    $revenue += $productData['revenue'] * $product['quantity'];

                    $productsArray[] = [
                        'id' => $productData['id'],
                        'name' => $productData['name'],
                        'purchase_price' => $productData['purchase_price'],
                        'sales_price' => $productData['sales_price'],
                        'revenue' => $productData['revenue'],
                        'amount' => $productData['amount'],
                        'quantity' => $product['quantity'] // Agregamos la cantidad comprada
                    ];
                }

                $productsJSON = json_encode($productsArray, JSON_PRETTY_PRINT);
                echo $productsJSON;

            }
        } else {
            $message = 'El arreglo de productos está vacío.';
        }
        if (!is_numeric($paymentmethods_id)) {
            $message = 'El metodo de pago no puede estar vacio';
        }


        if (!empty($message)){
            return $message;
        }else{




        }
    }

    public function list() {
        try {
            $query = "SELECT id, customer_id, products, total, revenue, date, pending_call, paymentmethods_id, trackingcode, status FROM invoices";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }
}