<?php

defined('BASEPATH') OR exit('No direct script access allowed');





//============================================================+

// File name   : example_003.php

// Begin       : 2008-03-04

// Last Update : 2013-05-14

//

// Description : Example 003 for TCPDF class

//               Custom Header and Footer

//

// Author: Nicola Asuni

//

// (c) Copyright:

//               Nicola Asuni

//               Tecnick.com LTD

//               www.tecnick.com

//               info@tecnick.com

//============================================================+



/**

 * Creates an example PDF TEST document using TCPDF

 * @package com.tecnick.tcpdf

 * @abstract TCPDF - Example: Custom Header and Footer

 * @author Nicola Asuni

 * @since 2008-03-04

 */



// Include the main TCPDF library (search for installation path).

// require_once('tcpdf/tcpdf.php');





class MYPDF extends TCPDF {



    //Page header

    public function Header() {



        // Logo

        $image_file = K_PATH_IMAGES.'logo2.jpg';

        $this->Image($image_file, 11, 11, 50, '', 'JPG', '', 'T', false, 6, '', false, false, 0, false, false, false);

        // Set font

        // $this->SetFont('helvetica', 'B', 10);

        // Title

        // $this->Cell(80, 55, 'PROPOSTA DE CONCESSÃO DE DIÁRIAS', 0, false, 'C', 0, '', 0, false, 'M', 'M');

    }



    // Page footer

    // public function Footer() {

    //     // Position at 15 mm from bottom

    //     $this->SetY(-15);

    //     // Set font

    //     $this->SetFont('helvetica', 'I', 8);

    //     // Page number

    //     $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    // }

}



// create new PDF document

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set default header data

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);



// set header and footer fonts

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));



// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {

    require_once(dirname(__FILE__).'/lang/eng.php');

    $pdf->setLanguageArray($l);

}



// ---------------------------------------------------------



// set font

$pdf->SetFont('helvetica', 'BI', 12);



// add a page

$pdf->AddPage();





$pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 50);



$pdf->SetFont('helvetica', '', 8);




// -----------------------------------------------------------------------------

$html = '';



$valor_total = 0;

$cont = 0;

$mat = array(

    'matold' => 0,

    'matNew' => ''

);

foreach($relatorio as $info) {

    $cont ++;

    $servidor = $info['nome_cid'];

    $matricula_cid = $info['matricula_cid'];
    $matricula_MA = $info['matricula_MA'];
    $CPF_MA = $info['CPF_MA'];
    $CPF_cid = $info['CPF_cid'];
    
    $cargo_cid = $info['cargo_cid'];
    $cargo_MA = $info['cargo_MA'];

    $horario_cid = $info['horario_cid'];
    $horario_MA = $info['horario_MA'];

    $cidade_cid = $info['cidade_cid'];
    $cidade_MA = $info['cidade_MA'];


    $html .= '<br> <br>

    <table cellspacing="0" cellpadding="1" style="font-family:arial; font-size:13px; border: 1px solid #000000;" border="1">
        <tr class="thead-dark">
            <td class="col-xs-6 text-center" colspan="5" style="text-align:center;"><h4> '.$cont . ' - ' . $servidor. ' </h4></td>
        </tr>
    </table>
    
    <table cellspacing="0" cellpadding="1"  style=" font-family:arial; font-size:13px; border-top: 1px solid #000000;" border="1">

        <tr class="thead-dark">
            <td class="col-xs-6 text-center" colspan="5" style="text-align:center;"><h4> '.$cidade_cid . ' </h4></td>
            <td class="col-xs-6 text-center" colspan="5" style="text-align:center;"><h4> '.$cidade_MA . '</h4></td>
        </tr>

    </table>

    ';

    $cont = 1;

    $html .= 

    '<table cellspacing="0" cellpadding="1"  style=" font-family:arial; font-size:13px; border-top: 1px solid #000000;" border="1">
        <tr class="thead-dark">
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>CPF: '.$CPF_cid . ' </h4></td>
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>CPF: '.$CPF_MA . '</h4></td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="1"  style=" font-family:arial; font-size:13px; border-top: 1px solid #000000;" border="1">
        <tr class="thead-dark">
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>Matricula: '.$matricula_cid . ' </h4></td>
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>Matricula:   '.$matricula_MA . '</h4></td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="1"  style=" font-family:arial; font-size:13px; border-top: 1px solid #000000;" border="1">
        <tr class="thead-dark">
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>Cargo: '.$cargo_cid . ' </h4></td>
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>Cargo:   '.$cargo_MA . '</h4></td>
        </tr>
    </table>
  
    <table cellspacing="0" cellpadding="1"  style=" font-family:arial; font-size:13px; border-top: 1px solid #000000;" border="1">
        <tr class="thead-dark">
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>Carga Horária: '.$horario_cid . ' </h4></td>
            <td class="col-xs-6 text-center" colspan="5" style="text-align:left;"><h4>Carga Horária:   '.$horario_MA . '</h4></td>
        </tr>
    </table>
';

}



$valor_total = number_format($valor_total, '2', ',', '.');

$tbl = <<<EOD



<h2 style="text-align:center; ">Cruzamendo de cargos por município</h2>
<p style="margin-left: 50px; font-size:15px">Mediante de dados cruzados dos portais de tranparência, fora constatado que o servidor <strong> $servidor </strong>, possui acumulo de cargos lotados nos municípios informados no relatório abaixo.<br><br><br></p>


EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
// -----------------------------------------------------------------------------





$tbl = <<<EOD



<h2 style="text-align:center; "><u> RELATORIO DE CRUZAMENTO</u> </h2>

<h2 style="text-align:center; border-bottom: 1px solid #000000;"> Total de Registros:  $contador_registro </h2></td>



EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
// -----------------------------------------------------------------------------



$tbl = <<<EOD





<table cellspacing="0" cellpadding="1">

    $html

</table>

EOD;



$pdf->writeHTML($tbl, true, false, false, false, '');




//Close and output PDF document

$pdf->Output('example_048.pdf', 'I');



//============================================================+

// END OF FILE

//============================================================+

