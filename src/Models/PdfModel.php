<?php
namespace App\Models;
require 'vendor/autoload.php';
use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
        
        //Mail
        $document = $pdf->Output('', "S");
        $companyname = $_ENV['COMPANY_NAME'];
        $companyemail= $_ENV['MAIL_USERNAME'];
        $mail = new PHPMailer(false);

        //data SMTP
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->SMTPAuth = $_ENV['MAIL_SMTPAUTH'];
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->addReplyTo($companyemail, $companyname);
        $mail->setFrom($companyemail, $companyname);


        //data Client
        $mail->addAddress($customer['contact']);
        $mail->Subject  =  "Cuenta de cobro";

        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        $mail->addStringAttachment($document, 'C_Cobro_' . $id . '.pdf');

        $mail->Body = "
                <html>
                <head>
                   <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                   <title>Sr. Córdoba Studio</title>
                    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet'>
                   <style type='text/css'>
                    a {color: #4A72AF;}
                    body, #header h1, #header h2, p {margin: 0; padding: 0; font-family: 'Lato', sans-serif;
                    font-weight: 300;}
                    #main {border: 1px solid #cfcece;}
                    img {display: block;}
                    #top-message p, #bottom-message p {color: #3f4042; font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
                    #header h1 {color: #ffffff !important; font-family: 'Lucida Grande', 'Lucida Sans', 'Lucida Sans Unicode', sans-serif; font-size: 24px; margin-bottom: 0!important; padding-bottom: 0; }
                    #header h2 {color: #ffffff !important; font-family: Arial, Helvetica, sans-serif; font-size: 24px; margin-bottom: 0 !important; padding-bottom: 0; }
                    #header p {color: #ffffff !important; font-family: 'Lucida Grande', 'Lucida Sans', 'Lucida Sans Unicode', sans-serif; font-size: 12px;  }
                    h1, h2, h3, h4, h5, h6 {margin: 0 0 0.8em 0;}
                    h3 {font-size: 28px; color: #444444 !important; font-family: 'Lato', sans-serif; font-weight: 300;}
                    h4 {font-size: 22px; color: #575756 !important; font-family: 'Lato', sans-serif; font-weight: 300; }
                    h5 {font-size: 18px; color: #444444 !important; font-family: 'Lato', sans-serif;  font-weight: 300;	}
                    p {font-size: 16px; color: #444444 !important; font-family: 'Lato', sans-serif; ; line-height: 1.5;}
                   </style>
                </head>
                <body>
                <table width='100%' cellpadding='0' cellspacing='0' bgcolor='#e4e4e4'><tr><td>
                    <table id='main' width='650' align='center' cellpadding='0' cellspacing='15' bgcolor='ffffff'>
                        <tr>
                            <td>
                                <table id='header' cellpadding='10' cellspacing='0' align='center' bgcolor='#F0F0F0'>
                                    <tr>
                                        <td width='600'><img src='".$_ENV['URL']."public/img/logo_srcordoba.png' alt='' style='width:250px ; max-width: 300px; height: auto; margin: auto; '></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table id='content-6' cellpadding='0' cellspacing='0' align='center'>
                                    <h4 align='center'>".$customer['name']."</h4>
                                    <h4 align='center'>Gracias por comprar en Sr. Córdoba Studio.</h4>
                                            <p align='center'>En este correo adjuntamos el comprobante de su compra.</p>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height='50px'>
                            </td>	
                        </tr>	
                        <tr>
                            <td>
                                <table  cellpadding='10' cellspacing='0' align='center' bgcolor='#F0F0F0'>
                                    <tr>
                                        <td width='600'>
                                            <h5 align='center'>Todo lo que necesite en insumos para el arte del tatuaje, </h5>
                                            <h5 align='center'><strong>CONTÁCTENOS.</strong></h5>
                                            <h3 align='center'>317 801 1898 - 316 813 0414
                                            </h3>	
                                        </td>
                                    </tr>
                                </table>
                            <table  cellpadding='0' cellspacing='0'  style='margin: 30px 0px'>
                                <tr >
                                    <td colspan='2' width='400px' >
                                    <p>Sr. Córdoba Studio 2021</p>
                                    <p>Calle 4 # 16-13 Barrio San Pablo - Zipaquirá.</p>
                                    </td>
                                    <td width='100px'>
                                        <a href='https://www.instagram.com/srcordobastudio/' target='_blank'><img src='".$_ENV['URL']."public/img/Instagram.png' style='width:30px'></a>
                                    </td>	
                                    <td width='100px'>
                                        <a href='https://www.facebook.com/SrCordobaStudio' target='_blank'><img src='".$_ENV['URL']."public/img/Facebook.png' style='width:30px'></a>
                                    </td>
                                    </tr>	
                                </table>
                            </td>
                        </tr>
                    </table>
                </td></tr></table>
                </body>
                </html>";

        if($customer['contact'] != ''){
            $mail->send();
        }
        //mail end

        $pdf->Output('C_Cobro_' . $id . '.pdf', "I");
    }
    public function search($id){
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