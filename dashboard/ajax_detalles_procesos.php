<?php
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
    function get_fulldetails($tabla, $proceso, $id_proceso, $codigo_proceso){
        include '../spoon/spoon.php';
        $objDB= new DBConexion();
        
//        if (isset($_POST['id_proceso'])){
//            $id_proceso = $_POST['id_proceso'];
//            $proceso = $_POST['proceso'];
//            $tabla = $_POST['tabla'];}
            
            $query = "SELECT *, " . $tabla . ".fecha_registro AS fecha_registro_proceso "
                . "FROM " . $tabla . " "
                . "INNER JOIN ciudadanos "
                . "ON " . $tabla .".id_ciudadano = ciudadanos.id_ciudadano "
                . "INNER JOIN usuarios "
                . "ON " . $tabla .".idusuario = usuarios.id_usuario "                    
                . "WHERE " . $tabla .".id_" . $proceso . " = {$id_proceso}";
        
        
//        $query = "SELECT * "
//                . "FROM denuncias "
//                . "WHERE id_denuncia = $id_proceso";
                
                $rs = $objDB->getRecord($query);
//                $proceso = $_POST['proceso'];
setlocale(LC_TIME, 'es_VE.UTF-8');
date_default_timezone_set('America/Caracas');
$fecha = strftime("%A %d de %B de %Y", strtotime($rs['fecha_registro_proceso']));
    
    
                
        $html = '<h5 style="text-align:right; "><strong>Registrada el, ' . $fecha . '</strong></h5><br><br>';
        
//        '<br><br><br><h1 style="text-align:center;">Recepción de ' . $proceso . '</h1><br><br><br><br><br><br><br><br>'
         $html.= '<p style="text-align:justify;"><b>Narración de los hechos:</b> ' . trim($rs['narracion_hechos']) .'</p><br>';
        $html.= '<p style="text-align:justify;"><b>Observaciones:</b> ' . trim($rs['observaciones']) .'</p><br>';

        
//        $last_id = ucfirst(substr($proceso,0,1)) . '-' . str_pad($id_proceso, 3, "0", STR_PAD_LEFT) . '-' . $rs['year'] ;
//$pdf->writeHTML(ucfirst(substr($proceso,0,1)) . '-' . $last_id, true, 0, true, 0, '');
//$html.= '<b>' . $last_id . '</b>';
        
$html.='<div style="width:100%; "><div style="float: left;width:55%; "><h4>Datos del Ciudadano</h4><hr style="margin-bottom: 5px; margin-top: 5px;">' ;
$html.='<p><b>Cédula:</b> ' . number_format($rs['cedula'],0,',','.') . '</p>' ;
$html.='<p><b>Nombre:</b> ' . $rs['apellidos'];
$html.=', ' . $rs['nombres'] . '</p>' ;

$html.='<p><b>Teléfonos:</b> ' . $rs['telefonos'] . '</p>' ;
$html.='<p><b>Correo:</b> ' . $rs['correo'] . '</p>' ;

$html.='<p><b>Dirección:</b> ' . $rs['direccion'] . '</p></div>' ;


//
//$html.= number_format($rs['cedula'],0,',','.');
//$html.= $rs['apellidos'] . ', ' . $rs['nombres'];


$html.='<div style="float:left; width:45%; "><h4>Recibido en la OAC por el Funcionario:</h4><hr style="margin-bottom: 5px; margin-top: 5px;"> <b>' . ($rs['nombre']) . '</b></div></div>';

//$html.='<p style="text-align:right;"><a href="">Ver histórico</a></p>' ;

/*
 * Consulta que devuelve el histórico de cambios de estatus de los procesos
 */
$queryD = "SELECT * "
        . "FROM detalles_procesos "
        . "WHERE codigo_proceso ='{$codigo_proceso}'";

$rsD = $objDB->getRecords($queryD);


$html.='<div style="margin-top: 250px"  class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 style="text-align: right;" class="panel-title">
        <a class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Histórico de cambios
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      <div><div style="width:25%;  float:left"> Fecha</div><div style="width:25%; float:left">Estatus</div><div style="width:25%; float:left">Observaciones</div><div style="width:25%; float:left">Funcionario</div></div>';
      
foreach ($rsD as $value) {
    $html.= '<div style="clear:both;"><div style="width:25%;  float:left">'.$value['fecha'] . '</div><div style="width:25%; float:left">' . $value['estatus'] . '</div><div style="width:25%; float:left">' . $value['observacion'] . '</div><div style="width:25%; float:left;">' . $value['funcionario'] . '</div></div>' . "\n";
    
}
        
$html.='</div>
    </div>
  </div> 
</div>';



    return $html;
    }
    
    

    if(isset($_POST['tabla']) && !empty($_POST['tabla']) &&
       isset($_POST['proceso']) && !empty($_POST['proceso']) &&
       isset($_POST['codigo_proceso']) && !empty($_POST['codigo_proceso']) &&
       isset($_POST['id_proceso']) && !empty($_POST['id_proceso'])
            ) {
        $html = get_fulldetails($_POST['tabla'], $_POST['proceso'], $_POST['id_proceso'], $_POST['codigo_proceso']);
//        echo json_encode($fulldetails);
        echo $html;
    }
    
    
    
    
    
}