<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
require_once "../spoon/spoon.php";
session_start();


if (isset(          $_POST['tipo_proceso'] , 
          $_POST['codigo_proceso'] , 
          $_POST['id_proceso'] , 
          $_POST['estatus'], 
          $_POST['tabla'], 
          $_POST['observaciones'])){
//    $id_citizen = $_POST['id_citizen'];
    $id_proceso = $_POST['id_proceso'];
    
    $codigo_proceso = $_POST['codigo_proceso'];
    $tipo_proceso = $_POST['tipo_proceso'];
    
    $estatus = $_POST['estatus'];
    $tabla = $_POST['tabla'];
    $observaciones = $_POST['observaciones'];
    
    $campos = array($_POST['codigo_proceso'],
//                    $_POST['id_citizen'],
                    $_POST['tipo_proceso'],
                    $_POST['id_proceso'],//id denuncia o reclamo o solicitud
                    $_POST['estatus'],
                    $_POST['tabla'],
                    $_POST['observaciones']);
    
    foreach ($campos as $value) {
        if (empty($value))
            exit("Hay campos en blanco, verifique e intente de nuevo");
    }
    
    $fecha_registro = date('Y-m-d') ;
    
}

 
try {

    $proceso_data['codigo_proceso'] = $codigo_proceso;
    $proceso_data['fecha'] = $fecha_registro;
    $proceso_data['estatus'] = $estatus;
    $proceso_data['observacion'] = $observaciones;
    $proceso_data['funcionario'] = $_SESSION['nombre'];
    
    
    $objDB = new DBConexion();
    $processLastId = $objDB->insert('detalles_procesos', $proceso_data);
    
    $estatus_data['estatus'] = $estatus;
    $updateStatus = $objDB->update($tabla, $estatus_data, 'id_'.$tipo_proceso . ' = ?', $id_proceso );


    
    if ($processLastId > 0){
        echo $id_proceso; 
    }

} 


catch (Exception $ex) {
    echo $ex->getMessage();
    
}
?>