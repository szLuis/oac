<?php
session_start();
if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/index.php");
    
}else{

include '../spoon/spoon.php';

$objDB= new DBConexion();



if (isset($_GET['year']) && !empty($_GET['year'])){
    $year = $_GET['year'];
}

if (isset($_GET['atenciones']) && !empty($_GET['atenciones'])){
    $atenciones = $_GET['atenciones'];
}

if (isset($atenciones)){
/*
 * Obtener total atenciones
 */
    $queryA = "SELECT count(id_atencion) AS total_atenciones "
            . "FROM atenciones "
            . "WHERE year = '{$year}'";
    $rsA = $objDB->getRecord($queryA);
    $totAtenciones = $rsA['total_atenciones'];
    
    $cols = array(array("label"=>"Atenciones", "type" => "string"),array("label"=>"Total","type"=>"number"));
    $rows = array(array("c"=>array(array("v"=>"Atenciones"), array("v"=>$totAtenciones))));
    
    $data = array("cols"=>$cols, "rows"=>$rows);
    echo json_encode($data);
}else{
    


    /*
     * Obtener total denuncias
     */
    $queryD = "SELECT count(id_denuncia) AS total_denuncias "
            . "FROM denuncias "
            . "WHERE year = '{$year}'";
    $rsD = $objDB->getRecord($queryD);


    /*
     * Obtener total solicitudes
     */
    $queryS = "SELECT count(id_solicitud) AS total_solicitudes "
            . "FROM solicitudes "
            . "WHERE year = '{$year}'";
    $rsS = $objDB->getRecord($queryS);



    /*
     * Obtener total reclamos
     */
    $queryR = "SELECT count(id_reclamo) AS total_reclamos "
            . "FROM reclamos "
            . "WHERE year = '{$year}'";
    $rsR = $objDB->getRecord($queryR);

        $tipo_proceso = $_GET['tipoProceso'];

    $totDenuncias = $rsD['total_denuncias'];
    $totSolicitudes = $rsS['total_solicitudes'];
    $totReclamos = $rsR['total_reclamos'];

    settype($totSolicitudes, int);
    settype($totDenuncias, int);
    settype($totReclamos, int);


    $cols = array(array("label"=>"Procesos", "type" => "string"),array("label"=>"Total por proceso","type"=>"number"));
    $rows = array( 
                  array("c"=>array(array("v"=>"Solicitudes"),array("v"=>$totSolicitudes))),          
                  array("c"=>array(array("v"=>"Denuncias"),array("v"=>$totDenuncias))),
                  array("c"=>array(array("v"=>"Reclamos"),array("v"=>$totReclamos))));


    $data = array("cols"=>$cols, "rows"=>$rows);
    echo json_encode($data);
    }
}
?>

