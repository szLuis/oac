<?php
session_start();




if ($_SESSION['logged'] != true ){
    header("Location:usuarios/index.php");
    
}else{

    
define("Atencion", 'style="color: #428bca;"');

$tipo_proceso = "atencion";
$tabla = "atenciones";


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Oficina de Atención al Ciudadano</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" 
      type="image/png" 
      href="imagenes/google_calendar.png" />
<!-- DataTables CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="jquery/jquery-1.11.3.min.js"></script>  

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="jquery/jquery.dataTables.css">
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="jquery/jquery.dataTables.js"></script> 
<!--<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <!--<link href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">-->


<!--<script    src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
<!--<script    src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>-->
<!--<script    src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>-->
<!--<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>-->

<script type="text/javascript" src="proceso/ajax.js"></script>

<script>
    $(document).ready( function () {

        
        $('#procesos').dataTable({
            "serverSide": false,
            "ajax": "datatable_atenciones.php",
            "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
            },
//            "order": [[ 5, "asc" ]],
            "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            { "sType": "date-uk" },
            null
            ],
            
            "pageLength": 10  ,
            "columnDefs": [    { "width": "10%", "targets": 0 }, 
                               { "width": "10%", "targets": 1 }, 
                               { "width": "10%", "targets": 2 }, 
                               { "width": "15%", "targets": 3 },
                               { "width": "12%", "targets": 4 }, 
                               { "width": "10%", "targets": 5 }, 
                               { "width": "10%", "targets": 6 } 
                //                               { "searchable": false, "targets": [1,2,3,4,5,6] }
                            ]
   } );
    } );   
    
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "date-uk-pre": function ( a ) {
                var ukDatea = a.split('/');
                return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
            },

            "date-uk-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "date-uk-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        } );
    
</script>
<style>
/*table.display {
margin: 0 auto;
width: 100%;
clear: both;
border-collapse: collapse;
table-layout: fixed;
word-wrap:break-word;
}*/


</style>

</head>
<body>
    <div class="container">
        <div class="row">
            <div style="background: url('./imagenes/banner_background.jpg') no-repeat; background-size: 100%" class="well well-sm">
                <div style="text-align: right;  font-size: 15px;  color: white; font-weight: bold">
                    <?php echo utf8_encode($_SESSION['nomUsuario']) . "," ; ?> 
                    <a id ="footer" href= "usuarios/logout.php">Salir</a> 
                </div>
                <h1 class="text-center"><span style="font-weight: bold; text-shadow: 1px 1px 3px black ;  color: white;">Oficina de Atención al Ciudadano</span></h1>
            </div>
        </div>
        <a class="btn btn-default" href="./proceso/index.php" role="button"><span class="glyphicon glyphicon-home"></span> Inicio</a>
                 
        <div class="row">
        <div class="col-md-12 ">
            <!-- Modal -->
<!--                <div class="modal fade" id="cambiarEstatus" tabindex="-2" role="dialog" aria-labelledby="cambiarEstatusLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="cambiarEstatusLabel"><?php // echo ucfirst($tipo_proceso); ?> - Cambiar estatus</h4>
                      </div>
                        <div class="modal-body" id="cambiarEstatusBody">
                          <div id="result" ></div>
                          <form class="form-horizontal" id="formestatus" role="form" name="formestatus" method="post" action="proceso/procesar_cambiar_estatus.php">
                            <div class="row">
                                <div class="col-md-2">


                                </div>

                                <div class="col-md-8">
                                        <fieldset>
                                            <legend></legend>
                                            <input name="tipo_proceso" id="tipo_proceso" type="hidden" />
                                            <input name="codigo_proceso" id="codigo_proceso" type="hidden" />
                                            <input name="id_citizen" type="hidden" value="<?php // echo $id_citizen; ?>"/>
                                            <input name="id_proceso" id="id_proceso" type="hidden" />
                                            <input name="tabla" id="tabla" type="hidden" />

                                                <div class="form-group">
                                                    <label for="estatus" class="col-md-4 control-label">Estatus:</label>
                                                    <div class="col-md-8" >	   
                                                        <select name="estatus" class="form-control">
                                                            <option value="Aceptada">Aceptada</option>
                                                            <option value="Valoración">Valoración</option>
                                                            <option value="Auditoría">Auditoría</option>
                                                            <option value="Notificada">Notificada</option>
                                                            <option value="Rechazada">Rechazada</option>
                                                            <option value="CGR">CGR</option>
                                                            <option value="Por soportes">Por soportes</option>
                                                        </select>
                                                    </div>   
                                                </div>

                                                <div class="form-group">    
                                                    <label for="observaciones" class="col-md-4 control-label">Observaciones:</label>
                                                    <div class="col-md-8">
                                                        <textarea name="observaciones" class="form-control" rows="3" id="observaciones" 
                                                                  data-toggle="tooltip" data-placement="right" 
                                                        title="Escriba alguna observación sobre el cambio de estatus"></textarea>
                                                    </div>
                                                </div>
                                        </fieldset>
                                    <hr>
                                </div>

                                <div class="col-md-2">

                                </div>

                            </div>
                            <div  class="row">
                                <div class="col-md-offset-3 col-md-6 col-md-offset-3">
                                    <input type="submit" class="btn btn-primary btn-lg btn-block"  name="Submit" value="Guardar cambios" />   

                                </div>
                            </div>
                            </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>-->
            
            
            <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Detalles del proceso</h4>
                      </div>
                        <div class="modal-body" id="myModalBody">
                        ...
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>

            <div class="row">
                <div class="col-md-12">
                    <h2>Listado de <span style="color: #428bca;" > Atenciones</span><hr></h2>
                    <br><br><br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <table id="procesos" class="table table-striped table-bordered" cellspacing="0" width="100%" >
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Narr. Hechos</th>
                                <th>Observaciones</th>                                
                                <th>Ciudadano</th>
                                <th>Comunidad</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Código</th>
                                <th>Narr. Hechos</th>
                                <th>Observaciones</th>                                
                                <th>Ciudadano</th>
                                <th>Comunidad</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php // include './datatable_atenciones.php'; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br><br><br>
        </div>
    </div>
    </div>
</body>


</html>

<?php 
}
?>