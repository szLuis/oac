<?php
try
{


include '../spoon/spoon.php';

define("Denuncia", 'style="color: #d9534f;"');
define("Solicitud", 'style="color: #5bc0de;"');
define("Reclamo", 'style="color: #d58512;"');
define("Atencion", 'style="color: #e55510;"');

//llamado desde el dashboard

if (isset($_GET['tipoProceso']) && !empty($_GET['tipoProceso'])){
    $tipo_proceso =  $_GET['tipoProceso'];
}

if (isset($_GET['year']) && !empty($_GET['year'])){
    $year = $_GET['year'];
}

if (isset($_GET['mes']) && !empty($_GET['mes'])){
    $mes = ucfirst($_GET['mes']);
}

if (isset($_GET['id_usuario']) && !empty($_GET['id_usuario'])){
    $id_usuario = $_GET['id_usuario'];
}

if (isset($_GET['estatus']) && !empty($_GET['estatus'])){
    $estatus = $_GET['estatus'];
}

//fin del llamdo del dashboard


if (isset($_POST['id_ciudadano'])){
    $id_ciudadano = $_POST['id_ciudadano'];
}

if (isset($_POST['usuario'])){
    $usuario = $_POST['usuario'];
}


if (isset($_POST['opcion'])){
    $tipo_proceso = strtolower($_POST['opcion']);
}

if (isset($_GET['opcion'])){
    $tipo_proceso = strtolower($_GET['opcion']);
}

switch ($tipo_proceso) {
    case "denuncia":
        $tabla = "denuncias";
        $estilo = constant("Denuncia");
        break;
    case "solicitud":
        $tabla = "solicitudes";
        $estilo = constant("Solicitud");
        break;
    case "reclamo":
        $tabla = "reclamos";
        $estilo = constant("Reclamo");
        break;
    case "atencion":
        $tabla = "atenciones";
        $estilo = constant("Atencion");
        break;
//    default:
//        $estilo = 'style="color: #000000;"';
//        break;
}


function getIdUsuario($usuario){
    $objDB= new DBConexion();
    $query = "SELECT id_usuario "
            . "FROM usuarios "
            . "WHERE nombre = '{$usuario}'";
    
    $rs = $objDB->getRecord($query);
    return $rs['id_usuario'];
}

function getTipoProceso($tipo_proceso) {
        $tipo = ucfirst($tipo_proceso);
        $tipo = substr($tipo, 0, 1);
        return $tipo;
} 

 
$inicial_tipo_proceso = getTipoProceso($tipo_proceso);

if (!isset($tabla)){
    exit('Algunos datos son requeridos');
}
$objDB= new DBConexion();

$query = "SELECT *, {$tabla}.fecha_registro AS fecRegPro "
        . "FROM $tabla "
        . "LEFT JOIN ciudadanos "
        . "ON " . $tabla . ".id_ciudadano = ciudadanos.id_ciudadano";

if (isset($id_ciudadano)) // se filtra por ciudadano
{
    $query = "SELECT *, {$tabla}.fecha_registro AS fecRegPro "
        . "FROM $tabla "
        . "LEFT JOIN ciudadanos "
        . "ON " . $tabla . ".id_ciudadano = ciudadanos.id_ciudadano "
        . "WHERE " . $tabla . ".id_ciudadano ={$id_ciudadano}";
}

if (isset($year) && !empty($year))// llamado desde dashboard
{
    
    $objDB->execute("SET lc_time_names = 'es_VE';");

    if (isset($estatus) && !empty($estatus)){// llamado desde dashboard
        $query = "SELECT  *, {$tabla}.fecha_registro AS fecRegPro 
                FROM {$tabla} 
                LEFT JOIN ciudadanos
                ON {$tabla}.id_ciudadano = ciudadanos.id_ciudadano
                WHERE idusuario = {$id_usuario}
                AND MONTHNAME({$tabla}.fecha_registro)='{$mes}'
                AND year = '{$year}'
                AND estatus = '{$estatus}' ";
    }else{ 
        $query = "SELECT *, {$tabla}.fecha_registro AS fecRegPro 
                FROM {$tabla} 
                LEFT JOIN ciudadanos
                ON {$tabla}.id_ciudadano = ciudadanos.id_ciudadano
                WHERE idusuario = {$id_usuario}
                AND MONTHNAME({$tabla}.fecha_registro)='{$mes}'
                AND year = '{$year}'";
    }
}
$numRows = $objDB->getNumRows($query);
$rs = $objDB->getRecords($query);
    $rows_json = array();
    foreach ($rs as $value) {
            
            $fecha = date_create_from_format("Y-m-d", $value['fecha_resolucion']) ;
        if ("atenciones" == $tabla){
            $fecha = date_create_from_format("Y-m-d", $value['fecRegPro']) ;
        }
        
        $fecha_resolucion = date_format($fecha, "d/m/Y");
        
        
        if (!isset($id_ciudadano)){
            $id_ciudadano = $value['id_ciudadano'];
        }
        
        $sustanciar = $value['sustanciar'];
        
        if ($sustanciar=='n'){
            $sustanciar='No';
        }  else {
            $sustanciar='Sí';
        }
        
        $estatus = $value['estatus'];
        
        if (empty($estatus)){
            $estatus = "ninguno";
        }
//                        if ($estatus=='A'){
//                            $estatus='Aceptada';
//                        }  else {
//                            $estatus='Rechazada';
//                        }
        $id_process  = 'id_'.$tipo_proceso;
        $codigo_proceso = "OAC-" . $inicial_tipo_proceso . "-" . "$value[$id_process]" . "-" . $value['year'];
//                      
        
        
        if ($value['fecRegPro']==date('Y-m-d')){
            $accion = "<a class='editar' href='proceso/proceso.php?proceso={$tipo_proceso}&opcion=actualizar&id_proceso={$value[$id_process]}'>Editar</a>";
            if (isset($year)){
                $accion = "<a class='editar' href='../proceso/proceso.php?proceso={$tipo_proceso}&opcion=actualizar&id_proceso={$value[$id_process]}'>Editar</a>";
            }
            
            if ($tipo_proceso==="atencion"){
                $accion = "<a class='editar' href='../atenciones/ver_atencion.php?opcion=actualizar&id_proceso={$value[$id_process]}'>Editar</a>";
            }
        }else{
            $accion = "No editable";
        }

        // echo "<tr>
        //     <td><a href='#myModal' data-tabla='{$tabla}' data-codigo='{$codigo_proceso}' data-proceso='{$tipo_proceso}' data-id_proceso='{$value[$id_process]}' data-toggle='modal'>{$codigo_proceso}</a></td>
        //     <td>{$value['observaciones']}</td>

        //     <td><a class='estatus'  href='#cambiarEstatus' data-tabla='{$tabla}' data-codigo_proceso='{$codigo_proceso}' data-tipo_proceso='{$tipo_proceso}' data-id_proceso='{$value[$id_process]}' data-toggle='modal'>{$estatus}</a></td>
        //     <td>{$value['apellidos']}, {$value['nombres']}</td>
        //     <td>{$fecha_resolucion}</td>
        //     <td>{$accion}</td>
        // </tr>";

        $codigo = "<a href='#myModal' data-tabla='{$tabla}' data-codigo='{$codigo_proceso}' data-proceso='{$tipo_proceso}' data-id_proceso='{$value[$id_process]}' data-toggle='modal'>{$codigo_proceso}</a>";
        $observaciones = $value['observaciones'] ;
        $status_link = "<a class='estatus'  href='#cambiarEstatus' data-tabla='{$tabla}' data-codigo_proceso='{$codigo_proceso}' data-tipo_proceso='{$tipo_proceso}' data-id_proceso='{$value[$id_process]}' data-toggle='modal'>{$estatus}</a>";
        $ciudadano = $value['apellidos'] . ", " .$value['nombres'];

        if (empty($ciudadano) || trim($ciudadano) == ","){
            $ciudadano = "no registrado";
        }
        $ciudadano = utf8_encode($ciudadano);
        $observaciones = utf8_encode($observaciones);

        array_push($rows_json, array("codigo"=>$codigo, "observaciones"=>$observaciones, "estatus"=>$status_link, "ciudadano"=>$ciudadano, "fecha"=>$fecha_resolucion, "accion" =>$accion));

    }
    
    $arr = array("draw"=> 1, "recordsTotal"=>$numRows,  "recordsFiltered"=>$numRows,   "data"=>$rows_json);
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($arr);


}


catch(Exception $e)
{
   
    echo $e->getMessage();
}
?>
                    
                
        
        