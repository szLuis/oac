<?php
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
    
    include '../spoon/spoon.php';
    $objDB= new DBConexion();
    
    function get_totalPorProceso(){
        $objDB= new DBConexion();
        
                
        $queryDen = "SELECT count(id_denuncia) "
                . "AS total_denuncias "
                . "FROM denuncias "
                . "WHERE fecha_tope_entrega "
                . "BETWEEN now() AND DATE_ADD(now(),INTERVAL 5 DAY) ";

                $rsDen = $objDB->getRecord($queryDen);
                
                
        $querySol = "SELECT count(id_solicitud) "
                . "AS total_solicitudes "
                . "FROM solicitudes "
                . "WHERE fecha_tope_entrega "
                . "BETWEEN now() AND DATE_ADD(now(),INTERVAL 5 DAY) ";

                $rsSol = $objDB->getRecord($querySol);
                
        
        $queryRec = "SELECT count(id_reclamo) "
                . "AS total_reclamos "
                . "FROM reclamos "
                . "WHERE fecha_tope_entrega "
                . "BETWEEN now() AND DATE_ADD(now(),INTERVAL 5 DAY) ";

                $rsRec = $objDB->getRecord($queryRec);
                
                
        $totalPorProceso = array(
                    denuncias=>$rsDen['total_denuncias'],
                    solicitudes=>$rsSol['total_solicitudes'],
                    reclamos=>$rsRec['total_reclamos']
        );
        
        return $totalPorProceso;
    }
    
    
    if(isset($_POST['getNotificaciones']) && !empty($_POST['getNotificaciones'])) {
        $totalPorProceso = get_totalPorProceso();
        echo json_encode($totalPorProceso);
    }else{

        $query =  "SELECT *, id_denuncia as id, SUBSTRING('id_denuncia',4,1) as tipPro "
                . "FROM denuncias "
                . "INNER JOIN ciudadanos "
                . "ON denuncias.id_ciudadano = ciudadanos.id_ciudadano " 
                . "WHERE denuncias.fecha_tope_entrega "
                . "BETWEEN now() AND DATE_ADD(now(),INTERVAL 5 DAY) ";
        
           $query1 =  "SELECT *, id_reclamo as id, SUBSTRING('id_reclamo',4,1) as tipPro  "
                . "FROM reclamos "
                . "INNER JOIN ciudadanos "
                . "ON reclamos.id_ciudadano = ciudadanos.id_ciudadano " 
                . "WHERE reclamos.fecha_tope_entrega "
                . "BETWEEN now() AND DATE_ADD(now(),INTERVAL 5 DAY) ";
           
              $query2 =  "SELECT *, id_solicitud as id, SUBSTRING('id_solicitud',4,1) as tipPro  "
                . "FROM solicitudes "
                . "INNER JOIN ciudadanos "
                . "ON solicitudes.id_ciudadano = ciudadanos.id_ciudadano " 
                . "WHERE solicitudes.fecha_tope_entrega "
                . "BETWEEN now() AND DATE_ADD(now(),INTERVAL 5 DAY) ";

       
        $rs =  $objDB->getRecords($query);
        $rs1 = $objDB->getRecords($query1);
        $rs2 = $objDB->getRecords($query2);
        
        if (isset($rs)){
            $rset = $rs;
        }else{
             $rs = array();
        }
        
        if (isset($rs1)){
            $rset = array_merge($rs,$rs1);
        }else{
            $rs1 = array();
        }
        
        if (isset($rs,$rs1,$rs2)){
            $rset = array_merge($rs,$rs1,$rs2);
        }else{
            $rs2 = array();
        }
        
        function getTipoProceso($tipo_proceso) {
            $tipo = ucfirst($tipo_proceso);
            $tipo = substr($tipo, 0, 1);
            return $tipo;
        } 

        foreach ($rset as $value) {
            
            $inicial_tipo_proceso = getTipoProceso($value["tipPro"]);

            $fecha = date_create_from_format("Y-m-d", $value['fecha_tope_entrega']) ;

            $fecha_tope_entrega = date_format($fecha, "d/m/Y");


            if (!isset($id_ciudadano)){
                $id_ciudadano = $value['id_ciudadano'];
            }

            $telefonos = $value['telefonos'];
            $id_process  = 'id';
            $codigo_proceso = "OAC-" . $inicial_tipo_proceso . "-" . "$value[$id_process]" . "-" . $value['year'];

            echo "<tr>
            <td>{$codigo_proceso}</td>
            <td>{$value['observaciones']}</td>        
            <td>{$value['apellidos']}, {$value['nombres']}</td>
            <td>{$telefonos}</td>
            <td>{$fecha_tope_entrega}</td>
            </tr>";
        }
    }
}
