<?php
session_start();
if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/index.php");
    
}else{

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

if (isset($_GET['tipoProceso']) && !empty($_GET['tipoProceso'])){
    $tipo_proceso = $_GET['tipoProceso'];
}

if (isset($_GET['year']) && !empty($_GET['year'])){
    $year = $_GET['year'];
}

if (isset($_GET['mes']) && !empty($_GET['mes'])){
    $mes = $_GET['mes'];
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

$query = "SELECT nombre, count({$id_proceso}) AS total, id_usuario
        FROM {$tabla}
        INNER JOIN usuarios 
        WHERE usuarios.id_usuario = {$tabla}.idusuario
        AND MONTHNAME(STR_TO_DATE(MONTH(fecha_registro), '%m'))='{$mes}'
        AND year = '{$year}' 
        GROUP BY idusuario";
$rs = $objDB->getRecords($query);

$rows= array();
$indice = 0;

foreach ($rs as $data){
    
    $total = $data['total'];
    $id_usuario = $data['id_usuario'];
    
    settype($total, int);
    settype($id_usuario, int);
    
    $indice = $indice+1;
    $color = getColor($indice);
    array_push($rows,array("c"=>array(array("v"=>$data['nombre']), array("v"=>$total), array("v"=>$id_usuario), array("v"=>$color))));
}
if (isset($_GET['tipoProceso'])){
    $tipo_proceso = $_GET['tipoProceso'];
}



$cols = array(array("label"=>"Nombre", "type" => "string"),array("label"=>"Total por funcionario","type"=>"number"),array("label"=>"Id Usuario","type"=>"number"), array("type"=>"string","role"=> "style"));
//$rows = array(array("c"=>array(array("v"=>$tipo_proceso), array("v"=>$total))));//, 
//              array("c"=>array(array("v"=>"Solicitudes"),array("v"=>$totSolicitudes))),          
//              array("c"=>array(array("v"=>"Denuncias"),array("v"=>$totDenuncias))),
//              array("c"=>array(array("v"=>"Reclamos"),array("v"=>$totReclamos))));
$data = array("cols"=>$cols, "rows"=>$rows);
echo json_encode($data);
}
?>

