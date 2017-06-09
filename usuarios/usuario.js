$(document).ready(function() {
    $('#regusu').submit(function() {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),

            beforeSend: function() {
                $('#result').html('Enviando...')
            },

            success: function(msg) {

                if (msg == 1) {
                    $("#txtnombre").val("");
                    $("#txtmailpersonal").val("");
                    $("#txtpassword").val("");
                    $("#txtautentica").val("");
                    $('#result').attr('class', 'alert alert-success')
                    $('#result').html("Registro exitoso! <a href=\"formlogin.php\">Iniciar Sesión</a>")
                        .hide()
                        .fadeIn(1000)

                } else {
                    $('#result').removeClass('alert alert-success');
                    $('#result').addClass('alert alert-warning');
                    $('#result').html(msg)
                        .hide()
                        .fadeIn(1000)
                }

            }

        });

        return false;
    });


    //buscar datos del usuario
    $('#bususu').submit(function() {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),

            beforeSend: function() {
                $('#resultado').html('Consultando...')
            },

            success: function(msg) {
                $('#resultado').html(msg)
                    .hide()
                    .fadeIn(1000)
            }
        });
        return false;
    });


    //buscar datos del usuario
    $('#inisesusu').submit(function() {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),

            beforeSend: function() {
                $('#result').html('Iniciando sesión...')
                    .show()
                    .fadeIn(3000)
            },

            success: function(msg) {
                if (msg === '3') //operador
                {
                    var url = "../atenciones/index.php";
                    $(location).attr('href', url);

                } else if (msg === '1' || msg === '2') //admin o contralor
                {
                    var url = "../dashboard.php";
                    $(location).attr('href', url);

                } else {

                    $('#result').removeClass('alert alert-info');
                    $('#result').addClass('alert alert-warning');
                    $('#result').html(msg)
                        .hide()
                        .fadeIn(1000)
                }
            },

            error: function() {
                alert("Ha ocurrido un problema, disculpas por las molestias causadas.");
            }
        });
        return false;
    });


    $('#actusu').submit(function() {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),

            beforeSend: function() {
                $('#result').html('Enviando...')
            },

            success: function(msg) {
                $('#result').html(msg)
                    .hide()
                    .fadeIn(1000);
                $("#txtNomSesUsu").val("");
                $("#txtConUsu").val("");
                $("#txtRepConUsu").val("");
                $("#txtApeUsu").val("");
                $("#txtNomUsu").val("");
                $("#txtCorEleUsu").val("");
            }
        });
        return false;
    });

});