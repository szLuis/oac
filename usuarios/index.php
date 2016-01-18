<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Iniciar Sesión - sDenuncias</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<script type="text/javascript" src="../jquery/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="ajax.usuario.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<script>
    $(document).ready(function() {
        
   $('#txtusuario').tooltip({container:'body'});
    
});

</script>
<style>
    body{
        /*padding-top: 15%;*/
    }

    .wrapper input[type="text"] {
        position: relative; 
    }

    input { font-family: 'Play','FontAwesome'; } /* This is for the placeholder */
</style>
</head>

<body>


    <div class="container">
       <div class="row">
            <div class="col-md-offset-3 col-md-6 col-md-offset-3">
                <div class="page-header">
                     <h1 class="text-center">Oficina de Atención al Ciudadano</h1>
                     <h2 class="text-center"><span style="color: #d9534f;">Denuncias</span> · <span style="color: #5bc0de;">Solicitudes</span> · <span style="color: #f0ad4e;">Reclamos</span></h2>
                </div>
            </div>
       </div>
        <div class="row">
            <div class="col-md-offset-3 col-md-6 col-md-offset-3">
                <div id="result" hidden="true" class="alert alert-info" role="alert" ></div>
            </div>
        </div>
        <div class="row" style="padding-top: 5%;">
            <div class="col-md-offset-4 col-md-4 col-md-offset-4">
                <form id="inisesusu" class="form-signin" role="form" name="inisesusu" method="POST" action="login.php">
                    <fieldset>
                        <div class="form-group">    
                            <label for="txtusuario">Usuario: </label>
                            <input name="txtusuario" class="form-control" required type="text" id="txtusuario" size="50" maxlength="30" placeholder="&#xf0f0;" data-toggle="tooltip" data-placement="right" title="ejemplo: eosuna" />   
                        </div>
                        <div class="form-group">    
                            <label for="txtpassword">Contraseña:</label>
                            <input name="txtpassword" class="form-control" type="password"  required id="txtpassword" size="20" maxlength="16" placeholder="&#xf084;"/>
                        </div>
                        
                        <br>
                        
                        
                        <input type="submit" class="btn btn-success btn-lg btn-block"  name="Submit" value="Entrar" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>