<?php
session_start();
if ($_SESSION['logged'] != true ){
    header("Location: ../usuarios/index.php");
    
}else{

if (isset($_GET['year']) && !empty($_GET['year'])){
    $year = $_GET['year'];
}
    
include '../spoon/spoon.php';

$objDB= new DBConexion();

/*
 * Obtener total atenciones
 */
// verde naranja azul rojo marron
function getColor($indice) {
    $color = array(1=>'#109618',2=>"#ff9900", 3 => "#3366cc", 4 => "#dc3912", 5 => "Brown");
    return $color[$indice];
}

if (isset($_GET['tipoProceso'])){
    $tipo_proceso = $_GET['tipoProceso'];
}

switch ($tipo_proceso) {
    case "Atenciones":
        $tabla = "atenciones";
        $id_proceso =  "id_atencion";
        break;
    case "Denuncias":
        $tabla = "denuncias";
        $id_proceso =  "id_denuncia";
        break;
    case "Solicitudes":
        $tabla = "solicitudes";
        $id_proceso =  "id_solicitud";
        break;
    case "Reclamos":
        $tabla = "reclamos";
        $id_proceso =  "id_reclamo";
        break;
}

$objDB->execute("SET lc_time_names = 'es_VE';");

$query = " SELECT MONTHNAME(STR_TO_DATE(MONTH(fecha_registro), '%m')) AS mes, count(year) AS total "
        . "FROM {$tabla} "
        . "WHERE year = '{$year}' "
        . "GROUP BY MONTH(fecha_registro)";
        
//        SELECT count(estatus), estatus FROM denuncias GROUP BY estatus
       
$rs = $objDB->getRecords($query);

$rows= array();
$indice = 0;

foreach ($rs as $data){
    $total = $data['total'];
    settype($total, int);
    $indice = $indice+1;
    $color = getColor($indice);
    array_push($rows,array("c"=>array(array("v"=>$data['mes']), array("v"=>$total), array("v"=>$color))));
}
if (isset($_GET['tipoProceso'])){
    $tipo_proceso = $_GET['tipoProceso'];
}



$cols = array(array("label"=>"Mes", "type" => "string"),array("label"=>"Total por mes","type"=>"number"), array("type"=>"string","role"=> "style"));
//$rows = array(array("c"=>array(array("v"=>$tipo_proceso), array("v"=>$total))));//, 
//              array("c"=>array(array("v"=>"Solicitudes"),array("v"=>$totSolicitudes))),          
//              array("c"=>array(array("v"=>"Denuncias"),array("v"=>$totDenuncias))),
//              array("c"=>array(array("v"=>"Reclamos"),array("v"=>$totReclamos))));
$data = array("cols"=>$cols, "rows"=>$rows);
echo json_encode($data);
}
?>

