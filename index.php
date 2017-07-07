<?php
session_start();




if ($_SESSION['logged'] != true ){
    header("Location:usuarios/index.php");
    
}else{

    
define("Denuncia", 'style="color: #d9534f;"');
define("Solicitud", 'style="color: #5bc0de;"');
define("Reclamo", 'style="color: #d58512;"');
define("Atencion", 'style="color: #e55510;"');

if (isset($_GET['id_ciudadano'])){
    $id_ciudadano = $_GET['id_ciudadano'];
}

if (isset($_GET['usuario'])){
    $usuario = $_GET['usuario'];
}


if (isset($_GET['opcion'])){
    $tipo_proceso = strtolower($_GET['opcion']);
    $proceso = $_GET['opcion'];
}else{
    header("Location:proceso/index.php");
}


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

switch ($tipo_proceso) {
    case "denuncia":
        $tabla = "denuncias";
        $estilo = constant("Denuncia");
        break;
    case "solicitud":
        $tabla = "solicitudes";
        $estilo = constant("Solicitud");
        break;
    case "reclamo":
        $tabla = "reclamos";
        $estilo = constant("Reclamo");
        break;
    case "atencion":
        $tabla = "atenciones";
        $estilo = constant("Atencion");
        break;
//    default:
//        $estilo = 'style="color: #000000;"';
//        break;
}


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
<!-- DataTables CSS -->
<link href="datatables/dataTables.bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="jquery/jquery.dataTables.css">  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="jquery/jquery-3.1.1.min.js"></script>  
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script    src="datatables/jquery.dataTables.min.js"></script>
<script    src="datatables/dataTables.bootstrap.js"></script>


<script type="text/javascript" src="proceso/ajax.js"></script>

<style>
    table.display {
        margin: 0 auto;
        width: 100%;
        clear: both;
        border-collapse: collapse;
        table-layout: fixed;
        word-wrap:break-word;
    }
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
        <a class="btn btn-default" href="./atenciones/index.php" role="button"><span class="glyphicon glyphicon-home"></span> Inicio</a>
            <a class="btn btn-default" href="index.php?opcion=<?php echo $linkOne; ?>" role="button"><?php echo $linkOne; ?></a>
            <a class="btn btn-default" href="index.php?opcion=<?php echo $linkTwo; ?>" role="button"><?php echo $linkTwo; ?></a>
            
                 
        <div class="row">
        <div class="col-md-12 ">
            <!-- Modal -->
                <div class="modal fade" id="cambiarEstatus" tabindex="-2" role="dialog" aria-labelledby="cambiarEstatusLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="cambiarEstatusLabel"><?php echo ucfirst($tipo_proceso); ?> - Cambiar estatus</h4>
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
                                            <!--<input name="id_citizen" type="hidden" value="<?php // echo $id_citizen; ?>"/>-->
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
                </div>
            
            
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
                    <h2>Listado de <span <?php echo $estilo;?> > <?php echo $tabla; ?></span><hr></h2>
                    <br><br><br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <table id="procesos" class="display" tableborders="1"  cellborders="1" cellspacing="0" >
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Observaciones</th>
                                <th>Estatus</th>
                                <th>Ciudadano</th>
                                <th>Resolver antes de</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        
                        <tbody>
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
<script>
    $(document).ready( function () {

        $('#myModal').on('show.bs.modal', function(event) {
            var link = $(event.relatedTarget) // Button that triggered the modal
            var tabla = link.data('tabla') // Extract info from data-* attributes
            var proceso = link.data('proceso')
            var id_proceso = link.data('id_proceso')
            var codigo_proceso = link.data('codigo')
            var url = link.data('url')
            $.ajax({
                type: 'POST',
                url: 'dashboard/ajax_detalles_procesos.php',
                data: { 'tabla': tabla, 'proceso': proceso, 'codigo_proceso': codigo_proceso, 'id_proceso': id_proceso },


                success: function(result) {
                        $('#myModalLabel').text(proceso.toUpperCase() + ': #' + codigo_proceso)
                        $('#myModalBody').html(result);
                    }
                    //                        ,
                    //                        dataType: 'json'
            })
        });
        $('#procesos').dataTable({
            "serverSide": false,
            "ajax": "dashboard/datatable_json.php?opcion=<?php echo $proceso; ?>"   ,
            "columns": [
                            { "data": "codigo" },
                            { "data": "observaciones" },
                            { "data": "estatus" },
                            { "data": "ciudadano" },
                            { "data": "fecha" },
                            { "data": "accion" }
                        ],
            "language": {
                "url": "datatables/Spanish.json"
            },
            "order": [[ 0, "desc" ]],
            
             "pageLength": 10  ,
            "columnDefs": [    { "width": "10%", "targets": 0 }, 
                               { "width": "35%", "targets": 1 }, 
                               { "width": "12%", "targets": 4 }, 
                               { "width": "10%", "targets": 5 }, 
                
                               { "searchable": false, "targets": [1,2,3,4,5] }]
        });
    } );
</script>
<?php 
}
?>