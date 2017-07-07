<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
    /**
     * Consulto a la base de datos si se solicita detalles o actualizar
     */
    
    
    
    $proceso = $_GET['proceso'];
    if (isset($proceso)){
        
        
        if ($proceso==="solicitud"){
            $tabla = "solicitudes";
            $linkOne = "denuncia";
            $linkTwo = "reclamo";
        }else  if ($proceso==="denuncia"){
            $tabla = "denuncias";
            $linkOne = "solicitud";
            $linkTwo = "reclamo";
        }else if ($proceso==="reclamo"){
            $tabla = "reclamos";
            $linkOne = "solicitud";
            $linkTwo = "denuncia";
        }
        
    }
    
    include '../spoon/spoon.php';   
    $objDB= new DBConexion();
    
    /**
     * Verificar que el proceso a registrar tenga una atención con los mismos 
     * datos de las variables de sesión comunidad, ciudadano y usuario.
     * En caso negativo no se permitirá registar el proceso.
     * 
     * 
     */
    
    if ("grida"===$_GET['from']){
        $id_atencion = $_GET['id'];
        $id_ciudadano = $_SESSION['idCiudadano'];
        $id_usuario = $_SESSION['idUsuario'];
        $fecha_registro = date("Y-m-d")    ;
        $comunidad = $_SESSION['comunidad'];
        if (!isset($id_atencion,$id_ciudadano, $id_usuario,$comunidad)){
            exit("Los datos de la Atención no coinciden con los datos de la sesión "
                    . "actual; no se puede continuar. Si requiere soporte técnico, "
                    . "póngase en contacto con la Dirección Técnica.");
        }
        $sql = "SELECT comunidad, id_ciudadano, idusuario, fecha_registro "
                . "FROM atenciones "
                . "WHERE id_atencion = $id_atencion "
                . "AND id_ciudadano = $id_ciudadano "
                . "AND idusuario = $id_usuario "
                . "AND comunidad = '$comunidad'"
                . " AND fecha_registro = '$fecha_registro'"; 

        $numero_Atenciones = $objDB->getNumRows($sql);

        if ($numero_Atenciones === 0){
            exit("Los datos de la Atención no coinciden con los datos de la sesión "
                    . "actual; no se puede continuar. Si requiere soporte técnico, "
                    . "póngase en contacto con la Dirección Técnica.");
        }
    }
    
    
    /**
     * Fin de la verificación
     */
    
    
    
    $query = "SELECT MAX(id_" . $proceso . ") + 1 AS last_id "
                . "FROM " . $tabla;
    
    

        
    $rs = $objDB->getRecord($query);
    
    $last_id = "001";
    if (isset($rs['last_id'])){
        $last_id = str_pad($rs['last_id'], 3, "0", STR_PAD_LEFT);
    }
    
    $year = date("Y");

    $estatus = array(
        1 =>"Aceptada",
        2 => "Valoración",
        3 => "Auditoría",
        4 => "Notificada",
        5 => "Rechazada",
        6 => "CGR",
        7 => "Por soportes"
    );

    $disabled = '';
    $readonly = '';
    $boton = 'Guardar';
    
    /**
     * Este bloque maneja detalles y actualización
     */
    if (isset($_GET['opcion'])){
        $opcion_actualizar=$_GET['opcion'];
        
        if ($_GET['opcion'] == "detalles"){
            $disabled = " disabled ";
            $readonly = " readonly ";
        }
        if ($_GET['opcion'] == "actualizar"){
            $boton = "Guardar cambios";
            
        }
        
        


        if (isset($_GET['id_proceso'])){
            $id_proceso = $_GET['id_proceso'];
            
            $query = "SELECT * "
                . "FROM " . $tabla . " "
                . "INNER JOIN ciudadanos "
                . "ON " . $tabla .".id_ciudadano = ciudadanos.id_ciudadano "
                . "WHERE " . $tabla .".id_" . $proceso . " = {$id_proceso}";
        }
        $rs = $objDB->getRecord($query);
        
        
        
    }
    
    function getTipoProceso($proceso) {
        $tipo = ucfirst($proceso);
        $tipo = substr($tipo, 0, 1);
        return $tipo;
    } 
   
 ?>
