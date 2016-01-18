<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

include_once("xlsxwriter.class.php");


$filename = "atenciones.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$header = array(
    'Hechos'=>'string',
    'Observaciones'=>'string',
    'fecha'=>'date',
    'Comunidad'=>'string',
    'Direccion'=>'string',
);

 include 'spoon/spoon.php';
        $objDB= new DBConexion();
 $fechaInicial = $_POST['fechainicial'];
 $fechaFinal = $_POST['fechafinal'];

$fecha = date_create_from_format("d/m/Y", $fechaInicial) ;                        
$fechaInicial = date_format($fecha, "Y-m-d");

$fecha = date_create_from_format("d/m/Y", $fechaFinal) ;                        
$fechaFinal = date_format($fecha, "Y-m-d");


        $query = "SELECT narracion_hechos, observaciones, atenciones.fecha_registro as fechareg, comunidades.comunidad as community, ciudadanos.direccion as address
FROM atenciones
INNER JOIN comunidades ON comunidades.id_comunidad = atenciones.comunidad
INNER JOIN ciudadanos ON ciudadanos.id_ciudadano = atenciones.id_ciudadano
WHERE atenciones.fecha_registro >  '{$fechaInicial}'
AND atenciones.fecha_registro <  '{$fechaFinal}'";
        
        
                
$atencionesArray = $objDB->getRecords($query);
//$data1 = array(
//    array('2003','1','-50.5','2010-01-01 23:00:00','2012-12-31 23:00:00'),
//    array('2003','=B2', '23.5','2010-01-01 00:00:00','2012-12-31 00:00:00'),
//);
//$data2 = array(
//    array('2003','01','343.12'),
//    array('2003','02','345.12'),
//);
$writer = new XLSXWriter();
$writer->setAuthor('Some Author');
$writer->writeSheet($atencionesArray,'Sheet1',$header);
//$writer->writeSheet($data2,'Sheet2');
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);


