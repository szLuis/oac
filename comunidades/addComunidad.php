<?php
/**
 * @author Luis Salazar
 * @copyright 2015
 */
require_once "../spoon/spoon.php";
session_start();

$campos = array($_POST['comunidad']);

$campos.= $_POST['parroquias'];

foreach ($campos as $value) {
    if (empty($value))
        exit("Hay campos en blanco, verifique e intente de nuevo");
}

$comunidad = trim($_POST['comunidad']);

$id_parroquia  = $_POST['parroquias'];


if (!isset($comunidad))
{
    exit("Verifique que los campos requeridos hayan sido llenados, e intente de nuevo");
}
 
try {

    
    $_data['comunidad'] = $comunidad;
    $_data['id_parroquia'] = $id_parroquia;

    if (isset($_POST['id_comunidad'],$_POST['parroquias']) && !empty($_POST['id_comunidad'])){
        $id_comunidad = $_POST['id_comunidad'];
        $objDB = new DBConexion();
        $objDB->execute("SET NAMES utf8");
        $update = $objDB->update("comunidades", $_data, 'id_comunidad' . ' = ?', $id_comunidad );
        if ($update===1){
            $processLastId = 1;
        }
    }else{
        
        $objDB = new DBConexion();
        $objDB->execute("SET NAMES utf8");
        $processLastId = $objDB->insert("comunidades", $_data);
    }

    
    if ($processLastId > 0){
        echo 1; 
    }
} 


catch (Exception $ex) {
    if (23000 == $ex->getCode()){
        echo "Esta comunidad ya existe, selecciónela en la pantalla anterior, luego haga clic en Comunidad y asóciela al estado, municipio y parroquia correspondiente";
    }
}
    
?>