<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Registre </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<link href="../jquery-ui-1.11.3.custom/jquery-ui.css" rel="stylesheet">

<script src="../jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="ajax.js"></script>
<!----><script src="../jquery-ui-1.11.3.custom/jquery-ui.js"></script>
<script src="../jquery-ui-1.11.3.custom/datepicker-es.js"></script>

<script>
 $(document).ready(function() {
        $('#txtcedula').tooltip({placement: 'right', container:'body'});
        $('#txtnombres').tooltip({placement: 'right', container:'body'});
        $('#txtapellidos').tooltip({container:'body'});
        $('#txtdireccion').tooltip({container:'body'});
        $('#txtcorreo').tooltip({container:'body'});
        $('#txttelefonos').tooltip({container:'body'});
        $('#txtnarracionhechos').tooltip({container:'body'});
        $('#txtobservaciones').tooltip({container:'body'});

        $.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

        $( "#dtpfechatopeentrega" ).datepicker({
            language: "es",
            formatDate: 'dd/mm/yyyy',
            buttonImage: "../images/Google_Calendar.png",
            buttonImageOnly: true,
            buttonText: "Seleccionar fecha",
            //beforeShowDay: $.datepicker.noWeekends,
            defaultDate: +20
       });  
       
       
           
   });  
   
   function mostrarFecha($opcion)
       {
           if ($opcion=='n')
               $('#fecha').hide();
           else
               $('#fecha').show();
       }
</script>

</head>

<body>
    <div class="container">
        <div class="row">
            
            
            
            <div style="background: url('../imagenes/banner_background.jpg') no-repeat; background-size: 100%" class="well well-sm">
                <div style="text-align: right;  font-size: 15px; color: white; font-weight: bold">
                    <?php echo utf8_encode($_SESSION['nomUsuario']) . "," ; ?> 
                    <a id ="footer" href= "../usuarios/logout.php">Salir</a> 
                </div>
                <h1 class="text-center"><span style="font-weight: bold; text-shadow: 1px 1px 3px black ;  color: white;">Oficina de Atención al Ciudadano</span></h1>
            </div>
            <?php if (("grida"!=$_GET['from'])){ ?>
            <a class="btn btn-default" href="../atenciones/index.php" role="button"><span class="glyphicon glyphicon-home"></span> Inicio</a>
            <a class="btn btn-default" href="proceso.php?proceso=<?php echo $linkOne; ?>" role="button"><?php echo $linkOne; ?></a>
            <a class="btn btn-default" href="proceso.php?proceso=<?php echo $linkTwo; ?>" role="button"><?php echo $linkTwo; ?></a>
            <?php } ?>
        </div>
        <br>
        <div id="result" >
        </div>
        <br>     
            <form class="form-horizontal" id="formdatosproceso" role="form" name="formdatosproceso" method="post" action="registrar_proceso.php">
<div class="row">
    <div class="col-md-2"></div>
