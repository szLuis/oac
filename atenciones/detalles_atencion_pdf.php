<?php

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// require '../vendor/autoload.php';

//  use Spipu\Html2Pdf\Html2Pdf;
 
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
    
    

            
        $rs = $objDB->getRecord($query);
                        
        setlocale(LC_TIME, 'es_VE.UTF-8');
        date_default_timezone_set('America/Caracas');
        $fecha = strftime("%A, %d de %B de %Y", strtotime($rs['fecha_registro_proceso']));

        
            
        $html = '<div style="background: url(../imagenes/ceb_logo_small.png) no-repeat; padding-bottom: 45px; "><h4 style="text-align:center; font-size: 12px; line-height: 15px; "><strong>REPÚBLICA BOLIVARIANA DE VENEZUELA</strong><br><strong>CONTRALORÍA DEL ESTADO BARINAS</strong></h4></div>';
        
        $html.= '<h4 style="text-align:center; margin-top: -15px;"><strong>ATENCION # ' . $codigo_proceso . '</strong></h4><br>';

        $html.= '<p style="text-align:right; font-size:13px; "><strong>Registrada el ' . $fecha . '</strong></p>';
 
        /*
        * Datos del ciudadano
        */
                
        $html.='<div style="width:100%; font-size: 12px; "><div style="float: none;width:100%; "><h4 style="margin-bottom: 2px;">Datos del Ciudadano</h4><hr style="margin-bottom: 15px; ">' ;
        $html.='<p style="line-height: 10px;"><b>Cédula:</b> ' . number_format($rs['cedula'],0,',','.') . '</p>' ;
        $html.='<p style="line-height: 8px;"><b>Nombre:</b> ' . $rs['apellidos'];
        $html.=', ' . $rs['nombres'] . '</p>' ;

        $html.='<p style="line-height: 8px;"><b>Teléfonos:</b> ' . $rs['telefonos'] . '</p>' ;
        $html.='<p style="line-height: 8px;"><b>Correo:</b> ' . $rs['correo'] . '</p>' ;

        $html.='<p style="line-height: 12px;"><b>Dirección:</b> ' . $rs['direccion'] . '</p></div>' ;

        /*
        * Datos de la atención
        */
               
        $html.='<div style="width:100%; font-size: 12px;"><br><div style="float: none;width:100%; "><h4 style="margin-bottom: 2px;">Datos de la Atención</h4><hr style="margin-bottom: 15px; "></div>' ;
        $html.= '<p style="text-align:justify;  line-height: 10px; "><b>Tipo:</b> ' . trim(utf8_decode($rs['tipo_atencion'])) .'</p>';
            $html.= '<p style="text-align:justify; line-height: 15px;"><b>Tarea realizada:</b> ' . trim(utf8_decode($rs['narracion_hechos'])) .'</p>';
        $html.= '<p style="text-align:justify; line-height: 15px;"><b>Observaciones:</b> ' . trim(utf8_decode($rs['observaciones'])) .'</p></div>';

  

        $html.='<div style="float:none; width:100%; "><br><h4>Recibido en la OAC por el Funcionario:</h4>' . ($rs['nombre']) . '</div>';





        return $html;
    }
    
    

    if(isset($_GET['tabla']) && !empty($_GET['tabla']) &&
       isset($_GET['proceso']) && !empty($_GET['proceso']) &&
       isset($_GET['codigo_proceso']) && !empty($_GET['codigo_proceso']) &&
       isset($_GET['id_proceso']) && !empty($_GET['id_proceso'])
            ) {
        $html = get_fulldetails($_GET['tabla'], $_GET['proceso'], $_GET['id_proceso'], $_GET['codigo_proceso']);
        // $html = get_fulldetails('atenciones', 'atencion', '3404', 'OAC-A-3404-2017');
        // echo json_encode($fulldetails);
        // echo $html;

        
        
        try{

            $dompdf = new Dompdf();
            $dompdf->set_option('defaultFont', 'Helvetica');
            $dompdf->loadHtml($html);
            
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('letter', 'portrait');
            
            // Render the HTML as PDF
            $dompdf->render();
            
            // Output the generated PDF to Browser
            $dompdf->stream('my.pdf',array('Attachment'=>0));

        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    
    
    
    
    
}