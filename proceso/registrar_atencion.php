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
                $_POST['txtobservaciones'],
                $_POST['comunidad']);

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
$comunidad = $_POST['comunidad'];

$txtobservaciones = $_POST['txtobservaciones'];
$txtnarracionhechos = $_POST['txtnarracionhechos'];

if (!isset($txtcedula, 
           $txtapellidos,
           $txtnombres,
           $txtdireccion, 
           $txttelefonos, 
           $txtcorreo, 

           $txtobservaciones,
           $txtnarracionhechos,
           $comunidad
//           $optionsRadios,
//           $txtfechanotificacion
    ))
 {
          exit("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
 }
 
try {
    
    $dsn = 'mysql:dbname=denunciasdb;host=localhost;charset=utf8';
   
    $objDB = new PDO($dsn,"root","");
    $objDB->beginTransaction();
    /*
     * Procedimiento para insertar los datos de las atenciones
     */

    /**/ 
    
    $objDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_POST['txtid_ciudadano']) && !empty($_POST['txtid_ciudadano'])){
        $ciudadanos = array($txtcedula,
                              $txtapellidos,
                              $txtnombres,
                              $txtdireccion,
                              $txtcorreo,
                              $txttelefonos,
                              $txtid_ciudadano);

        $rs = $objDB->prepare("UPDATE ciudadanos"
                            . " SET cedula=?, apellidos=?, nombres=?, direccion=?, correo=?, telefonos=?"
                            . " WHERE id_ciudadano=?");
    }else
    {
        $ciudadanos = array($txtcedula,
                          $txtapellidos,
                          $txtnombres,
                          $txtdireccion,
                          $txtcorreo,
                          $txttelefonos,
                          date("Y-m-d"));
    
        $rs = $objDB->prepare("INSERT INTO "
                            . "ciudadanos (cedula, apellidos, nombres, direccion, correo, telefonos, fecha_registro) "
                            . "VALUES (?,?,?,?,?,?,?)");
    }
    
    $rs->execute($ciudadanos);
    
    $year = date('Y');
     
    if (isset($_POST['txtid_ciudadano']) && !empty($_POST['txtid_ciudadano'])){
        $id_citizen = $_POST['txtid_ciudadano'];
    }else{
        $id_citizen = $objDB->lastInsertId();
    }
    
    /**/$atenciones = array($year,
                           $txtnarracionhechos,
                           $txtobservaciones,
                           date("Y-m-d"),
                           $id_citizen,
                           $_SESSION['idUsuario'],
                           $comunidad);
    
    $rs2 = $objDB->prepare("INSERT INTO "
                         . "atenciones (year, narracion_hechos, observaciones, fecha_registro, id_ciudadano, idusuario, comunidad) "
                         . "VALUES (?,?,?,?,?,?,?)");
    //$rs2->execute($denuncias);
    
    $_SESSION['comunidad'] = $comunidad;
    
    if (isset($_POST['txtid_ciudadano']) && !empty($_POST['txtid_ciudadano'])){
        $_SESSION['idCiudadano'] = $txtid_ciudadano;
    }else{
        $_SESSION['idCiudadano'] = $objDB->lastInsertId(); //id de último ciudadano registrado
    }
    if ($rs2->execute($atenciones)){
        $objDB->commit();
        echo '1';
        
    }
} 


catch (Exception $ex) {
    echo $ex->getMessage();
    $objDB->rollBack();
    
}

?>