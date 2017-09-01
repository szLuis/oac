<?php
// session_start();

// if ($_SESSION['logged'] != true ){
//     header("Location:../usuarios/");

// }else{


    /**
     * Consulto a la base de datos si se solicita detalles o actualizar
     */





    include '../spoon/spoon.php';

//    $query = "SELECT MAX(id_" . $proceso . ") + 1 AS last_id "
//                . "FROM " . $tabla;


//
//
//    $rs = $objDB->getRecord($query);
//
//    $last_id = "001";
//    if (isset($rs['last_id'])){
//        $last_id = str_pad($rs['last_id'], 3, "0", STR_PAD_LEFT);
//    }
//
//    $year = date("Y");
//
//    $estatus = array(
//        1 =>"Aceptada",
//        2 => "Valoración",
//        3 => "Auditoría",
//        4 => "Notificada",
//        5 => "Rechazada",
//        6 => "CGR",
//        7 => "Por soportes"
//    );
//
//    $disabled = '';
//    $readonly = '';
//    $boton = 'Guardar';

    /**
     * Este bloque maneja detalles y actualización
     */
//    if (isset($_GET['opcion'])){
//
//        echo "fuck";
//        if ($_GET['opcion'] == "detalles"){
//            $disabled = " disabled ";
//            $readonly = " readonly ";
//        }
//        if ($_GET['opcion'] == "actualizar"){
//            $boton = "Guardar cambios";
//
//        }
//
//
//
//
//        if (isset($_GET['id'])){
//            $id = $_GET['id'];
//
//            $query = "SELECT * "
//                . "FROM " . $tabla . " "
//                . "INNER JOIN ciudadanos "
//                . "ON " . $tabla .".id_ciudadano = ciudadanos.id_ciudadano "
//                . "WHERE " . $tabla .".id_" . $proceso . " = {$id}";
//        }
//        $rs = $objDB->getRecord($query);
//
//
//
//    }

    //$txtcedula = $_POST['txtcedula'];

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

                    $rsDen = $objDB->getRecord($queryDen); //devuelve 0 para $rsDen['total_denuncias'] cuando no hay denuncias registradas


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
    } // end of get_fullname

    function getMunicipios($id_estado){
        $objDB= new DBConexion();
        $objDB->execute("SET NAMES utf8");
        $query = "SELECT id_municipio, municipio FROM municipios WHERE id_estado = $id_estado";

        $rs = $objDB->getRecords($query);
        $numRows = $objDB->getNumRows($query);

        if ($numRows > 0){

            return $rs;
        }
        return "no hay";
    } // end of getMunicipios

    function getParroquias($id_municipio){
        $objDB= new DBConexion();
        $objDB->execute("SET NAMES utf8");
        $query = "SELECT id_parroquia, parroquia FROM parroquias WHERE id_municipio = $id_municipio";

        $rs = $objDB->getRecords($query);
        $numRows = $objDB->getNumRows($query);

        if ($numRows > 0){

            return $rs;
        }
        return "no hay";
    } // end of getParroquias

    function getComunidades($id_parroquia){
        $objDB= new DBConexion();
        $objDB->execute("SET NAMES utf8");
        $query = "SELECT id_comunidad, comunidad FROM comunidades WHERE id_parroquia = $id_parroquia
                  ORDER BY comunidad ASC";

        $rs = $objDB->getRecords($query);
        $numRows = $objDB->getNumRows($query);

        if ($numRows > 0){

            return $rs;
        }
        return "no hay";
    } // end of getComunidades




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

    if (isset($_GET['id_estado'])){
        $municipios = getMunicipios($_GET['id_estado']);
        echo json_encode($municipios);
    }

    if (isset($_GET['id_municipio'])){
        $parroquias = getParroquias($_GET['id_municipio']);
        echo json_encode($parroquias);
    }

    if (isset($_GET['id_parroquia'])){
        $comunidades = getComunidades($_GET['id_parroquia']);
        echo json_encode($comunidades);
    }

    function getTipoProceso($proceso) {
        $tipo = ucfirst($proceso);
        $tipo = substr($tipo, 0, 1);
        return $tipo;
    }

// }

 ?>
