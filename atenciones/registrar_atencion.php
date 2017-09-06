<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
  
try {

require_once "../spoon/spoon.php";
session_start();
//print_r($_POST);

$campos = array($_POST['txtcedula'],
                $_POST['txtapellidos'],
                $_POST['txtnombres'],
                $_POST['txtdireccion'],
                $_POST['txttelefonos'],
                $_POST['txtcorreo'],
                $_POST['txtobservaciones'],
                $_POST['tipo_atencion'],
                $_POST['comunidades']);

foreach ($campos as $value) {
    if (empty($value)){
        throw new Exception("Hay campos en blanco, verifique e intente de nuevo");
    }
    
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

$comunidad = $_POST['comunidades'];
$tipo_atencion = $_POST['tipo_atencion'];
$txtobservaciones = $_POST['txtobservaciones'];
$txtnarracionhechos = $_POST['txtnarracionhechos'];

if (!isset($txtcedula, 
           $txtapellidos,
           $txtnombres,
           $txtdireccion, 
           $txttelefonos, 
           $txtcorreo, 
           
           $tipo_atencion,
           $txtobservaciones,
           $txtnarracionhechos,
           $comunidad
    ))
 {
          throw new Exception("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
 }

    
    $dsn = 'mysql:dbname=denunciasdb;host=localhost;charset=utf8';
   
    $objDB = new PDO($dsn,"root","**");
    
    /* Procedimiento de inserción de ciudadanos a la base de datos, la actualización se hace  a través del botón actualizar
     *
     */
    if (empty($_POST['txtid_ciudadano'])) //si la variable existe pero es vacío su valor
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
        $rs->execute($ciudadanos);
    }
    
    //INSERT INTO atenciones (year, tipo_atencion, narracion_hechos, observaciones, fecha_registro, id_ciudadano, idusuario, comunidad) 
      //                   VALUES (2017, "DJP ingreso", "nanananan", "obobobobobobob", "2017-05-09", 2412, 9, 515)

    if (isset($_POST['txtid_ciudadano']) && !empty($_POST['txtid_ciudadano'])){
        $_SESSION['idCiudadano'] = $txtid_ciudadano;
    }else{
        $_SESSION['idCiudadano'] = $objDB->lastInsertId(); //id de último ciudadano registrado
    }
    


     $year = date('Y');
    
    /* 
     * Procedimiento de inserción de atenciones a la base de datos
     */
    $atenciones = array($year,
                        $tipo_atencion,
                        $txtnarracionhechos,
                        $txtobservaciones,
                        date("Y-m-d"),
                        $_SESSION['idCiudadano'],
                        $_SESSION['idUsuario'],
                        $comunidad);
    // print_r($atenciones);
    $rs2 = $objDB->prepare("INSERT INTO 
                            atenciones (year, tipo_atencion, narracion_hechos, observaciones, fecha_registro, id_ciudadano, idusuario, comunidad)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $_SESSION['comunidad'] = $comunidad;
    
   
    if ($rs2->execute($atenciones)){
        echo '1';
        
    }else{
        echo '0';
    }
} 


catch (Exception $ex) {
    echo $ex->getMessage();
    
}
 

?>
