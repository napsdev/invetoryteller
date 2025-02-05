<?php
namespace App\Models;
require 'vendor/autoload.php';
use Mpdf\Mpdf;
use App\Models\InvoicesModel;
use App\Models\CustomerModel;
use App\Models\PaymentMethodsModel;

class PdfModel{

    private $InvoicesModel;
    private $CustomerModel;
    private $PaymentMethodsModel;
    public function __construct(){
        $this->InvoicesModel = new InvoicesModel();
        $this->CustomerModel = new CustomerModel();
        $this->PaymentMethodsModel = new PaymentMethodsModel();
    }
    public function formatThousands($number)
    {
        return (intval($number) == $number) ? number_format($number, 0, '', ',') : number_format($number, 2, '.', ',');
    }

    public function quote($customer_id,$paymentmethods_id,$total,$productsJSON){
        echo 'Hola';

        $customer = $this->CustomerModel->get($customer_id);
        $customer = $customer[0];

        $payment = $this->PaymentMethodsModel->get($paymentmethods_id);
        $payment = $payment[0];

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [220, 280],
            'margin_top' => 7,
            'margin_left' => 7,
            'margin_right' => 7,
            'mirrorMargins' => true
        ]);

        $html = '
            <body>
            <div class="caja-recibo">
            <header class="clearfix">
            <div id="logo">
            <img src="'.$_ENV['BASE_URL_PATH'].'/public/img/logo_srcordoba.svg"/>
            </div>
            <div id="info">
            <strong id="businessname">Sr. Córdoba Studio</strong><br />
            Calle 4 # 16-13 Barrio SanPablo - Zipaquirá<br />
            Cel: 317 801 1898 - 316 813 0414<br />
            Tel: +57 1 881 0346<br />
            <a href="mailto:info@srcordobastudio.com">info@srcordobastudio.com</a>
            </div>
            <div id="number">
            Cuenta de cobro: <br />
            <strong id="invoiceid">COT</strong><br />
            No responsable de IVA
            </div>
            <div id="project">
            <div id="colone">
            <div><span>Cliente</span> ' . $customer['name'] . '</div>
            <div><span>ID</span> ' . $customer['document'] . '</div>
            <div><span>Teléfono</span> ' . $customer['phone'] . '</div>
            <div><span>Email</span> ' . $customer['contact'] . '</div>
            <div><span>Pago</span> ' . $payment['name'] . '</div>
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
        $products = json_decode($productsJSON, true);
        foreach ($products as $product) {
            $html .= '<tr>';
            $html .= '<td class="reference">'.$product['id'].'</td>';
            $html .= '<td class="qty">'.$product['amount'].'</td>';
            $html .= '<td class="service">'.$product['name'].'</td>';
            $html .= '<td class="unit">'.$this->formatThousands($product['sales_price']).'</td>';
            $html .= '<td class="total">'.$this->formatThousands($product['sales_price'] * $product['amount']).'</td>';
            $html .= '</tr>';
        }
        $html .='
            <tr>
            <td colspan="5" class="grand total">TOTAL: $' . $this->formatThousands($total) . '</td>
            </tr>
            </tbody>
            </table>
            </main>
            </div>
            <footer>
            Por Expresa disposición del artículo 616-2 del estatuto tributario,<br />
            los responsables del regimen simplicado no están en la obligación de expedir
            factura de venta.<br />
            Sr. Córdoba Studio, 2021
            </footer>
            </body>';

        $css = file_get_contents(__DIR__ .'/../../public/css/pdf.css');
        $pdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $pdf->writeHtml($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $pdf->Output('C_Cobro_COT.pdf', "I");

        exit();

    }
    public function create($id){
        $invoice = $this->InvoicesModel->get((int)$id);

        if (!is_array($invoice)) {
            die('Error: La factura no existe o es inválida: '.$id);
        }
        $invoice = $invoice[0];

        $customer = $this->CustomerModel->get($invoice['customer_id']);
        $customer = $customer[0];

        $payment = $this->PaymentMethodsModel->get($invoice['paymentmethods_id']);
        $payment = $payment[0];

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [220, 280],
            'margin_top' => 7,
            'margin_left' => 7,
            'margin_right' => 7,
            'mirrorMargins' => true
        ]);

       
        $html = '
            <body>
            <div class="caja-recibo">
            <header class="clearfix">
            <div id="logo">
            <img src="'.$_ENV['BASE_URL_PATH'].'/public/img/logo_srcordoba.svg"/>
            </div>
            <div id="info">
            <strong id="businessname">Sr. Córdoba Studio</strong><br />
            Calle 4 # 16-13 Barrio SanPablo - Zipaquirá<br />
            Cel: 317 801 1898 - 316 813 0414<br />
            Tel: +57 1 881 0346<br />
            <a href="mailto:info@srcordobastudio.com">info@srcordobastudio.com</a>
            </div>
            <div id="number">
            Cuenta de cobro: <br />
            <strong id="invoiceid">No. ' . $invoice['id'] . '</strong><br />
            No responsable de IVA
            </div>
            <div id="project">
            <div id="colone">
            <div><span>Cliente</span> ' . $customer['name'] . '</div>
            <div><span>ID</span> ' . $customer['document'] . '</div>
            <div><span>Teléfono</span> ' . $customer['phone'] . '</div>
            <div><span>Email</span> ' . $customer['contact'] . '</div>
            <div><span>Pago</span> ' . $payment['name'] . '</div>
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
                $html .= '<td class="unit">'.$this->formatThousands($product['sales_price']).'</td>';
                $html .= '<td class="total">'.$this->formatThousands($product['sales_price'] * $product['amount']).'</td>';
                $html .= '</tr>';
            }
            $html .='
            <tr>
            <td colspan="5" class="grand total">TOTAL: $' . $this->formatThousands($invoice['total']) . '</td>
            </tr>
            </tbody>
            </table>
            </main>
            </div>
            <footer>
            Por Expresa disposición del artículo 616-2 del estatuto tributario,<br />
            los responsables del regimen simplicado no están en la obligación de expedir
            factura de venta.<br />
            Sr. Córdoba Studio, 2021
            </footer>
            </body>';

        $css = file_get_contents(__DIR__ .'/../../public/css/pdf.css');
        $pdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $pdf->writeHtml($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $pdf->Output('C_Cobro_' . $id . '.pdf', "I");


    }

}