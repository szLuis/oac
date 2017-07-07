<?php
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
    /**
     * Consulto a la base de datos si se solicita detalles o actualizar
     */
    
    if (!$id_atencion = filter_input(INPUT_GET, "id_proceso", FILTER_VALIDATE_INT))
    {
        exit("Error en los datos provistos para la atención");
    }
    
    include '../spoon/spoon.php';   
    
    $query = "SELECT * FROM atenciones WHERE id_atencion=$id_atencion";
    
    $objDB= new DBConexion();

        
    $rs = $objDB->getRecord($query);
    
    
//    $year = date("Y");
    
    
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
<script type="text/javascript" src="atenciones.js"></script>
<!----><script src="../jquery-ui-1.11.3.custom/jquery-ui.js"></script>
<script src="../jquery-ui-1.11.3.custom/datepicker-es.js"></script>

<script>
 $(document).ready(function() {
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
            
            <a class="btn btn-default" href="listado_atenciones.php" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Atrás</a>
            
        </div>
        <br>
        <div id="result" >

        </div>
        <br>     
            <form class="form-horizontal" id="updateatencion" role="form" name="updateatencion" method="post" action="actualizar_atencion.php">
<div class="row">
    <div class="col-md-2"></div>
<div class="col-md-8">
	
        <fieldset><legend><span style="color:<?php echo $codigo; ?>">Datos de la Atención</span> </legend>
             <input name="id_atencion" type="hidden" value="<?php echo $id_atencion; ?>"/>
                <div class="form-group">    
                    <label for="txtcodigo" class="col-md-4 control-label">Código:</label>
                    <div class="col-md-8">
                        <input type="text"  readonly="true" name="txtcodigo" class="form-control" id="txtcodigo" 
                               value="<?php if (isset($id_atencion)){
                                                echo $id_atencion;
                                            }
                                                
                                      ?>"  maxlength="12">
                        
                        
                    </div>
                </div>

                <div class="form-group">
                    <label for="atenciones" class="col-md-4 control-label">
                        Atención:
                    </label>
                    <div class="col-md-8" >	   
                        <select id="tipo_atencion" name="tipo_atencion" class="form-control">
                            <option value="0">Seleccionar...</option>
                            <option value="DJP Ingreso">DJP Ingreso</option>
                            <option value="DJP Cese">DJP Cese</option>
                            <option value="DJP Actualización">DJP Actualización</option>
                            <option value="Asesoría Instituciones Públicas">Asesoría Instituciones Públicas</option>
                            <option value="Asesoría Poder Popular">Asesoría Poder Popular</option>
                            <option value="SISROE">SISROE</option>
                            <option value="Exposición de motivos">Exposición de motivos</option>
                            <option value="Mesa de Trabajo Instituciones Públicas">Mesa de Trabajo Instituciones Públicas</option>                                
                            <option value="Mesa de Trabajo Poder Popular">Mesa de Trabajo Poder Popular</option>                                
                            <option value="Otros">Otros</option>
                        </select>
                    </div>   
                </div>
                
                 <div class="form-group">    
                    <label for="txtnarracionhechos" class="col-md-4 control-label">Tarea realizada:</label>
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