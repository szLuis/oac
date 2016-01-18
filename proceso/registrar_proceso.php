<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
require_once "../spoon/spoon.php";
session_start();

if (!isset($_POST['optionsRadios'])){
    header("location: proceso.php");
}


if ($_POST['optionsRadios'] == 's'){

    $campos = array($_POST['txtproceso'],
                    $_POST['txttabla'],
                    $_POST['txtnarracionhechos'],
                    $_POST['dtpfechatopeentrega'],
                    $_POST['txtobservaciones'],
                    $_POST['optionsRadios']);

    //print_r($campos);
    foreach ($campos as $value) {
        if (empty($value))
            exit("Hay campos en blanco, verifique e intente de nuevo");
    }
    
    $dtpfentrega = date_create_from_format('d/m/Y',$_POST['dtpfechatopeentrega']);
   $dtpfechatopeentrega = date_format($dtpfentrega, 'Y-m-d') ;
}
if (isset($_POST['opcion']) && !empty($_POST['opcion'])){
        $actualizar = TRUE;
}
$txtnarracionhechos = $_POST['txtnarracionhechos']; 
$txtobservaciones = $_POST['txtobservaciones'];
$optionsRadios  = $_POST['optionsRadios'];
$proceso = $_POST['txtproceso'];
$tabla = $_POST['txttabla'];

if ($optionsRadios == 's' && 
    !isset($proceso,
           $tabla,
           $txtnarracionhechos, 
           $txtobservaciones,
           $optionsRadios
            )
     )
 {
          exit("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
 }

if ($proceso ==='solicitud'){
    $intervalo = new DateInterval('P20D');
}elseif ($proceso ==='reclamo'){
    $intervalo = new DateInterval('P15D');
}

$txtfecharesolucion = NULL;

if ($proceso !='denuncia'){

$dateResolv = new DateTime();
$dateResolv->add($intervalo);
$txtfecharesolucion = $dateResolv->format("Y-m-d");
}



    
    $last_id = "001";
    if (isset($rs['last_id'])){
        $last_id = str_pad($rs['last_id'], 3, "0", STR_PAD_LEFT);
    }
    
    $year = date("Y");


 
try {

    $proceso_data['year'] = $year;
    $proceso_data['narracion_hechos'] = $txtnarracionhechos;
    $proceso_data['observaciones'] = $txtobservaciones;
    
    if ($optionsRadios == 's')
        $proceso_data['estatus'] = "Por soportes" ; //Por soportes
    else
        $proceso_data['estatus'] = "Aceptada"; //Aceptada
    
    $proceso_data['requisitos'] = $optionsRadios;
    $proceso_data['fecha_registro'] = date('Y-m-d');
    $proceso_data['fecha_tope_entrega'] = $dtpfechatopeentrega;
    $proceso_data['fecha_resolucion'] = $txtfecharesolucion;
    if (isset($_SESSION['idCiudadano'])){
        $proceso_data['id_ciudadano'] = $_SESSION['idCiudadano'];
    }
    
    if (isset($_SESSION['idUsuario'])){
        $proceso_data['idusuario'] = $_SESSION['idUsuario'];
    }
    
    if ($actualizar){
        $estatus_data['estatus'] = $estatus;
        $id_proceso = $_POST['txtcodigo'];
        $objDB = new DBConexion();
        $update = $objDB->update($tabla, $proceso_data, 'id_'.$proceso . ' = ?', $id_proceso );
        if ($update===1){
            $processLastId = $id_proceso;
        }
    }else{
        $objDB = new DBConexion();
        $proceso_data['comunidad'] = $_SESSION['comunidad'];
        $processLastId = $objDB->insert($tabla, $proceso_data);
    }

    
    if ($processLastId > 0){
        echo $processLastId . "," . $proceso . "," . $tabla; 
    }

} 


catch (Exception $ex) {
    echo $ex->getMessage();
    
}

?>