<div class="col-md-8">
    <?php 
        $opcion = "Variable no asignada";
        $proceso = "undefined";
        if (isset($_GET['proceso'])){
            $proceso = $_GET['proceso'];
            $opcion =" de la " . $_GET['proceso'];
            $codigo = "#d9534f";
        }
        
        if ($opcion == " de la solicitud"){
            $codigo = "#5bc0de";
        }
        
        if ($opcion == " de la reclamo"){
                $opcion = " del reclamo";
                $codigo = "#d58512";
        }
        ?>
    
          
	
        <fieldset><legend><span style="color:<?php echo $codigo; ?>">Datos <?php echo $opcion; ?></span> </legend>
             <input name="txtproceso" type="hidden" value="<?php echo $proceso; ?>"/>
             <input name="txttabla" type="hidden" value="<?php echo $tabla; ?>"/>
             <input name="opcion" type="hidden" value="<?php echo $opcion_actualizar; ?>"/>
                <div class="form-group">    
                    <label for="txtcodigo" class="col-md-4 control-label">Código:</label>
                    <div class="col-md-8">
                        <input type="text" <?php if (isset($disabled)) echo $disabled; ?> readonly="true" name="txtcodigo" class="form-control" id="txtcodigo" 
                               value="<?php if (isset($_GET['opcion'])){
                                                echo $id_proceso;
                                            }else{
                                                if (isset($rs)){ 
                                                    echo getTipoProceso($proceso) . "-" . $last_id . "-" . $year ;
                                                
                                                }
                                            }
                                                
                                      ?>"  maxlength="12">
                        
                        
                    </div>
                </div>
                
                 <div class="form-group">    
                    <label for="txtnarracionhechos" class="col-md-4 control-label">Narración de los hechos:</label>
                    <div class="col-md-8">
                        <textarea name="txtnarracionhechos" class="form-control" rows="5" id="txtnarracionhechos" 
                                  data-toggle="tooltip" data-placement="right" <?php if (isset($readonly)) echo $readonly; ?>
                        title="Exponga las razones que conllevan a realizar la denuncia,
                        según sea el caso"><?php if (isset($rs)) echo $rs['narracion_hechos']; ?></textarea>
                    </div>
                </div>
             
                <div class="form-group">    
                    <label for="txtobservaciones" class="col-md-4 control-label">Observaciones:</label>
                    <div class="col-md-8">
                        <textarea name="txtobservaciones" class="form-control" rows="5" id="txtobservaciones" 
                                  data-toggle="tooltip" data-placement="right" <?php if (isset($readonly)) echo $readonly; ?>
                        title="Asente los requisitos que quedan por entregar"><?php if (isset($rs)) echo $rs['observaciones']; ?></textarea>
                    </div>
                </div>
                <?php if ($proceso!="atencion"): ?>
                 <div class="form-group">    
                    <label for="options" class="col-md-4 control-label">¿Presentar soportes?</label>
                    <div class="col-md-8">
                        <div class="radio">
                          <label class="col-md-6">
                              <input  type="radio" name="optionsRadios" id="optionsi" onchange="mostrarFecha('s');" checked value="s" <?php if (isset($disabled)) echo $disabled; ?> <?php if (isset($rs) && $rs['sustanciar']=='s') echo 'checked'; ?> >
                            Sí
                          </label> 

                          <label class="col-md-6">
                              <input  type="radio" name="optionsRadios" id="optionno" onchange="mostrarFecha('n');" value="n" <?php if (isset($disabled)) echo $disabled; ?> <?php if (isset($rs) && $rs['sustanciar']=='n') echo 'checked'; ?> >
                            No
                          </label>
                        </div>

                    </div>
                </div>
                
                
                <div class="form-group" id="fecha">    
                    <label for="dtpfechatopeentrega" class="col-md-4 control-label">Fecha tope de entrega:</label>

                    <div class="col-md-8">		
                        <input <?php if (isset($disabled)) echo $disabled; ?> name="dtpfechatopeentrega" class="form-control" type="text" 
                               id="dtpfechatopeentrega"  
                               value="<?php if (isset($rs)){
                                                $fecha = date_create_from_format("Y-m-d", $rs['fecha_tope_entrega']);
                                                $fecha  = date_format($fecha, "d/m/Y");
                                                echo $fecha;
                                            }
                                             
                                      ?>" size="50" maxlength="30"
                               placeholder="clic aquí para seleccionar fecha"
                               data-toggle="tooltip" data-placement="right"  title=""/>
                   </div>
               </div>
             <?php  endif; ?>

        
        </fieldset>
    <hr>
</div>
</div>
<div  class="row">
    <div class="col-md-offset-3 col-md-6 col-md-offset-3">
        <input type="submit" class="<?php if (isset($disabled)) echo $disabled; ?>btn btn-primary btn-lg btn-block"  name="Submit" <?php  if ($boton=="Guardar cambios") echo 'value="Guardar cambios"'; else echo 'value="Guardar"'; ?> />   
        
    </div>
</div>
</form>
</div>
    <br><br><br>
    
    
</body>
</html>
<?php } ?>