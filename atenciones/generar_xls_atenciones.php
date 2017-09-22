<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

include_once("xlsxwriter.class.php");

include '../spoon/spoon.php';
$objDB= new DBConexion();
$fechaInicial = $_POST['fechainicial'];
$fechaFinal = $_POST['fechafinal'];

$filename = "Atenciones - desde: " . $fechaInicial . " hasta: " . $fechaFinal . ".xlsx";

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');


$headerResumen = array(
    'Hechos'=>'string',
    'Cantidad'=>'string',
);




$fecha = date_create_from_format("d/m/Y", $fechaInicial) ;                        
$fechaInicial = date_format($fecha, "Y-m-d");

$fecha = date_create_from_format("d/m/Y", $fechaFinal) ;                        
$fechaFinal = date_format($fecha, "Y-m-d");


$header = array(
    'Items' => 'string',
    'Nombres y Apellidos' => 'string',
    'Cédula' => 'string',
    'Comunidad u Organismo' => 'string',
    'Municipio' => 'string',
    'Parroquia' => 'string',
    'Sector' => 'string',
    'Hechos'=>'string',
    'Observaciones'=>'string',
    'Teléfono' => 'string',
    'Fecha'=>'date',
);

if (strtotime($fechaInicial)<=strtotime("2017-09-04")){


    $header = array(
        'Items' => 'string',
        'Nombres y Apellidos' => 'string',
        'Cédula' => 'string',
        'Comunidad u Organismo' => 'string',
        'Municipio' => 'string',
        'Parroquia' => 'string',
        'Sector' => 'string',
        'Hechos'=>'string',
        'Observaciones'=>'string',
        'Teléfono' => 'string',
        'Fecha'=>'date',
    );

    $query = "SELECT @curRow := @curRow +1 AS items,
                    CONCAT(nombres, ' ' , apellidos) AS nombre_completo, 
                    cedula, 
                    comunidades.comunidad AS ente, 
                    municipio, 
                    parroquia, 
                    comunidades.comunidad AS community, 
                    narracion_hechos, 
                    observaciones,  
                    telefonos, 
                    atenciones.fecha_registro as fechareg 
            FROM atenciones
            INNER JOIN comunidades ON comunidades.id_comunidad = atenciones.comunidad
            LEFT JOIN  parroquias_barinas ON parroquias_barinas.id = comunidades.id_parroquia
            INNER JOIN ciudadanos ON ciudadanos.id_ciudadano = atenciones.id_ciudadano, 
            (SELECT @curRow :=0 ) r
            WHERE atenciones.fecha_registro >=  '{$fechaInicial}'
            AND atenciones.fecha_registro <=  '{$fechaFinal}'";
}else{


    $header = array(
        'Items' => 'string',
        'Nombres y Apellidos' => 'string',
        'Cédula' => 'string',
        'Estado' => 'string',
        'Municipio' => 'string',
        'Parroquia' => 'string',
        'Sector' => 'string',
        'Hechos'=>'string',
        'Observaciones'=>'string',
        'Teléfono' => 'string',
        'Fecha'=>'date',
    );
    $query = "SELECT @curRow := @curRow +1 AS items,
                    CONCAT(nombres, ' ' , apellidos) AS nombre_completo, 
                    cedula, 
                    estado,
                    municipio, 
                    parroquia, 
                    comunidades.comunidad AS community, 
                    narracion_hechos, 
                    observaciones,  
                    telefonos, 
                    atenciones.fecha_registro as fechareg 
                FROM atenciones
                INNER JOIN comunidades ON comunidades.id_comunidad = atenciones.comunidad
                INNER JOIN  parroquias ON parroquias.id_parroquia = comunidades.id_parroquia
                INNER JOIN  municipios ON municipios.id_municipio = parroquias.id_municipio                
                INNER JOIN  estados ON estados.id_estado = municipios.id_estado  
                INNER JOIN ciudadanos ON ciudadanos.id_ciudadano = atenciones.id_ciudadano, 
                (SELECT @curRow :=0 ) r
                WHERE atenciones.fecha_registro >=  '{$fechaInicial}'
                AND atenciones.fecha_registro <=  '{$fechaFinal}'";
}
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

$objDB->execute("SET NAMES utf8");                
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

//************************************************************** Lista de Comunidades atendidas por rango de fecha
$headerComunidades = array(
    'Comunidad u Organismo' => 'string'
);

$queryComunidades="SELECT 
            DISTINCT(comunidades.comunidad) 
            FROM atenciones
            INNER JOIN comunidades ON atenciones.comunidad = comunidades.id_comunidad
            WHERE atenciones.fecha_registro
            BETWEEN  '{$fechaInicial}'
            AND  '{$fechaFinal}'
            ORDER BY comunidades.comunidad ASC";

$objDB->execute("SET NAMES utf8");                
$comunidadesArray = $objDB->getRecords($queryComunidades);


//************************************************************** Fin Lista de Comunidades atendidas por rango de fecha



$writer = new XLSXWriter();
$writer->setAuthor('Sistema de Atención al Ciudadano - CEB');
$writer->writeSheet($atencionesArray,'ListadoDeAtenciones',$header);
$writer->writeSheet($comunidadesArray,'ListadoDeComunidades',$headerComunidades);

$writer->writeSheet($atencionesCountArray,'AtencionesTotalizadas',$headerResumen);
//$writer->writeSheet($data2,'Sheet2');
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);


