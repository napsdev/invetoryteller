<?php
namespace App\Models;
require 'vendor/autoload.php';
use Mpdf\Mpdf;
use App\Models\InvoicesModel;

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: '.$_ENV['BASE_URL_PATH'].'/login');
    exit;
}


class PdfModel{

    private $InvoicesModel;
    public function __construct(){
        $this->InvoicesModel = new InvoicesModel();
    }

    public function create($id){
        $invoice = $this->InvoicesModel->get((int)$id);

        if (!is_array($invoice)) {
            die('Error: La factura no existe o es inválida: '.$id);
        }
        
        $invoice = $invoice[0];

        $pdf = new Mpdf();
       
        $html = '
            <body>
            <style>
            .clearfix:after {
            content: "";
            display: table;
            clear: both;
            }

            a {
            color: #5d6975;
            text-decoration: underline;
            }

            body {
            position: relative;
            width: 20cm;
            height: 26cm;
            color: #001028;
            background: #ffffff;
            font-family: Arial, sans-serif;
            font-family: Arial;
            padding: 30px 40px;
            }

            header {
            padding: 10px 0;
            margin-bottom: 30px;
            }

            #info {
            width: 250;
            margin-top: 15px;
            margin-left: 30px;
            padding: 5px;
            font-size: 12px;
            color: #5d6975;
            float: left;
            text-align: center;
            }

            #number {
            width: 160;
            margin-top: 15px;
            padding: 5px 10px 5px 15px;
            float: right;
            text-align: center;
            font-size: 15px;
            color: #5d6975;
            border-left: 1px solid #706f6f;
            }

            #project {
            clear: both;
            }

            #colone {
            float: left;
            width: 350px;
            }

            #coltwo {
            float: left;
            width: 350px;
            }

            #project span {
            color: #5d6975;
            width: 65px;
            margin-right: 10px;
            display: inline-block;
            font-size: 13px;
            font-weight: bold;
            }

            #project div {
            white-space: nowrap;
            }

            table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
            }

            table tr:nth-child(2n-1) td {
            background: #f5f5f5;
            }

            table td {
            text-align: center;
            }

            table th {
            padding: 5px 10px;
            background: #5d6975;
            color: #fff;
            white-space: nowrap;
            font-weight: bold;
            }

            table td {
            padding: 5px 8px;
            text-align: left;
            font-size: 15px;
            }

            table td.reference,
            table td.qty {
            text-align: center;
            width: 50px;
            }

            table td.description {
            text-align: left;
            }

            table td.price {
            text-align: left;
            width: 50px;
            }

            table td.total {
            width: 30px;
            text-align: right;
            }

            table td.grand {
            border-top: 1px solid #5d6975;
            font-size: 20px;
            }

            table td.unit {
            text-align: right;
            width: 30px;
            }

            table td.service {
            text-align: center;
            }

            footer {
            color: #5d6975;
            width: 94%;
            height: 30px;
            position: absolute;
            bottom: 20;
            border-top: 1px solid #c1ced9;
            padding: 10px 0;
            text-align: center;
            font-size: 12px;
            }
            </style>

            <div class="caja-recibo">
            <header class="clearfix">
            <div
            id="logo"
            style="width: 250px; text-align: left; margin-bottom: 40px; float: left"
            >
            <img
            src="'.$_ENV['BASE_URL_PATH'].'/public/img/logo_srcordoba.svg"/>
            </div>
            <div id="info">
            <strong style="font-size: 18px">Sr. Córdoba Studio</strong><br />
            Calle 4 # 16-13 Barrio SanPablo - Zipaquirá<br />
            Cel: 317 801 1898 - 316 813 0414<br />
            Tel: +57 1 881 0346<br />
            <a href="mailto:info@srcordobastudio.com">info@srcordobastudio.com</a>
            </div>
            <div id="number">
            Cuenta de cobro: <br />
            <strong style="color: #e30613">No. ' . $invoice['id'] . '</strong><br />
            No responsable de IVA
            </div>
            <div id="project">
            <div id="colone">
            <div><span>Cliente</span> ' . $invoice['id'] . '</div>
            <div><span>ID</span> ' . $invoice['id'] . '</div>
            <div><span>Teléfono</span> ' . $invoice['id'] . '</div>
            <div><span>Email</span> ' . $invoice['id'] . '</div>
            <div><span>Pago</span> ' . $invoice['id'] . '</div>
            </div>
            </div>
            </header>
            <main>
            <table>
            <thead>
            <tr>
            <th>Ref.</th>
            <th>Und.</th>
            <th>Descripción</th>
            <th>Precio Und.</th>
            <th>Total</th>
            </tr>
            </thead>
            <tbody>
            
            <!--Product information-->
            ';
            
            $products = json_decode($invoice['products'], true);
            foreach ($products as $product) {
                $html .= '<tr>';
                $html .= '<td class="reference">'.$product['id'].'</td>';
                $html .= '<td class="qty">'.$product['amount'].'</td>';
                $html .= '<td class="service">'.$product['name'].'</td>';
                $html .= '<td class="unit">'.$product['sales_price'].'</td>';
                $html .= '<td class="total">'.$product['sales_price'] * $product['amount'].'</td>';
                $html .= '</tr>';
            }
            
            $html .='
            <tr>
            <td colspan="5" class="grand total">TOTAL: $' . $invoice['total'] . '</td>
            </tr>
            </tbody>
            </table>
            </main>
            </div>
            <footer>
            Por Expresa disposición del articulo 616-2 del estatuto tributario,<br />
            los responsables del regimen simplicado no están en la obligación de expedir
            factura de venta.<br />
            Sr. Córdoba Studio, 2021
            </footer>
            </body>
        ';

        $pdf->WriteHTML($html);
        $pdf->Output('documento.pdf', 'I');


    }

}