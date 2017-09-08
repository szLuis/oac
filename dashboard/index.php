<?php
session_start();
if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/index.php");
    
}else{

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
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
    google.load("visualization", "1", {packages:['corechart', 'controls']});
//    google.load('visualization', '1', {'packages':['controls']});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
          var link;
          link= "getProcessChartData.php?year=" + $("#year").val();
          if ($("#atenciones").is(':checked')){
              link= "getProcessChartData.php?year=" + $("#year").val() + "&atenciones=" + "atenciones" ;
          }
      var jsonData = $.ajax({
         
          url: link,
          dataType:"json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      var options = {
        title: 'Procesos Registrados',
        pieHole: 0.4,
        legend: 'left'
      };
      
      var chart = new google.visualization.PieChart(document.getElementById('piechart_hole'));
      
$('#column_chart_procesos_fecha').html('');
$('#column_chart_usuarios').html('');
$('#column_chart_estatus').html('');
        //handler del piehole
      function selectHandler() {
        var selectedItem = chart.getSelection()[0];
        if (selectedItem) {
          var value = data.getValue(selectedItem.row, 0);
          
            //
            //gráfico de denuncias por FECHA
            //
            var jsonDataPorFecha = $.ajax({
            url: "getProcesoPorFechaChartData.php?year=" + $("#year").val(),
            data: {'tipoProceso' : value},
            dataType:"json",
            async: false
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var dataProcesoPorFecha = new google.visualization.DataTable(jsonDataPorFecha);
            
            var registro = ' registradas ';
                if (value === 'Reclamos'){
                    registro = ' registrados ';
                }

            var optionsProcesosFecha = {
              title: value + registro +' por mes',
              legend: {position: 'right'},
            };
            
            var chartProcesosFecha = new google.visualization.ColumnChart(document.getElementById('column_chart_procesos_fecha'));
            $('#column_chart_usuarios').html('');


            //
            //fin gráfico de denuncias por FECHA
            //
            
            function selectHandlerFecha(){
                var selectedItem2 = chartProcesosFecha.getSelection()[0];
                if (selectedItem2) {
                  var mes = dataProcesoPorFecha.getValue(selectedItem2.row, 0);
                  if (value === 'Reclamos'){
                      proceso = 'reclamo';
                  }else  if (value === 'Solicitudes'){
                      proceso = 'solicitud';
                  }else if (value === 'Denuncias'){
                      proceso = 'denuncia';
                  }else{
                      proceso = 'atencion';
                  }
                    //
                    //gráfico de denuncias por USUARIO
                    //
                    var jsonData2 = $.ajax({
                    url: "getUserChartData.php?year=" + $("#year").val(),
                    data: {'tipoProceso' : value, 'mes': mes},
                    dataType:"json",
                    async: false
                    }).responseText;
                    
                    $('#column_chart_estatus').html('');
                    // Create our data table out of JSON data loaded from server.
                    var data2 = new google.visualization.DataTable(jsonData2);

                    var registro = ' registradas ';
                        if (value === 'Reclamos'){
                            registro = ' registrados ';
                        }
//
                    var options2 = {
                      title: value + registro +' por Funcionario para el mes de ' + mes,
                    };
//                    var donutRangeSlider = new google.visualization.ControlWrapper({
//                              'controlType': 'NumberRangeFilter',
//                              'containerId': 'filter_div',
//                              'options': {
//                                'filterColumnLabel': 'Total por funcionario'
//                              }
//                            });
                    dataview = new google.visualization.DataView(data2);
                    dataview.hideColumns([2]);
                            
                    var chart2 = new google.visualization.ColumnChart(document.getElementById('column_chart_usuarios'));
//                    var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_div'));
//                    var chart2 = new google.visualization.ChartWrapper({
//                            'chartType': 'PieChart',
//                            'containerId': 'column_chart',
//                            'options': {
//                              'width': 1024,
//                              'height': 460,
//                              'pieSliceText': 'value',
//                              'legend': 'left',
//                              'title': value + registro +' por Usuario'
//                            }, 'view': {'columns': [0, 1]}
//                          });
//                          dashboard.bind(donutRangeSlider, chart2);
//                          dashboard.draw(data2);
                        
                    //
                    //fin gráfico de denuncias por fecha
                    //
                    
                    function selectHandlerUsuario(){
                        var selectedItem2 = chart2.getSelection()[0];
                        if (selectedItem2) {
                          var id_usuario = data2.getValue(selectedItem2.row, 2);
                          var nombre_usuario = data2.getValue(selectedItem2.row, 0);
                          if (value === 'Reclamos'){
                              proceso = 'reclamo';
                          }else  if (value === 'Solicitudes'){
                              proceso = 'solicitud';
                          }else if (value === 'Denuncias'){
                              proceso = 'denuncia';
                          }else{
                              proceso = 'atencion';
                          }
                          
                        if (value === 'Atenciones'){ //Se genera listado de atenciones
                                $('#column_chart_estatus').hide();
                                $('#nombre_proceso').html(value);
                                $('#procesos').dataTable({

                                        destroy: true,
                                        "language": {
                                            "url": "../datatables/Spanish.json"
                                        },
                                        "pageLength": 10  ,
                                        bFilter: false, bInfo: false,"paging":   true, "ordering": true,
                                        
                                        // "columnDefs": [    { "width": "10%", "targets": 0 }, 
                                        //                 { "width": "35%", "targets": 1 }, 
                                        //                 { "width": "12%", "targets": 4 }, 
                                        //                 { "width": "5%", "targets": 5 },
                                        //                 { "searchable": false, "targets": [1,2,3,4,5] }],
                                                        
                                        "ajax":{ "url":"datatable_json.php?year=" + $("#year").val(),
                                                "data": {'tipoProceso' : proceso,'mes': mes, 'id_usuario': id_usuario},
                                                "dataType": "json",
                                                
                                            }
                                        ,

                                        "columns": [
                                            { "data": "codigo" },
                                            { "data": "observaciones" },
                                            { "data": "estatus" },
                                            { "data": "ciudadano" },
                                            { "data": "fecha" },
                                            { "data": "accion" }
                                        ]
                                       
                                });


        //                     $.ajax({
        //                     url: "datatable_json.php?year=" + $("#year").val(),
        //                     data: {'tipoProceso' : proceso,'mes': mes, 'id_usuario': id_usuario},
        //                     type: 'GET',

        //                     success: function(msg){
        // //                                $('#result').attr('class','alert alert-success')
        //                                 $('#column_chart_estatus').hide();
        //                                 $('#nombre_proceso').html(value)
        //                                 $('#result').html(msg) 
                                        

        //                                 .hide()
        //                                 .fadeIn(1000) 
        //                         }

        //                     });
        //                     return false;
                        }
                        else{
                            //
                            //gráfico de denuncias por ESTATUS
                            //
                            $('#column_chart_estatus').show();
                            var jsonDataEstatus = $.ajax({
                            url: "getEstatusChartData.php?year=" + $("#year").val(),
                            data: {'tipoProceso' : value,'mes': mes, 'id_usuario':id_usuario},
                            dataType:"json",
                            async: false
                            }).responseText;

                            // Create our data table out of JSON data loaded from server.
                            var dataEstatus = new google.visualization.DataTable(jsonDataEstatus);

                            var registro = ' registradas por ';
                            if (value === 'Reclamos'){
                                registro = ' registrados por ';
                            }


                            var optionsEstatus = {
                              title: value + ' según Estatus,' + registro +  nombre_usuario + ' durante el mes de ' + mes,
                              legend: 'left'
                            };

                            var chartEstatus = new google.visualization.ColumnChart(document.getElementById('column_chart_estatus'));


                            //
                            //fin gráfico de denuncias por estatus
                            //

                            function selectHandlerEstatus(){
                            var selectedItem2 = chartEstatus.getSelection()[0];
                                if (selectedItem2) {
                                    var estatus = dataEstatus.getValue(selectedItem2.row, 0);
                                    if (value === 'Reclamos'){
                                        proceso = 'reclamo';
                                    }else  if (value === 'Solicitudes'){
                                        proceso = 'solicitud';
                                    }else if (value === 'Denuncias'){
                                        proceso = 'denuncia';
                                    }else{
                                        proceso = 'atencion';
                                    }
                                    $('#nombre_proceso').html(value);
                                    $('#procesos').dataTable({

                                        destroy: true,
                                        "language": {
                                            "url": "../datatables/Spanish.json"
                                        },
                                        "pageLength": 10  ,
                                        bFilter: false, bInfo: false,"paging":   false, "ordering": false,
                                        
                                        // "columnDefs": [    { "width": "10%", "targets": 0 }, 
                                        //                 { "width": "35%", "targets": 1 }, 
                                        //                 { "width": "12%", "targets": 4 }, 
                                        //                 { "width": "5%", "targets": 5 },
                                        //                 { "searchable": false, "targets": [1,2,3,4,5] }],
                                                        
                                        "ajax":{ "url":"datatable_json.php?year=" + $("#year").val(),
                                                "data": {'tipoProceso' : proceso,'mes': mes, 'id_usuario': id_usuario, 'estatus': estatus},
                                                "dataType": "json",
                                                
                                            }
                                        ,

                                        "columns": [
                                            { "data": "codigo" },
                                            { "data": "observaciones" },
                                            { "data": "estatus" },
                                            { "data": "ciudadano" },
                                            { "data": "fecha" },
                                            { "data": "accion" }
                                        ]
                                        
                                       
                                    });

                //                     $.ajax({
                //                     url: "datatable_json.php?year=" + $("#year").val(),
                //                     data: {'tipoProceso' : proceso,'mes': mes, 'id_usuario': id_usuario, 'estatus': estatus},
                //                     type: 'GET',

                //                     success: function(msg){
                                        
        
                // //                                $('#result').attr('class','alert alert-success')
                //                                 $('#nombre_proceso').html(value)

                //                                 //$('#result').html(msg) 

                //                                 .hide()
                //                                 .fadeIn(1000) 
                //                         $('.editar').removeAttr('href');
                //                          $('.estatus').removeAttr('href');

                //                         }

                //                     });
                                    return false;


                                }
                            }

                            google.visualization.events.addListener(chartEstatus, 'select', selectHandlerEstatus);

                            chartEstatus.draw(dataEstatus, optionsEstatus);
                            
                        }
                          
                //          alert('The user selected ' + value);
//                            $.ajax({
//                            url: "datatable.php",
//                            data: {'opcion' : proceso, 'usuario' : usuario},
//                            type: 'POST',
//
//                            success: function(msg){
//        //                                $('#result').attr('class','alert alert-success')
//                                        $('#nombre_proceso').html(value)
//                                        $('#result').html(msg) 
//
//                                        .hide()
//                                        .fadeIn(1000) 
//                                }
//
//                            });
//                            return false;
                        }
                    }
                    
                    google.visualization.events.addListener(chart2, 'select', selectHandlerUsuario);
      
                    chart2.draw(dataview, options2);
            
                    
                    
                }
            }
            
            
            
            //
            //procesos por fecha
            //
            google.visualization.events.addListener(chartProcesosFecha, 'select', selectHandlerFecha);
      
            chartProcesosFecha.draw(dataProcesoPorFecha, optionsProcesosFecha);
            //
            //
            //
        }
      }
      
      google.visualization.events.addListener(chart, 'select', selectHandler);
      
      chart.draw(data, options);
    }
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
            <div style="text-align: right;  font-size: 15px; color: white; font-weight: bold">
                <?php echo utf8_encode($_SESSION['nomUsuario']) . "," ; ?> 
                <a class="disabled" id ="footer" href= "../usuarios/logout.php">Salir</a> 
            </div>
            <h1 class="text-center"><span style="font-weight: bold; text-shadow: 1px 1px 3px black ; color: white;">Oficina de Atención al Ciudadano</span></h1>
            
        </div>
    </div>
    <a class="btn btn-default" href="../atenciones/index.php" role="button"><span class="glyphicon glyphicon-home"></span> Inicio</a>
    
    <div class="row">
        <div class="col-md-12">
            <h2>Dashboard </h2>
            <div class="row">
                <form class="form-inline" id="formestatus" role="form" name="formestatus" method="post" action="../proceso/procesar_cambiar_estatus.php">
                        
                        <div class="col-md-offset-3 col-md-4 form-group">
                            <label for="year" class="control-label">Seleccionar año:</label>
                                <select onchange="drawChart();" id="year" name="year" class="form-control">
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017" selected="true">2017</option>
                                </select>
                        </div>
                    
                        <div class="col-md-2 form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" onchange="drawChart();" id="atenciones" name="atenciones"> Atenciones
                                </label>
                            </div>
                        </div>
                </form>
            </div>
            <br>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default" id="piechart_hole"  style="width: 100%; height: 400px;"></div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-default" id="column_chart_procesos_fecha" style="width: 100%; height: 400px;"></div>
                </div> 

                <div class="col-md-12">
                    <div class="panel panel-default" id="column_chart_usuarios" style="width: 100%; height: 400px;"></div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-default" id="column_chart_estatus" style="width: 100%; height: 400px;"></div>
                </div> 
                
                
            </div>         
        </div>
    </div>
    
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
                      <div class="modal-body"  id="myModalBody">
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
                    <h2>Listado de <span id="nombre_proceso" <?php echo $estilo;?> > </span><hr></h2>
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
    <br><br><br>
        
        
</div>
</body>



<!-- bootstrap CSS -->
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- DataTables CSS -->
<link href="../datatables/dataTables.bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../jquery/jquery.dataTables.css">  

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../jquery/jquery-3.1.1.min.js"></script> 

<!-- bootstrap js-->
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script    src="../datatables/jquery.dataTables.min.js"></script>
<script    src="../datatables/dataTables.bootstrap.js"></script>

<script type="text/javascript" src="../proceso/ajax.js"></script>

<script>
    $(document).ready( function () {
        
        $('#procesos').dataTable({
            "language": {
                "url": "../datatables/Spanish.json"
            },
             "pageLength": 10  ,
             bFilter: false, bInfo: false,"paging":   false, "ordering": false,
            "columnDefs": [    { "width": "10%", "targets": 0 }, 
                               { "width": "35%", "targets": 1 }, 
                               { "width": "12%", "targets": 4 }, 
                               { "width": "5%", "targets": 5 },
                               { "searchable": false, "targets": [1,2,3,4,5] }]
        });
        
    } ); 
   
</script>
</html>
<?php 
}
?>