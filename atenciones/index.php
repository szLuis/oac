<?php
session_start(); 

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
}else{
    $disabled = '';
    $readonly = '';
    $boton = 'Guardar';
    
    include '../spoon/spoon.php';   
    $objDB= new DBConexion();

    if (isset($_GET['opcion'])){
        if ($_GET['opcion'] == "detalles"){
            $disabled = " disabled ";
            $readonly = " readonly ";
        }
        if ($_GET['opcion'] == "actualizar"){
            $boton = "Guardar cambios";
            
        }
        
               
        /**
         * Consulto a la base de datos si se solicita detalles o actualizar
         */
        include '../spoon/spoon.php';   


        if (isset($_GET['id'])){
            $id_denuncia = $_GET['id'];
            
            $query = "SELECT * "
                . "FROM denuncias "
                . "INNER JOIN ciudadanos "
                . "ON denuncias.id_denunciante = ciudadanos.id_ciudadano "
                . "WHERE denuncias.id_denuncia = {$id_denuncia}";
        }else{
            $query = "SELECT MAX(id_denuncia) + 1 AS last_id_denuncia"
                . "FROM denuncias ";
        }

        $objDB= new DBConexion();

        
        $rs = $objDB->getRecord($query);
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
 <!--DataTables CSS--> 
<link rel="stylesheet" type="text/css" href="../jquery/jquery.dataTables.css">  

<!-- <script src="../jquery/jquery-1.11.3.min.js"></script> -->
<script src="../jquery/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../jquery/WebNotifications.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
 <!--DataTables--> 
<script type="text/javascript" charset="utf8" src="../jquery/jquery.dataTables.js"></script>
<script src="../jquery-ui-1.11.3.custom/jquery-ui.js"></script>
<script src="../jquery-ui-1.11.3.custom/datepicker-es.js"></script>
<script src="atenciones.js"></script>



</head>

<body >
    <div class="container">
        <div class="row">
            
            
            <div style="background: url('../imagenes/banner_background.jpg') no-repeat; background-size: 100%" class="well well-sm">
                <div style="text-align: right;  font-size: 15px; color: white; font-weight: bold">
                    <p> 
                        <?php 
                        echo utf8_encode($_SESSION['nomUsuario']) . ", " ;
                    ?> 
                    <a id ="footer" href= "../usuarios/logout.php">Salir</a> 
                    </p>
                </div>
                <h1 class="text-center"><span style="font-weight: bold; text-shadow: 1px 1px 3px black ;  color: white;">Oficina de Atención al Ciudadano</span></h1>
            </div>
             <!-- Modal -->
                <div class="modal fade" id="notificaciones" tabindex="-1" role="dialog" aria-labelledby="notificacionesLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="notificacionesLabel">Procesos próximos a ser rechazados</h4>
                      </div>
                      <div class="modal-body" id="notificacionesBody">
                          <div class="row">
                            <div class="col-md-12 ">
                                <table id="procesos" class="display" tableborders="1"  cellborders="1" cellspacing="0" >
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Observaciones</th>
                                            <th>Ciudadano</th>
                                            <th>Teléfonos</th>
                                            <th>Fecha tope</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultbody">

                                    </tbody>
                                </table>
                            </div>
                          </div>
                        
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
            
            
        </div>
        <br>
        <div id="result" >

        </div>
        <br>
            
<form class="form-horizontal" id="formdatos" role="form" name="formdatos" method="post" action="registrar_atencion.php">
<div class="row">
    <div class="col-md-2">
        <div data-spy="affix" style="margin-top: 50px;" data-offset-top="60" data-offset-bottom="200">
            <div class="list-group">
                <a href="./index.php" class="list-group-item active">
                  <span class="glyphicon glyphicon-floppy-disk" ></span> Registrar atención
                </a>
                <a href="./listado_atenciones.php" class="list-group-item"><span style="color: #428bca;" class="glyphicon glyphicon-search" ></span><span style="color: #428bca;"> Atenciones</span> </a>
                <a href="../index.php?opcion=denuncia" class="list-group-item"><span style="color: #d9534f;" class="glyphicon glyphicon-search" ></span><span style="color: #d9534f;"> Denuncias</span> </a>
                <a href="../index.php?opcion=solicitud" class="list-group-item"><span style="color: #5bc0de;" class="glyphicon glyphicon-search" ></span><span style="color: #5bc0de;"> Solicitudes</span></a>
                <a href="../index.php?opcion=reclamo" class="list-group-item"><span class="glyphicon glyphicon-search" style="color: #d58512;" ></span><span style="color: #d58512;"> Reclamos</span></a>
                <a href="../dashboard/index.php" class="list-group-item"><span class="glyphicon glyphicon-filter" ></span> Filtrar procesos</a>
                <a href="../dashboard/resumen_por_fecha.php" class="list-group-item"><span class="glyphicon glyphicon-filter" ></span> Filtrar por fecha</a>
                <a href="atenciones_por_fecha.php" class="list-group-item"><span class="glyphicon glyphicon-download" ></span> Atenciones</a>
		<!--<a href="encuesta.php" class="list-group-item"><span class="glyphicon glyphicon-list-alt" ></span><span style="color: #428bca;"> Encuesta</span></a>-->

            </div>
        </div>

    </div>
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
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"  ><a href="#dts_personales" data-toggle="tab" aria-controls="dts_personales" role="tab">Datos personales y de contacto</a></li>
                <li role="presentation" ><a href="#dts_de_contacto" data-toggle="tab" aria-controls="dts_de_contacto" role="tab" >Datos de la Atención</a></li>
            </ul>

 <!-- Tab panes -->
    <div class="tab-content">
            <div  role="tabpanel" class="tab-pane active" id="dts_personales">
                <br><br>
                <fieldset  >
                    <!--<legend>Datos personales y de contacto</legend>-->
                    <input name="txtproceso" type="hidden" value="<?php echo $proceso; ?>"/>
                    <input name="txtusermail" type="hidden" value="<?php echo $txtusermail; ?>"   id="txtusermail" />
                    <input name="txtid_ciudadano" type="hidden" value="<?php echo $txtuserkey; ?>"  id="txtid_ciudadano" />

                        <div class="form-group">
                            <label for="estados" class="col-md-4 control-label">
                                Estado:
                            </label>
                            <div class="col-md-8" >	   
                                <select id="estados" name="estados" class="form-control">
                                <option value="0">Seleccionar</option>
                                <?php
                                try{
                                    $objDB->execute("SET NAMES utf8");
                                    $results_estados = $objDB->getRecords("SELECT * FROM estados");
                                }

                                catch(Exception $e){
                                    $e->getMessage();
                                }
                                
                                foreach (  $results_estados as $fila )
                                {
                                    echo '<option value="'.$fila["id_estado"].'" >'.$fila["estado"].'</option>';
                                }
                                ?>                         
                                </select>
                                
                            </div>   
                        </div>

                        <div class="form-group">
                            <label for="municipios" class="col-md-4 control-label">
                                Municipio:
                            </label>
                            <div class="col-md-8" >	   
                                <select id="municipios" name="municipios" class="form-control">
                                <option value="0">Seleccionar</option>
                                
                                </select>
                            </div>   
                        </div>

                        <div class="form-group">
                            <label for="parroquias" class="col-md-4 control-label">
                                Parroquia:
                            </label>
                            <div class="col-md-8" >	   
                                <select id="parroquias" name="parroquias" class="form-control">
                                    <option value="0">Seleccionar</option>
                                </select>
                            </div>   
                        </div>

                        <div class="form-group">
                            <label for="comunidades" class="col-md-4 control-label">
                                <a id="nueva_comunidad" href="../comunidades/index.php">Comunidad:</a>
                            </label>
                            <div class="col-md-8" >	   
                                <select id="comunidades" name="comunidades" class="form-control">
                                    <option value="0">Seleccionar...</option>
                                    <?php
                                        
                                        $query = "SELECT id_comunidad, comunidad FROM comunidades ORDER BY comunidad ASC";
                                        $rs = $objDB->getRecords($query);
                                        foreach ($rs as $value) {
                                            echo "<option value=\"{$value['id_comunidad']}\">{$value['comunidad']}</option>";
                                        }
                                    ?>
                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ">San Rafael de Canaguá</option>
                                </select>
                            </div>   
                        </div>

                        <div class="form-group">
                            <label for="txtcedula" class="col-md-4 control-label">Cédula:</label>
                            <div class="col-md-8" >	   
                                <input name="txtcedula" type="text" class="form-control" required id="txtcedula" size="50" maxlength="30" 
                                    <?php if (isset($readonly)) echo $readonly; ?> data-toggle="tooltip" data-placement="right" title="Ejemplo: V - 16.235.655" 
                                    value="<?php if (isset($rs)) echo $rs['cedula']; ?>"/>
                            </div>   
                        </div>

                        <div class="form-group">
                            <label for="txtapellidos" class="col-md-4 control-label">Apellidos:</label>
                            <div class="col-md-8" >	   
                                <input name="txtapellidos" type="text" class="form-control" required id="txtapellidos" size="50" maxlength="30" 
                                    <?php if (isset($readonly)) echo $readonly; ?> data-toggle="tooltip" data-placement="right" 
                                    value="<?php if (isset($rs)) echo $rs['apellidos']; ?>" title="Ejemplo: Jiménez Castillo"/>
                            </div>   
                        </div>

                        <div class="form-group">
                            <label for="txtnombres" class="col-md-4 control-label">Nombres:</label>
                            <div class="col-md-8" >	   
                                <input name="txtnombres" type="text" class="form-control" required id="txtnombres" size="50" maxlength="30" 
                                    <?php if (isset($readonly)) echo $readonly; ?> data-toggle="tooltip" data-placement="right" 
                                    value="<?php if (isset($rs)) echo $rs['nombres']; ?>" title="Ejemplo: Migdalia Amarilis"/>
                            </div>   
                        </div>

                        

                        <div class="form-group">
                            <label for="txtdireccion" class="col-md-4 control-label">Dirección:</label>
                            <div class="col-md-8" >	   
                                <input name="txtdireccion" type="text" class="form-control" required id="txtdireccion" size="50" maxlength="100" 
                                    <?php if (isset($readonly)) echo $readonly; ?> data-toggle="tooltip" data-placement="right" 
                                    value="<?php if (isset($rs)) echo $rs['direccion']; ?>" title="Ejemplo: Calle Bolívar frente a la plaza Zamora, 5201, Barinas- Venezuela"/>
                            </div>   
                        </div>

                        <div class="form-group">   
                            <label for="txttelefonos" class="col-md-4 control-label">Teléfonos:</label> 
                            <div class="col-md-8">	
                                <input name="txttelefonos" type="text" class="form-control"  required id="txttelefonos" size="50" maxlength="60" 
                                    <?php if (isset($readonly)) echo $readonly; ?> data-toggle="tooltip" data-placement="right" 
                                    value="<?php if (isset($rs)) echo $rs['telefonos']; ?>" title="Ejemplo: 0416 - 465 9766"/>
                            </div>
                        </div>    

                        <div class="form-group">    
                            <label for="txtcorreo" class="col-md-4 control-label">Correo Electrónico: </label>
                            <div class="col-md-8">		
                                <input name="txtcorreo" class="form-control" required type="text" id="txtcorreo" 
                                        value="<?php if (isset($rs)) echo $rs['correo']; ?>" size="60" maxlength="60" 
                                        <?php if (isset($readonly)) echo $readonly; ?> data-toggle="tooltip" data-placement="right"  title="Ejemplo: migdalia_jimenez@gmail.com"/>   
                            </div>
                        </div>
                        <div id="citizen" hidden="true"  class="row">
                            <div class="col-md-offset-4 col-md-8">
                                <input type="submit" id="btn_citizen" class="btn btn-primary btn-lg btn-block" value="Actualizar"   name="btn_ciudadano"  />   
                            </div>
                        </div>


                </fieldset>
                </div>

                <div role="tabpanel" class="tab-pane" id="dts_de_contacto">
                    <br><br>
                    <fieldset>
                        <!--<legend>Datos de la Atención</span> </legend>-->
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
                                        <option value="Cambio de Clave">Cambio de Clave</option>
                                        <option value="Cambio de Usuario">Cambio de Usuario</option>
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
                                    <textarea name="txtnarracionhechos" class="form-control" maxlength="850"  rows="3" id="txtnarracionhechos" 
                                            data-toggle="tooltip" data-placement="right" <?php if (isset($readonly)) echo $readonly; ?>
                                    title="Exponga las razones que conllevan a realizar la atención,
                                    según sea el caso"><?php if (isset($rs)) echo $rs['narracion_hechos']; ?></textarea>
                                </div>
                            </div>


                            <div class="form-group">    
                                <label for="txtobservaciones" class="col-md-4 control-label">Observaciones:</label>
                                <div class="col-md-8">
                                    <textarea name="txtobservaciones" class="form-control" maxlength="500" rows="3" id="txtobservaciones" 
                                            data-toggle="tooltip" data-placement="right" <?php if (isset($readonly)) echo $readonly; ?>
                                    title="Asente las observaciones existentes"><?php if (isset($rs)) echo $rs['observaciones']; ?></textarea>
                                </div>
                            </div>

                            <div  class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <input type="submit" class="<?php if (isset($disabled)) echo $disabled; ?>btn btn-primary btn-lg btn-block"  name="Submit" <?php  if ($boton=="Guardar cambios") echo 'value="Guardar cambios"'; else echo 'value="Guardar"'; ?> />   
                                    
                                </div>
                            </div>

                    </fieldset>
                </div>
            </div>
        <hr>
    </div>
    <div class="col-md-2">
        
        <div id="totalprocesos" hidden="true">
            <ul class="list-group" style="margin-top: 55px;" >
                <li class="list-group-item">
                  <span id="span_denuncias" class="badge">0</span>
                  <a id="linkdenuncias" href="#">Denuncias</a>
                </li>
                <li class="list-group-item">
                  <span id="span_solicitudes" class="badge">0</span>
                  <a id="linksolicitudes" href="../index.php?opcion=solicitud">Solicitudes</a>
                </li>              
                <li class="list-group-item">
                  <span id="span_reclamos" class="badge">0</span>
                  <a id="linkreclamos" href="../index.php?opcion=reclamo">Reclamos</a>
                </li>
            </ul>
        </div>
    </div>
</div>

</form>
</div>
    <br><br><br>
    
</body>
<script type="text/javascript">
$(document).ready( function () {
    $('#estados option[value="5"]').attr("selected",true).trigger('change');
    // $('#municipios option[value="58"]').attr("selected",true).trigger('change');
    
        $('#procesos').dataTable({
            bFilter: false, bInfo: false,"paging":   false, "ordering": false,
            "language": {
                "url": "../datatables/Spanish.json"
            },
             "pageLength": 5  ,
            "columnDefs": [    { "width": "20%", "targets": 0 }, 
                               { "width": "40%", "targets": 1 }, 
                               { "width": "16%", "targets": 2 }, 
                               { "width": "12%", "targets": 3 }, 
                               { "width": "12%", "targets": 4 }]
        });
   

//    function showNotification() {}
      var denuncias,solicitudes, reclamos;
      $.ajax({
            type: 'POST',
            url: 'notificaciones.php',
           data:{'getNotificaciones' : "true"},			
            success: function(result){
                denuncias = result.denuncias;
                solicitudes = result.solicitudes;
                reclamos = result.reclamos;
                var notif = showWebNotification('OAC - Próximas a ser rechazadas' , denuncias + " Denuncias\n" + solicitudes + " Solicitudes\n" + reclamos + " Reclamos\n"  , '../imagenes/google_calendar.png', null,8000);
                 //handle different events
                 if (notif){
                    notif.addEventListener("show", Notification_OnEvent);
                    notif.addEventListener("click", Notification_OnEvent);
                 }
                    
//                     notif.addEventListener("close", Notification_OnEvent);
                    
                function Notification_OnEvent(event) {
                        //A reference to the Notification object
                        var notif = event.currentTarget;
                        notif.onclick=function(){
                            $('#notificaciones').modal('show');
                        }
                    }
            },
            error:function(result){
                $('#resultbody').html(result);
            },
            dataType: 'json'
        }) 
        
 
    $('#txtcedula').tooltip({placement: 'right', container:'body'});
    $('#txtnombres').tooltip({placement: 'right', container:'body'});
    $('#txtapellidos').tooltip({container:'body'});
    $('#txtdireccion').tooltip({container:'body'});
    $('#txtcorreo').tooltip({container:'body'});
    $('#txttelefonos').tooltip({container:'body'});
    $('#txtobservaciones').tooltip({container:'body'});
    $('#txtnarracionhechos').tooltip({container:'body'});
//    $('#txtcedula').popover('toggle');
    
    $.datepicker.setDefaults( $.datepicker.regional[ "es" ] );
   
    $( "#dtpnotificacion" ).datepicker({
        language: "es",
        formatDate: 'dd/mm/yyyy',
        buttonImage: "../images/Google_Calendar.png",
        buttonImageOnly: true,
        buttonText: "Seleccionar fecha",
        //beforeShowDay: $.datepicker.noWeekends,
        defaultDate: +20
   });  
   
   
   $('#notificaciones').on('show.bs.modal', function (event) {
        $.ajax({
            type: 'POST',
            url: 'notificaciones.php',
           data:{'tabla' : "tabla"},			
            success: function(result){
                $('#resultbody').html(result);
            },
            error:function(result){
                $('#resultbody').html(result);
            }
        }) 		
   });

});  

</script>

</html>
<?php } ?>