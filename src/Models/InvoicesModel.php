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
        $message = "";

        if (isset($OptionCustomerSelect)) {
            $phone = 'N/A';
            $address = 'N/A';
            $document = 'N/A';

            if(empty(trim($name)) || empty(trim($contact))){
                $message = 'El nombre del cliente o el correo no puede estar vacio.';
            }else{
                $customer_id = $this->customersInstance->create($name,$phone,$address,$document,$contact);
                if (!is_numeric($customer_id)) {
                    $message = 'Error al crear el cliente.';
                }
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
            
            foreach ($products as $product) {
                $productinfo = $this->productInstance->get($product['id']);
                $this->productInstance->updateAmount($product['id'], -abs($product['amount']));

                if (!empty($productinfo) && is_array($productinfo)) {
                    $productData = $productinfo[0];
                    $total += $productData['sales_price'] * $product['amount'];
                    $revenue += $productData['revenue'] * $product['amount'];

                    $productsArray[] = [
                        'name' => $productData['name'],
                        'sales_price' => $productData['sales_price'],
                        'amount' => $product['amount'],
                    ];
                }

            }

            $paymentmethod_info = $this->paymentMethodsInstance->get($paymentmethods_id);
                if (!empty($paymentmethod_info) && is_array($paymentmethod_info)) {
                    $paymentmethodData = $paymentmethod_info[0];
                    if ($paymentmethodData['percentage'] == 0) {
                    $shipping = $paymentmethodData['value_added'];
                    } else {
                    $shipping = ($total*($paymentmethodData['percentage']/100))+$paymentmethodData['value_added'];
                    }
                }

                $productsArray[] = [
                    'name' => 'Envio',
                    'sales_price' => $shipping,
                    'amount' => 1,
                ];

                $total += $shipping;

            $productsJSON = json_encode($productsArray, JSON_PRETTY_PRINT);


        } else {
            $message = 'El arreglo de productos está vacío.';
        }
        if (!is_numeric($paymentmethods_id)) {
            $message = 'El metodo de pago no puede estar vacio';
        }


        if (!empty($message)){
            return $message;
        }else{

            try {
                $query = "INSERT INTO invoices (customer_id, products, total, revenue, pending_call, paymentmethods_id, trackingcode) VALUES (:customer_id, :products, :total, :revenue, :pending_call, :paymentmethods_id, :trackingcode)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':products', $productsJSON);
                $stmt->bindParam(':total', $total);
                $stmt->bindParam(':revenue', $revenue);
                $stmt->bindParam(':pending_call', $pending_call);
                $stmt->bindParam(':paymentmethods_id', $paymentmethods_id);
                $stmt->bindParam(':trackingcode', $trackingcode);
                $stmt->execute();
                return "Factura creada correctamente.";
            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                return "Error en la consulta";
            }
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