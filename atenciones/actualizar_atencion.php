<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
require_once "../spoon/spoon.php";
session_start();

$campos = array($_POST['tipo_atencion'],
                $_POST['txtobservaciones'],
                $_POST['txtnarracionhechos'],
                $_POST['id_atencion']);

foreach ($campos as $value) {
    if (empty($value))
        exit("Hay campos en blanco, verifique e intente de nuevo");
}

$txtobservaciones = $_POST['txtobservaciones'];
$txtnarracionhechos = $_POST['txtnarracionhechos'];
$id_atencion = $_POST['id_atencion'];
$tipo_atencion = $_POST['tipo_atencion'];

if (!isset($txtobservaciones, $tipo_atencion,
           $txtnarracionhechos,$id_atencion
    ))
 {
          exit("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
 }
 
try {
    
   $objDB= new DBConexion();
        
        $atenciones['narracion_hechos'] = $txtnarracionhechos;
        $atenciones['observaciones'] = $txtobservaciones;
        $atenciones['tipo_atencion'] = $tipo_atencion;
        
        $update = $objDB->update("atenciones", $atenciones, 'id_atencion = ?', $id_atencion );
        
        if ($update===1){
            echo '1';
        }else{
            echo '0';
        }
} 

catch (Exception $ex) {
    echo $ex->getMessage();
}

    
?>