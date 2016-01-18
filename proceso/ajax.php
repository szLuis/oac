<?php
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
    
    
    
    include '../spoon/spoon.php';   
    
    function get_fullname($txtcedula){
        $objDB= new DBConexion();
        $query = "SELECT * "
                . "FROM ciudadanos "
                . "WHERE cedula = $txtcedula";
                
        $rs = $objDB->getRecord($query);
        $numRows = $objDB->getNumRows($query);
        
        if ($numRows > 0){
                
            $queryDen = "SELECT count(id_denuncia) "
                    . "AS total_denuncias "
                    . "FROM denuncias "
                    . "WHERE id_ciudadano = {$rs['id_ciudadano']}";

                    $rsDen = $objDB->getRecord($queryDen);


            $querySol = "SELECT count(id_solicitud) "
                    . "AS total_solicitudes "
                    . "FROM solicitudes "
                    . "WHERE id_ciudadano = {$rs['id_ciudadano']}";

                    $rsSol = $objDB->getRecord($querySol);


            $queryRec = "SELECT count(id_reclamo) "
                    . "AS total_reclamos "
                    . "FROM reclamos "
                    . "WHERE id_ciudadano = {$rs['id_ciudadano']}";

                    $rsRec = $objDB->getRecord($queryRec);


            $fullname = array(
                            id_ciudadano => $rs['id_ciudadano'], 
                            apellidos => $rs['apellidos'], 
                            nombres => $rs['nombres'],
                            direccion => $rs['direccion'],
                            correo => $rs['correo'],
                            telefonos => $rs['telefonos'],
                            denuncias=>$rsDen['total_denuncias'],
                            solicitudes=>$rsSol['total_solicitudes'],
                            reclamos=>$rsRec['total_reclamos']
                    );
            return $fullname;
        }
        return 0;
    }
    
    
    
    
    function get_totalPorProceso($comunidad){
        $objDB= new DBConexion();
        
                
        $queryDen = "SELECT count(id_denuncia) "
                . "AS total_denuncias "
                . "FROM denuncias "
                . "WHERE comunidad = '{$comunidad}'";

                $rsDen = $objDB->getRecord($queryDen);
                
                
        $querySol = "SELECT count(id_solicitud) "
                . "AS total_solicitudes "
                . "FROM solicitudes "
                . "WHERE comunidad = '{$comunidad}'";

                $rsSol = $objDB->getRecord($querySol);
                
        
        $queryRec = "SELECT count(id_reclamo) "
                . "AS total_reclamos "
                . "FROM reclamos "
                . "WHERE comunidad = '{$comunidad}'";

                $rsRec = $objDB->getRecord($queryRec);
                
                
        $totalPorProceso = array(
                    denuncias=>$rsDen['total_denuncias'],
                    solicitudes=>$rsSol['total_solicitudes'],
                    reclamos=>$rsRec['total_reclamos']
            );
        return $totalPorProceso;
    }
    
    
    
    
    
    function get_totalPorProcesoPorFecha($fechaInicial, $fechaFinal){
        $objDB= new DBConexion();
        
                
        $queryDen = "SELECT count(id_denuncia) "
                . "AS total_denuncias "
                . "FROM denuncias "
                . "WHERE fecha_registro "
                . "BETWEEN '{$fechaInicial}' AND '{$fechaFinal}'";

                $rsDen = $objDB->getRecord($queryDen);
                
                
        $querySol = "SELECT count(id_solicitud) "
                . "AS total_solicitudes "
                . "FROM solicitudes "
                . "WHERE fecha_registro "
                . "BETWEEN '{$fechaInicial}' AND '{$fechaFinal}'";

                $rsSol = $objDB->getRecord($querySol);
                
        
        $queryRec = "SELECT count(id_reclamo) "
                . "AS total_reclamos "
                . "FROM reclamos "
                . "WHERE fecha_registro "
                . "BETWEEN '{$fechaInicial}' AND '{$fechaFinal}'";

                $rsRec = $objDB->getRecord($queryRec);
                
                
        $totalPorProceso = array(
                    denuncias=>$rsDen['total_denuncias'],
                    solicitudes=>$rsSol['total_solicitudes'],
                    reclamos=>$rsRec['total_reclamos']
            );
        return $totalPorProceso;
    }
    
    

    if(isset($_POST['txtcedula']) && !empty($_POST['txtcedula'])) {
        $fullname = get_fullname($_POST['txtcedula']);
        echo json_encode($fullname);
    }
    
    if(isset($_POST['fechainicial'], $_POST['fechafinal']) && !empty($_POST['fechainicial']) && !empty($_POST['fechafinal'])) {
        
        $fecIniUs = date_create_from_format('d/m/Y',$_POST['fechainicial']);
        $fecIniVe = date_format($fecIniUs, 'Y-m-d') ;
        
        $fecFinUs = date_create_from_format('d/m/Y',$_POST['fechafinal']);
        $fecFinVe = date_format($fecFinUs, 'Y-m-d') ;
   
        $fullname = get_totalPorProcesoPorFecha($fecIniVe,$fecFinVe);
        echo json_encode($fullname);
    }
    
    if(isset($_POST['comunidad']) && !empty($_POST['comunidad'])) {
        $totalPorProceso = get_totalPorProceso($_POST['comunidad']);
        echo json_encode($totalPorProceso);
    }
    
    function getTipoProceso($proceso) {
        $tipo = ucfirst($proceso);
        $tipo = substr($tipo, 0, 1);
        return $tipo;
    } 
 
}
   
 ?>
