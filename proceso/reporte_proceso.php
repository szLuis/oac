<?php
//============================================================+
// File name   : example_021.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 021 for TCPDF class
//               WriteHTML text flow
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
 * @abstract TCPDF - Example: WriteHTML text flow.
 * @author Nicola Asuni
 * @since 2008-03-04
 */
require_once "../spoon/spoon.php";
session_start();
$objDB = new DBConexion();

 if (isset($_GET['id'])){
            $id = $_GET['id'];
            $proceso = $_GET['proceso'];
            $tabla = $_GET['tabla'];
            
            $query = "SELECT * "
                . "FROM " . $tabla . " "
                . "INNER JOIN ciudadanos "
                . "ON " . $tabla .".id_ciudadano = ciudadanos.id_ciudadano "
                . "WHERE " . $tabla .".id_" . $proceso . " = {$id}";
        }
        $rs = $objDB->getRecord($query);

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 021');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->setHeaderData('oac_logo.png','180.40');
//$pdf->Image('','40');

// // set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 021', PDF_HEADER_STRING);
//$pdf->setHeaderData('http://localhost/sdenuncias/imagenes/oac_logo.png');
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
$pdf->SetFont('dejavusans', '', 8);

// add a page
$pdf->AddPage();




$proceso = $_GET['proceso'];
setlocale(LC_TIME, 'es_VE.UTF-8');
date_default_timezone_set('America/Caracas');
$fecha = strftime("%A %d de %B de %Y", strtotime(date('Y-m-d')));

//$last_id = str_pad($id, 3, "0", STR_PAD_LEFT);

//$pdf->writeHTML(ucfirst(substr($proceso,0,1)) . '-' . $last_id, true, 0, true, 0, '');
// create some HTML content
$html = '<h4 style="text-align:left; ">Barinas, ' . $fecha. '</h4>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// create some HTML content
$html = '<br><br><br><h2 style="text-align:center;">Recepción de ' . $proceso . '</h2>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// create some HTML content
$html = '<br><br><br><br>'
        . '<p style="text-align:justify;"><b>Narración de los hechos:</b> ' . $rs['narracion_hechos'] .'</p><br>'
        . '<div style="text-align:justify;"><b>Observaciones:</b> ' . $rs['observaciones'] .'</div><br>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

$y = 110;
$h = 7;
$border = 0;
//$pdf->Cell(190, 15, 'Datos del Ciudadano', 0, 0, 'C');
$pdf->Ln();
$pdf->writeHTMLCell(120, $h, 14, $y-12,'<h3>Datos del Ciudadano</h3>', $border);
$pdf->Line(15, 105, 195, 105);



$last_id = ucfirst(substr($proceso,0,1)) . '-' . str_pad($id, 3, "0", STR_PAD_LEFT) . '-' . $rs['year'] ;
//$pdf->writeHTML(ucfirst(substr($proceso,0,1)) . '-' . $last_id, true, 0, true, 0, '');
$pdf->writeHTMLCell(40, $h, 175, $y - 83, '<b>OAC-' . $last_id . '</b>', $border);

$pdf->writeHTMLCell(40, $h, 20, $y,'<b>Cédula:</b> ' . number_format($rs['cedula'],0,',','.') ,$border);
$pdf->writeHTMLCell(65, $h, 60, $y,'<b>Apellidos:</b> ' . $rs['apellidos'],$border);
$pdf->writeHTMLCell(65, $h, 125, $y,'<b>Nombres:</b> ' . $rs['nombres'],$border);

$pdf->writeHTMLCell(85, $h, 20, $y+$h,'<b>Teléfonos:</b> ' . $rs['telefonos'],$border);
$pdf->writeHTMLCell(65, $h, 125, $y+$h,'<b>Correo:</b> ' . $rs['correo'],$border);

$pdf->writeHTMLCell(170, $h, 20, $y+($h*2),'<b>Dirección:</b> ' . $rs['direccion'],$border);



$pdf->Line(85, 148, 120, 148);
$pdf->writeHTMLCell(28, $h, 90, $y+41, number_format($rs['cedula'],0,',','.'), $border, 0, FALSE, TRUE,'C');
$pdf->writeHTMLCell(58, $h, 74, $y+46, $rs['apellidos'] . ', ' . $rs['nombres'],$border, 0, FALSE, TRUE,'C');


$pdf->Ln();
$pdf->writeHTML('<br><br><h3>Recibido en la OAC</h3>', true, 0, true, 0, '');
$pdf->Line(15, 180, 195, 180);

$pdf->writeHTMLCell(65, $h, 20, $y+($h*10)+5,'<b>Funcionario:</b> ' . utf8_encode($_SESSION['nombre']),$border);
$pdf->writeHTMLCell(65, $h, 20, $y+($h*11)+5,'<b>Código de proceso:</b> ' . $last_id, $border);
//utf8_encode($_SESSION['nomUsuario'])

$pdf->Line(45, 218, 80, 218);
$pdf->writeHTMLCell(48, $h, 40, $y+110, "Jefe o Coordinador" , $border, 0, FALSE, TRUE,'C');
$pdf->writeHTMLCell(60, $h, 33, $y+115, 'Oficina de Atención al Ciudadano',$border, 0, FALSE, TRUE,'C');

$pdf->Line(125, 218, 160, 218);
$pdf->writeHTMLCell(48, $h, 120, $y+110, utf8_encode($_SESSION['nombre']) , $border, 0, FALSE, TRUE,'C');
$pdf->writeHTMLCell(60, $h, 113, $y+115, 'Oficina de Atención al Ciudadano',$border, 0, FALSE, TRUE,'C');
//$pdf->MultiCell(70, 15, 'Cédula: ' . $rs['cedula'], 0, 'L');

//if ($rs['requisitos']==='s' && $rs['estatus']==='Por soportes' ){
    

    $html = '<br><br><br><br><br><br><br><i> '
            . 'Si no se presenta el o los requisitos faltantes, '
            . 'detallados arriba en <b>Observaciones,</b> dentro de los quince (15) días '
            . 'siguientes, contados a partir de la fecha impresa en esta comunicación, '
            . 'se considerará la solicitud, reclamo o denuncia defectuosa.</i> ';

    $pdf->writeHTML($html, true, 0, true, 0, '');

//}
// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($proceso.$id.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
