<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
require_once "../spoon/spoon.php";
session_start();

$campos = array($_POST['txtcedula'],
                $_POST['txtapellidos'],
                $_POST['txtnombres'],
                $_POST['txtdireccion'],
                $_POST['txttelefonos'],
                $_POST['txtcorreo'],
                $_POST['txtid_ciudadano']);

//print_r($campos);
foreach ($campos as $value) {
    if (empty($value))
        exit("Hay campos en blanco, verifique e intente de nuevo");
}

if (isset($_POST['txtid_ciudadano']) && !empty($_POST['txtid_ciudadano'])){
    $txtid_ciudadano = $_POST['txtid_ciudadano'];
}

$txtcedula = $_POST['txtcedula'];
$txtapellidos = $_POST['txtapellidos'];
$txtnombres = $_POST['txtnombres'];
$txtdireccion = $_POST['txtdireccion']; 
$txttelefonos = $_POST['txttelefonos']; 
$txtcorreo = $_POST['txtcorreo']; 


if (!isset($txtid_ciudadano,
           $txtcedula, 
           $txtapellidos,
           $txtnombres,
           $txtdireccion, 
           $txttelefonos, 
           $txtcorreo))
 {
          exit("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
 }
 
try {
    
   $objDB= new DBConexion();
    
    if (isset($txtid_ciudadano) && !empty($txtid_ciudadano)){
        
        $ciudadanos['cedula'] = $txtcedula;
        $ciudadanos['apellidos'] = $txtapellidos;
        $ciudadanos['nombres'] = $txtnombres;
        $ciudadanos['direccion'] = $txtdireccion;
        $ciudadanos['correo'] = $txtcorreo;
        $ciudadanos['telefonos'] = $txttelefonos;
        $ciudadanos['fecha_registro'] = date("Y-m-d");
        
        $update = $objDB->update("ciudadanos", $ciudadanos, 'id_ciudadano = ?', $_POST['txtid_ciudadano'] );
        
        if ($update===1){
            echo '1';
        }
    }
} 

catch (Exception $ex) {
    echo $ex->getMessage();
}

?>