<?php
/*
 * 13.02.2012
 * Manipulación del objeto usuario
 * 
 */
require_once ('../spoon/spoon.php');

if (filter_input(INPUT_POST, "txtusuario", FILTER_SANITIZE_STRING))
{
    $txtusuario=filter_input(INPUT_POST, "txtusuario", FILTER_SANITIZE_STRING);
} 


        

$objUsuario = new Usuario();


$objUsuario->setNomUsu(trim($txtusuario));

if (isset($_POST['txtpassword']))
{
    $objUsuario->setPasUsu(trim($_POST['txtpassword']));
}
//1 Contralor  2 Administrador 3 Operador
if ($objUsuario->logInUser()===true){
    if ($objUsuario->getPerfil()==='1' || $objUsuario->getPerfil()==='2'){
        echo 1;
    }else if ($objUsuario->getPerfil()==='3'){
        echo 3;
    }
}



?>
