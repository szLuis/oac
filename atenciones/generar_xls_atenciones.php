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
    'Fecha'=>'date',
    'Comunidad'=>'string',
    //'Direccion'=>'string',
);

$headerResumen = array(
    'Hechos'=>'string',
    'Cantidad'=>'string',
);


 include '../spoon/spoon.php';
        $objDB= new DBConexion();
 $fechaInicial = $_POST['fechainicial'];
 $fechaFinal = $_POST['fechafinal'];

$fecha = date_create_from_format("d/m/Y", $fechaInicial) ;                        
$fechaInicial = date_format($fecha, "Y-m-d");

$fecha = date_create_from_format("d/m/Y", $fechaFinal) ;                        
$fechaFinal = date_format($fecha, "Y-m-d");


$query = "SELECT narracion_hechos, observaciones, atenciones.fecha_registro as fechareg, comunidades.comunidad as community
            FROM atenciones
            INNER JOIN comunidades ON comunidades.id_comunidad = atenciones.comunidad
            WHERE atenciones.fecha_registro >=  '{$fechaInicial}'
            AND atenciones.fecha_registro <=  '{$fechaFinal}'";
        //, ciudadanos.direccion as address
        //LEFT JOIN ciudadanos ON ciudadanos.id_ciudadano = atenciones.id_ciudadano

/*
**Devuelve agrupadas las atenciones realizadas con su cantidad
*/
$queryCount="SELECT narracion_hechos, COUNT(narracion_hechos) AS cantidad 
FROM atenciones
INNER JOIN comunidades ON comunidades.id_comunidad = atenciones.comunidad
WHERE atenciones.fecha_registro >= '{$fechaInicial}'
AND atenciones.fecha_registro <= '{$fechaFinal}' GROUP BY narracion_hechos ORDER BY cantidad";
                
$atencionesArray = $objDB->getRecords($query);

$atencionesCountArray = $objDB->getRecords($queryCount);
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
$writer->writeSheet($atencionesArray,'ListadoDeAtenciones',$header);
$writer->writeSheet($atencionesCountArray,'AtencionesTotalizadas',$headerResumen);
//$writer->writeSheet($data2,'Sheet2');
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);


