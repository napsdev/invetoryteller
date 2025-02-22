<?php
namespace App\Models;
use App\Models\Database;
use App\Models\Database_old;
use App\Models\CustomerModel;
use App\Models\ProductsModel;
use InvalidArgumentException;
use PDO;
use PDOException;


class MigrateModel
{
    private $db;
    private $db_old;
    private $customersInstance;
    private $productsInstance;
    public function __construct(){
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
        $dbInstance_old = new Database_old();
        $this->db_old = $dbInstance_old->getConnection();
        $this->customersInstance = new CustomerModel();
        $this->productsInstance = new ProductsModel();
    }

    public function migrate()
    {

        try {
            $query = "select id, nombre, estudio, direccion, ciudad, telefono, email, idint from clientes";
            $stmt = $this->db_old->prepare($query);
            $stmt->execute();
            $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($customer as $c) {
                $name = $c['nombre'];
                $phone = $c['id'];
                $address = $c['direccion'] . ' ' . $c['ciudad'];
                $document = $c['telefono'];
                $contact = $c['email'];

               echo ($this->customersInstance->create($name, $phone, $address, $document, $contact))."<br>";
            }
            echo 'Migración de cliente exitosa'."<br>";	
            
            $query = "select id, nombre, proveedor, cantidad, precio_compra, precio_venta, ganancia, precio_caja, cartucho from productos";
            $stmt = $this->db_old->prepare($query);
            $stmt->execute();
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($product as $p) {
                $name = $p['nombre'];
                $purchase_price = $p['precio_compra'];
                $sales_price = $p['precio_venta'];
                $amount = 0;
                $barcod = $p['id'];
                $cartridge = '';
                $cartridgevalue = 0;

                echo ($this->productsInstance->create($name,$purchase_price,$sales_price,$amount,$barcod,$cartridge,$cartridgevalue))."<br>";
            }
            echo 'Migración de productos exitosa';

        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            echo "Error en la consulta";
        }

    }
}