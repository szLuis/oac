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
            
        $html = '<div style="background: url(../imagenes/ceb_logo_small.png) no-repeat; line-height: 45px;"><h4 style="text-align:center; "><strong>REPÚBLICA BOLIVARIANA DE VENEZUELA</strong><br><strong>CONTRALORÍA DEL ESTADO BARINAS</strong></h4></div><br><br>';
        
        $html.= '<h4 style="text-align:center;"><strong>ATENCION # ' . $codigo_proceso . '</strong></h4><br>';

        $html.= '<p style="text-align:right; font-size:13px; "><strong>Registrada el ' . $fecha . '</strong></p>';

        /*
        * Datos del ciudadano
        */
                
        $html.='<div style="width:100%; "><div style="float: none;width:100%; "><h4>Datos del Ciudadano</h4><hr style="margin-bottom: 5px; margin-top: 0px;">' ;
        $html.='<p><b>Cédula:</b> ' . number_format($rs['cedula'],0,',','.') . '</p>' ;
        $html.='<p><b>Nombre:</b> ' . $rs['apellidos'];
        $html.=', ' . $rs['nombres'] . '</p>' ;

        $html.='<p><b>Teléfonos:</b> ' . $rs['telefonos'] . '</p>' ;
        $html.='<p><b>Correo:</b> ' . $rs['correo'] . '</p>' ;

        $html.='<p><b>Dirección:</b> ' . $rs['direccion'] . '</p></div>' ;

        /*
        * Datos de la atención
        */
               
        $html.='<div style="width:100%; "><br><div style="float: none;width:100%; "><h4>Datos de la Atención</h4><hr style="margin-bottom: 5px; margin-top: 0px;"></div></div>' ;
        $html.= '<p style="text-align:justify;  "><b>Tipo:</b> ' . trim(utf8_decode($rs['tipo_atencion'])) .'</p><br>';
            $html.= '<p style="text-align:justify; "><b>Tarea realizada:</b> ' . trim(utf8_decode($rs['narracion_hechos'])) .'</p><br>';
        $html.= '<p style="text-align:justify; "><b>Observaciones:</b> ' . trim(utf8_decode($rs['observaciones'])) .'</p><br>';

  

        $html.='<div style="float:none; width:100%; "><br><h4>Recibido en la OAC por el Funcionario:</h4>' . ($rs['nombre']) . '</div></div>';





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

            // $html2pdf = new Html2Pdf('P','A4','fr');
            // // echo $html;
            // $html2pdf->writeHTML('heeeey');
            // // echo 'hey joe';
            // $html2pdf->output('atencion.pdf');
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    
    
    
    
    
}