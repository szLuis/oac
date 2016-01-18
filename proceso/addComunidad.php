<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
require_once "../spoon/spoon.php";
session_start();

$campos = array($_POST['comunidad']);

foreach ($campos as $value) {
    if (empty($value))
        exit("Hay campos en blanco, verifique e intente de nuevo");
}

$comunidad = trim($_POST['comunidad']);


if (!isset($comunidad))
{
    exit("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
}
 
try {

    
    $_data['comunidad'] = $comunidad;
    if (isset($_POST['id_comunidad']) && !empty($_POST['id_comunidad'])){
        $id_comunidad = $_POST['id_comunidad'];
        $objDB = new DBConexion();
        $update = $objDB->update("comunidades", $_data, 'id_comunidad' . ' = ?', $id_comunidad );
        if ($update===1){
            $processLastId = 1;
        }
    }else{
        
        $objDB = new DBConexion();
        $processLastId = $objDB->insert("comunidades", $_data);
    }

    
    if ($processLastId > 0){
        echo 1; 
    }
} 


catch (Exception $ex) {
    echo $ex->getMessage();
    
}
    
?>