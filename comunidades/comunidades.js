$(document).ready(function() {

    /*
     * Guardar nueva comunidad
     */
    $('#formNuevaComunidad').submit(function() {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),

            beforeSend: function() {
                $('#result').html('Enviando...')
            },

            success: function(msg) {
                if (msg > 0) {
                    $("#result").removeClass("alert-danger");
                    $('#result').addClass("alert-success");
                    $('#comunidad').html("");
                    $('#result').html("Los datos se han guardado exitosamente")
                        .hide()
                        .fadeIn(1000)


                } else {
                    $("#result").removeClass("alert-success");
                    $('#result').addClass("alert-danger");
                    $('#result').html(msg)
                        .hide()
                        .fadeIn(1000)
                }
            }

        });

        return false;
    });


    //***************************************
    // Creamos el evento change para detectar el elemento elegido del combo Comunidad
    $("#municipios").change(function() {
        var municipio_seleccionado = $(this).val();


        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'municipio': municipio_seleccionado },
            type: 'GET',
            success: function(result) {
                $("#parroquia").html("");
                $("#parroquia").append('<option value="0">Seleccionar</option>');
                $.each(result, function(key, value) {
                    $("#parroquia").append(` <option value="${value.id}">${value.parroquia}</option>`);

                })


            },
            dataType: 'json'
        });

        return false;
    });

    $("#parroquia").change(function() {
        var parroquia_seleccionada = $(this).val();


        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'parroquia': parroquia_seleccionada },
            type: 'GET',
            success: function(result) {
                $("#comunidad").html("");
                $("#comunidad").append('<option value="0">Seleccionar</option>');
                $.each(result, function(key, value) {
                    $("#comunidad").append(`<option value="${value.id_comunidad}">${value.comunidad}</option>`);

                })


            },
            dataType: 'json'
        });

        return false;
    });




})