<?php
include '../spoon/spoon.php';
session_start();
define("Atencion", 'style="color: #428bca;"');



$tipo_proceso = "atencion";
$tabla = "atenciones";
$estilo = constant("Atencion");

function getIdUsuario($usuario){
    $objDB= new DBConexion();
    $query = "SELECT id_usuario "
            . "FROM usuarios "
            . "WHERE nombre = '{$usuario}'";
    
    $rs = $objDB->getRecord($query);
    return $rs['id_usuario'];
}


function getComunidad($id_comunidad){
    $objDB= new DBConexion();
    $query = "SELECT comunidad "
            . "FROM comunidades "
            . "WHERE id_comunidad = {$id_comunidad}";
    
    $rs = $objDB->getRecord($query);
    return $rs['comunidad'];
}

function getTipoProceso($tipo_proceso) {
        $tipo = ucfirst($tipo_proceso);
        $tipo = substr($tipo, 0, 1);
        return $tipo;
} 
$inicial_tipo_proceso = getTipoProceso($tipo_proceso);

$objDB= new DBConexion();

$query = "SELECT *, atenciones.fecha_registro AS fecRegAte 
            FROM atenciones 
            INNER JOIN ciudadanos 
            ON atenciones.id_ciudadano = ciudadanos.id_ciudadano 
            WHERE atenciones.idusuario = " . $_SESSION['idUsuario'] . "   
            ORDER BY  fecRegAte DESC ";

$numRows = $objDB->getNumRows($query);
$rs = $objDB->getRecords($query);
$rows = array();
    foreach ($rs as $value) {

        $fecha = date_create_from_format("Y-m-d", $value['fecRegAte']) ;

        $fecha_atencion = date_format($fecha, "d/m/Y");
        $cedula_ciudadano = $value['cedula']; //cedula ciudadano
        $id_ciudadano = $value['id_ciudadano'];
        $tipo_atencion = $value['tipo_atencion'];
        
        $comunidad = getComunidad($value['comunidad']);

        

     
        $id_process  = 'id_'.$tipo_proceso;
        $codigo_proceso = "OAC-" . $inicial_tipo_proceso . "-" . "$value[$id_process]" . "-" . $value['year'];
        $ciudadano = $value['apellidos'] . ", " . $value['nombres'];
        
        $denuncia= '<a href="../proceso/proceso.php?proceso=denuncia&from=grida&id='.$value[$id_process].'">Denuncia</a><br>';
        $reclamo = '<a href="../proceso/proceso.php?proceso=reclamo&from=grida&id='.$value[$id_process].'">Reclamo</a><br>';
        $solicitud = '<a href="../proceso/proceso.php?proceso=solicitud&from=grida&id='.$value[$id_process].'">Solicitud</a><br>';

        if ($value['fecRegAte']==date('Y-m-d')){
            $accion = $denuncia.$reclamo.$solicitud."<a class='editar' href='ver_atencion.php?opcion=actualizar&id_proceso={$value[$id_process]}'>Editar</a>";
        }else{
            $accion = "No editable";
        }
        $codigo = "<a href='#myModal' data-tabla='{$tabla}' data-codigo='{$codigo_proceso}' data-proceso='{$tipo_proceso}' data-id_proceso='{$value[$id_process]}' data-toggle='modal'>{$codigo_proceso}</a>";
//        echo "<tr>
//                <td><a href='#myModal' data-tabla='{$tabla}' data-codigo='{$codigo_proceso}' data-proceso='{$tipo_proceso}' data-id_proceso='{$value[$id_process]}' data-toggle='modal'>{$codigo_proceso}</a></td>
//                <td>{$value['narracion_hechos']}</td>                
//                <td>{$value['observaciones']}</td>
//                <td>{$value['apellidos']}, {$value['nombres']}</td>
//                <td>{$comunidad}</td>
//                <td>{$fecha_atencion}</td>
//                <td>{$accion}</td>
//            </tr>";
                
         
         array_push($rows,array($codigo, $cedula_ciudadano, $ciudadano,  $tipo_atencion, $fecha_atencion,$accion));
    }
    $arr = array("draw"=> 1, "recordsTotal"=>$numRows,  "recordsFiltered"=>$numRows,   "data"=>$rows);
    echo json_encode($arr);
                    
    ?>