<?php
namespace App\Models;
use App\Models\Database;
use App\Models\ProductsModel;
use App\Models\CustomerModel;
use App\Models\PaymentMethodsModel;
use App\Models\PdfModel;
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

    public function search($search) {
        try {
            $query = "SELECT 
                DATE_FORMAT(i.date, '%Y-%m-%d') AS dia,
                pm.name AS forma_pago,
                SUM(i.total - COALESCE(envio_valor, 0)) AS total_neto,
                SUM(i.revenue) AS total_revenue
                FROM 
                    invoices i
                JOIN 
                    paymentmethods pm ON i.paymentmethods_id = pm.id
                LEFT JOIN (
                    SELECT 
                        id,
                        JSON_EXTRACT(products, CONCAT('$[', idx - 1, '].sales_price')) AS envio_valor
                    FROM 
                        invoices,
                        JSON_TABLE(
                            products,
                            '$[*]' 
                            COLUMNS (
                                idx FOR ORDINALITY,
                                name VARCHAR(100) PATH '$.name'
                            )
                        ) AS jt
                    WHERE 
                        jt.name = 'Envio'
                ) AS envio ON i.id = envio.id
                WHERE 
                     DATE(i.date) = :search
                AND
                    i.status = 1
                GROUP BY 
                    dia, forma_pago
                ORDER BY 
                    dia, forma_pago";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':search', $search);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }

    public function table() {
        try {
            $query = "SELECT 
                DATE_FORMAT(i.date, '%Y-%m') AS mes,
                pm.name AS forma_pago,
                SUM(i.total - COALESCE(envio_valor, 0)) AS total_neto
                FROM 
                    invoices i
                JOIN 
                    paymentmethods pm ON i.paymentmethods_id = pm.id
                LEFT JOIN (
                    SELECT 
                        id,
                        JSON_EXTRACT(products, CONCAT('$[', idx - 1, '].sales_price')) AS envio_valor
                    FROM 
                        invoices,
                        JSON_TABLE(
                            products,
                            '$[*]' 
                            COLUMNS (
                                idx FOR ORDINALITY,
                                name VARCHAR(100) PATH '$.name'
                            )
                        ) AS jt
                    WHERE 
                        jt.name = 'Envio'
                ) AS envio ON i.id = envio.id
                WHERE 
                    YEAR(i.date) = YEAR(CURDATE())
                AND
                i.status = 1
                GROUP BY 
                    mes, forma_pago
                ORDER BY 
                    mes, forma_pago";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function charts() {
        try {
            $query = "SELECT 
                DATE_FORMAT(i.date, '%Y-%m') AS mes,
                SUM(i.total - COALESCE(envio_valor, 0)) AS total_neto,
                SUM(i.revenue) as total_revenue
                FROM 
                    invoices i
                JOIN 
                    paymentmethods pm ON i.paymentmethods_id = pm.id
                LEFT JOIN (
                    SELECT 
                        id,
                        JSON_EXTRACT(products, CONCAT('$[', idx - 1, '].sales_price')) AS envio_valor
                    FROM 
                        invoices,
                        JSON_TABLE(
                            products,
                            '$[*]' 
                            COLUMNS (
                                idx FOR ORDINALITY,
                                name VARCHAR(100) PATH '$.name'
                            )
                        ) AS jt
                    WHERE 
                        jt.name = 'Envio'
                ) AS envio ON i.id = envio.id
                WHERE 
                    YEAR(i.date) = YEAR(CURDATE())
                AND
                i.status = 1
                GROUP BY 
                    mes
                ORDER BY 
                    mes";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function chartrevenue() {
        try {
            $query = "SELECT 
                        revenue_by_month.month,
                        IFNULL(revenue_by_month.total_revenue, 0) - IFNULL(expenses_by_month.total_expenses, 0) AS net_income
                        FROM 
                        (SELECT 
                        DATE_FORMAT(date, '%Y-%m') AS month,
                        SUM(revenue) AS total_revenue
                        FROM invoices
                        WHERE invoices.status = 1 
                        GROUP BY month) AS revenue_by_month
                        LEFT JOIN 
                        (SELECT 
                        DATE_FORMAT(date, '%Y-%m') AS month,
                        SUM(amount) AS total_expenses
                        FROM expenses
                        GROUP BY month) AS expenses_by_month
                        ON revenue_by_month.month = expenses_by_month.month
                        UNION
                        SELECT 
                        expenses_by_month.month,
                        IFNULL(revenue_by_month.total_revenue, 0) - IFNULL(expenses_by_month.total_expenses, 0) AS net_income
                        FROM 
                        (SELECT 
                        DATE_FORMAT(date, '%Y-%m') AS month,
                        SUM(revenue) AS total_revenue
                        FROM invoices
                        WHERE invoices.status = 1 
                        GROUP BY month) AS revenue_by_month
                        RIGHT JOIN 
                        (SELECT 
                        DATE_FORMAT(date, '%Y-%m') AS month,
                        SUM(amount) AS total_expenses
                        FROM expenses
                        GROUP BY month) AS expenses_by_month
                        ON revenue_by_month.month = expenses_by_month.month;
                        ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
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
        if (!is_numeric($paymentmethods_id)) {
            $message = 'El metodo de pago no puede estar vacio';
        }
        if (!empty($products)) {
            $total = 0;
            $revenue = 0;
            $discount = 0;
            $productsArray = [];

            $countcartridge = 0;
            foreach ($products as $product) {
                if ($product['cartridge'] == 1) {
                    $countcartridge += $product['amount']; 
                }
            }

            foreach ($products as $product) {
                $productinfo = $this->productInstance->get($product['id']);
                $this->productInstance->updateAmount($product['id'], -abs($product['amount']));
                if (!empty($productinfo) && is_array($productinfo)) {
                    $productData = $productinfo[0];
                    
                    if($countcartridge >= 10 && $productData['cartridge'] == 1){
                        $total += $productData['cartridgevalue'] * $product['amount'];
                        $revenue += ($productData['cartridgevalue'] - $productData['purchase_price'])* $product['amount'];
                        $discount +=  ($productData['cartridgevalue']*$product['amount']) - ($productData['sales_price']*$product['amount']);
                        $productsArray[] = [
                            'id' => $productData['id'],
                            'name' => $productData['name'],
                            'sales_price' => $productData['sales_price'],
                            'amount' => $product['amount'],
                        ];
                    }else{   
                        $total += $productData['sales_price'] * $product['amount'];
                        $revenue += $productData['revenue'] * $product['amount'];  
                        $productsArray[] = [
                            'id' => $productData['id'],
                            'name' => $productData['name'],
                            'sales_price' => $productData['sales_price'],
                            'amount' => $product['amount'],
                        ];  
                    }
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
            
            if($discount < 0){
                $productsArray[] = [
                    'id' => '#',
                    'name' => 'Descuento por caja de cartuchos',
                    'sales_price' => $discount,
                    'amount' => 1,
                ];
            }

            if($shipping > 0){
                $productsArray[] = [
                    'id' => '#',
                    'name' => 'Envio',
                    'sales_price' => $shipping,
                    'amount' => 1,
                ];
            }
                $total += $shipping;

            $productsJSON = json_encode($productsArray, JSON_PRETTY_PRINT);


        } else {
            $message = 'El arreglo de productos está vacío.';
        }
        

        if($pending_call == 1){
            $status = 2; //pending
        }else{
            $status = 1; //completed
        }

        if (!empty($message)){
            return $message;
        }else{

            try {
                $query = "INSERT INTO invoices (customer_id, products, total, revenue, pending_call, paymentmethods_id, trackingcode, status) VALUES (:customer_id, :products, :total, :revenue, :pending_call, :paymentmethods_id, :trackingcode, :status)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':products', $productsJSON);
                $stmt->bindParam(':total', $total);
                $stmt->bindParam(':revenue', $revenue);
                $stmt->bindParam(':pending_call', $pending_call);
                $stmt->bindParam(':paymentmethods_id', $paymentmethods_id);
                $stmt->bindParam(':trackingcode', $trackingcode);
                $stmt->bindParam(':status', $status);
                $stmt->execute();

                return $this->db->lastInsertId();

            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                return "Error en la consulta";
            }
        }
    }

    public function quote($customer_id,$name,$contact,$products,$pending_call,$paymentmethods_id,$OptionCustomerSelect, $trackingcode) {
        $message = "";

        if (isset($OptionCustomerSelect)) {
            $phone = 'N/A';
            $address = 'N/A';
            $document = 'N/A';
            if(empty(trim($name)) || empty(trim($contact))){
                $message = 'El nombre del cliente o el correo no puede estar vacio.';
            }else{
                $customer_exists = $this->customersInstance->getByContact($contact);
                if (!empty($customer_exists) && is_array($customer_exists)) {
                    $customer_id = $customer_exists[0]['id'];
                } else {
                $customer_id = $this->customersInstance->create($name,$phone,$address,$document,$contact);
                if (!is_numeric($customer_id)) {
                    $message = 'Error al crear el cliente.';
                }
            }
            }
        } else {
            if(!is_numeric(($customer_id))){
                $message = 'Debe seleccionar un cliente.';
            }
        }
        if (!is_numeric($paymentmethods_id)) {
            $message = 'El metodo de pago no puede estar vacio';
        }

        if (!empty($products)) {
            $total = 0;
            $revenue = 0;
            $discount = 0;
            $productsArray = [];

            $countcartridge = 0;
            foreach ($products as $product) {
                if ($product['cartridge'] == 1) {
                    $countcartridge += $product['amount']; 
                }
            }

            foreach ($products as $product) {
                $productinfo = $this->productInstance->get($product['id']);
                if (!empty($productinfo) && is_array($productinfo)) {
                    $productData = $productinfo[0];
                    
                    if($countcartridge >= 10 && $productData['cartridge'] == 1){
                        $total += $productData['cartridgevalue'] * $product['amount'];
                        $revenue += ($productData['cartridgevalue'] - $productData['purchase_price'])* $product['amount'];
                        $discount +=  ($productData['cartridgevalue']*$product['amount']) - ($productData['sales_price']*$product['amount']);
                        $productsArray[] = [
                            'id' => $productData['id'],
                            'name' => $productData['name'],
                            'sales_price' => $productData['sales_price'],
                            'amount' => $product['amount'],
                        ];
                    }else{   
                        $total += $productData['sales_price'] * $product['amount'];
                        $revenue += $productData['revenue'] * $product['amount'];  
                        $productsArray[] = [
                            'id' => $productData['id'],
                            'name' => $productData['name'],
                            'sales_price' => $productData['sales_price'],
                            'amount' => $product['amount'],
                        ];  
                    }
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
            
            if($discount < 0){
                $productsArray[] = [
                    'id' => '#',
                    'name' => 'Descuento por caja de cartuchos',
                    'sales_price' => $discount,
                    'amount' => 1,
                ];
            }

            if($shipping > 0){
                $productsArray[] = [
                    'id' => '#',
                    'name' => 'Envio',
                    'sales_price' => $shipping,
                    'amount' => 1,
                ];
            }
            

            $total += $shipping;
            $productsJSON = json_encode($productsArray, JSON_PRETTY_PRINT);
        } else {
            $message = 'El arreglo de productos está vacío.';
        }
        
        if (!empty($message)){
            return $message;
        }else{

            $PdfInstance = new PdfModel();
            $PdfInstance->quote($customer_id,$paymentmethods_id,$total,$productsJSON);

        }
    }

    public function approve($id) {
        try {
            $query = "UPDATE invoices SET status = 1, pending_call = 2 WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "Factura aprobada";
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function cancel($id) {
        try {
            $query = "UPDATE invoices SET status = 3, pending_call = 2 WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "Factura anulada";
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }


    public function list() {
        try {
            $query = "SELECT invoices.id, customers.name as customername, invoices.products, invoices.total, invoices.revenue, invoices.date, invoices.pending_call, paymentmethods.name as paymentmethodname, invoices.trackingcode, invoices.status 
                      FROM invoices inner join customers on customers.id = invoices.customer_id  inner join paymentmethods on paymentmethods.id = invoices.paymentmethods_id order by invoices.id desc";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }

    public function get($id) {
        try {
            $query = "SELECT id, customer_id, products, total, revenue, date, pending_call, paymentmethods_id, trackingcode, status FROM invoices WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return "Error en la consulta";
        }
    }
}