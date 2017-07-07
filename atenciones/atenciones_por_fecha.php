<?php
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
   
   
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
<!----><script src="../jquery-ui-1.11.3.custom/jquery-ui.js"></script>
<script src="../jquery-ui-1.11.3.custom/datepicker-es.js"></script>

<script>
 $(document).ready(function() {

        $.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

        $( "#fechainicial" ).datepicker({
            language: "es",
            formatDate: 'dd/mm/yyyy',
            buttonImage: "../images/Google_Calendar.png",
            buttonImageOnly: true,
            buttonText: "Seleccionar fecha"
            //beforeShowDay: $.datepicker.noWeekends,
//            defaultDate: +20
       });  
       
       $( "#fechafinal" ).datepicker({
            language: "es",
            formatDate: 'dd/mm/yyyy',
            buttonImage: "../images/Google_Calendar.png",
            buttonImageOnly: true,
            buttonText: "Seleccionar fecha",
            //beforeShowDay: $.datepicker.noWeekends,
            defaultDate: +20
       });  
       
       $("#filtrarsdbydate").submit(function () {
//		if ($(this).val() > 5){};
                    $.ajax({
                            url: "generar_xls_atenciones.php",
                            data: {'fechainicial': $("#fechainicial").val(),'fechafinal': $("#fechafinal").val() },
                            type: 'post',
                            success: function(result) {

//                                if (result.denuncias > '0'){
//                                    $("#span_denuncias").html(result.denuncias);
//                                }else{
//                                    $("#span_denuncias").html(0);
//                                }
//
//                                if (result.solicitudes > '0'){
//                                    $("#span_solicitudes").html(result.solicitudes);
//                                }else
//                                {
//                                    $("#span_solicitudes").html(0);
//                                }
//
//                                if (result.reclamos > '0'){
//                                    $("#span_reclamos").html(result.reclamos);
//                                }else
//                                {
//                                    $("#span_reclamos").html(0);
//                                }
//


                                $("#totalprocesos").hide()
                                        .fadeIn(1000);

                            
                        },
                        dataType: 'json'
                    });
                
		return false;
	});	
       
       
           
   });  
   

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
            
            <a class="btn btn-default" href="index.php" role="button"><span class="glyphicon glyphicon-home"></span> Inicio</a>
            
        </div>
        <br>
        <div id="result" >

        </div>
        <br>     
            <form class="form-horizontal" id="filtrarbydate" role="form" name="filtrarbydate" method="post" action="generar_xls_atenciones.php">
<div class="row">
    
    <div class="col-md-offset-2 col-md-8 col-md-offset-2">

            <fieldset><legend><span style="color:<?php echo $codigo; ?>">Seleccionar rango de fecha <?php echo $opcion; ?></span> </legend>

                <div class="form-group" id="fechainicial1">    
                    <label for="fechainicial" class="col-md-2 control-label">Fecha Inicial:</label>

                    <div class="col-md-10">		
                        <input name="fechainicial" class="form-control" type="text" 
                               id="fechainicial"  
                                size="50" maxlength="30"
                               placeholder="clic aquí para seleccionar fecha"
                               data-toggle="tooltip" data-placement="right"  title=""/>
                   </div>
                </div>

                <div class="form-group" id="fechafinal1">    
                        <label for="fechafinal" class="col-md-2 control-label">Fecha Final:</label>
                        <div class="col-md-10">		
                            <input  name="fechafinal" class="form-control" type="text" 
                                   id="fechafinal"  
                                    size="50" maxlength="30"
                                   placeholder="clic aquí para seleccionar fecha"
                                   data-toggle="tooltip" data-placement="right"  title=""/>
                       </div>
                   </div>


            </fieldset>
        <hr>
    </div>
</div>
<div  class="row">
    <div class="col-md-offset-4 col-md-4 col-md-offset-4">
        <input type="submit" class="btn btn-primary btn-lg btn-block"  name="Submit" value="Descargar atenciones"/>   
        
    </div>
</div>
</form>
</div>
    <br><br><br>
    
    
</body>
</html>
<?php } ?>