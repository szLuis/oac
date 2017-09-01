<?php
session_start();

if ($_SESSION['logged'] != true ){
    header("Location:../usuarios/");
    
}else{
    include '../spoon/spoon.php';   
    $objDB= new DBConexion();
    
    if (isset($_GET['id_comunidad'])){
        $id_comunidad = $_GET['id_comunidad'];
        $comunidad = $_GET['comunidad'];
    }
    
}
   
 ?>
<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Registre </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<script src="../jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
<script src="comunidades.js"></script>
</head>

<body >
    <div class="container">
        <div class="row">
            
            
            <div style="background: url('../imagenes/banner_background.jpg') no-repeat; background-size: 100%" class="well well-sm">
                <div style="text-align: right;  font-size: 15px; color: white; font-weight: bold">
                    <?php echo utf8_encode($_SESSION['nomUsuario']) . ", " ; ?> 
                    <a id ="footer" href= "../usuarios/logout.php">Salir</a> 
                </div>
                <h1 class="text-center"><span style="font-weight: bold; text-shadow: 1px 1px 3px black ;  color: white;">Oficina de Atención al Ciudadano</span></h1>
            </div>
                
            
            
        </div>
        <div class="row">
            <div id="result" hidden class="col-md-offset-3 col-md-6 col-md-offset-3 alert alert-success alert-dismissible" role="alert">

            </div>
        </div>
            
<form class="form-horizontal" id="formNuevaComunidad" role="form" name="formNuevaComunidad" method="post" action="addComunidad.php">
<div class="row">
    <div class="col-md-2">
        <div data-spy="affix" style="margin-top: 50px;" data-offset-top="60" data-offset-bottom="200">
            <div class="list-group">
                <a href="../atenciones/index.php" class="list-group-item">
                  <span class="glyphicon glyphicon-floppy-disk" ></span> Registrar atención
                </a>
                <a href="../index.php?opcion=denuncia" class="list-group-item"><span style="color: #d9534f;" class="glyphicon glyphicon-search" ></span><span style="color: #d9534f;"> Denuncias</span> </a>
                <a href="../index.php?opcion=solicitud" class="list-group-item"><span style="color: #5bc0de;" class="glyphicon glyphicon-search" ></span><span style="color: #5bc0de;"> Solicitudes</span></a>
                <a href="../index.php?opcion=reclamo" class="list-group-item"><span class="glyphicon glyphicon-search" style="color: #d58512;" ></span><span style="color: #d58512;"> Reclamos</span></a>
                <a href="../dashboard.php" class="list-group-item"><span class="glyphicon glyphicon-filter" ></span> Filtrar procesos</a>
                <a href="../atenciones/resumen_por_fecha.php" class="list-group-item"><span class="glyphicon glyphicon-filter" ></span> Filtrar por fecha</a>
            </div>
        </div>

    </div>
    <div class="col-md-8" style="margin-top: 50px;">
        
            <fieldset>
                <legend>Nueva comunidad</legend>
                <input name="id_comunidad" type="hidden" value="<?php if (isset($_GET['id_comunidad'])){ echo $id_comunidad;} ?>"/>

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
                                echo '<option value="'.$fila["id_estado"].'">'.$fila["estado"].'</option>';
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
                        <label for="comunidad" class="col-md-4 control-label">Nombre de la comunidad:</label>
                        <div class="col-md-8" >	   
                           <input name="comunidad" type="text" class="form-control" required id="comunidad" size="50" maxlength="50" 
                                  data-toggle="tooltip" data-placement="right" title="Ejemplo: La Hormiga"
                                  value="<?php if (isset($_GET['comunidad'])){ echo $comunidad;} ?>"/>
                        </div>   
                    </div>

                    
            </fieldset>
        <hr>
        <div  class="row">
            <div class="col-md-offset-3 col-md-6 col-md-offset-3">
                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Guardar"  name="Submit"/>   
            </div>
        </div>
    </div>
    
</div>

</form>
</div>
    <br><br><br>
    
</body>
<script type="text/javascript">
$(document).ready( function () {
    $('#municipios option[value="Barinas"]').attr("selected",true).trigger('change');
    $('#comunidad').tooltip({placement: 'right', container:'body'});
 }); 
</script>

</html>