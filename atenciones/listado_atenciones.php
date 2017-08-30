<?php
session_start();




if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/index.php");
    
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
      href="../imagenes/google_calendar.png" />
<!-- DataTables CSS -->
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../jquery/jquery-1.11.3.min.js"></script>

<!-- DataTables CSS -->
<link href="../datatables/dataTables.bootstrap.css" rel="stylesheet">


<!-- DataTables JS -->
<script    src="../datatables/jquery.dataTables.min.js"></script>
<script    src="../datatables/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="atenciones.js"></script>

<script>
    $(document).ready( function () {

        
        $('#procesos').dataTable({
            "serverSide": false,
            "ajax": "datatable_atenciones_json.php",
            "language": {
                "url": "../datatables/Spanish.json"
            },
            "order": [[ 5, "asc" ]],
            "aoColumns": [
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
                               { "width": "10%", "targets": 5 }
                               
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
            <div style="background: url('../imagenes/banner_background.jpg') no-repeat; background-size: 100%" class="well well-sm">
                <div style="text-align: right;  font-size: 15px;  color: white; font-weight: bold">
                    <?php echo utf8_encode($_SESSION['nomUsuario']) . "," ; ?> 
                    <a id ="footer" href= "../usuarios/logout.php">Salir</a> 
                </div>
                <h1 class="text-center"><span style="font-weight: bold; text-shadow: 1px 1px 3px black ;  color: white;">Oficina de Atención al Ciudadano</span></h1>
            </div>
        </div>
        <a class="btn btn-default" href="./index.php" role="button"><span class="glyphicon glyphicon-home"></span> Inicio</a>
                 
        <div class="row">
        <div class="col-md-12 ">

        
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
                        <a class="btn btn-success pull-left" href="#" id="btn_imprimir_atencion_pdf" role="button"><strong>Imprimir</strong></a>
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
                                <th>Cédula</th>                                
                                <th>Ciudadano</th>
                                <th>Tipo Atención</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Código</th>
                                <th>Cédula</th>                                
                                <th>Ciudadano</th>
                                <th>Tipo Atención</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
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

<?php 
}
?>