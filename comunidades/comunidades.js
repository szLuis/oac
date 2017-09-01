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
    //Envia parametros de busca y carga de Municipios
    $("#estados").change(function () {
        var id_estado_seleccionado = $(this).val();
        
        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'id_estado': id_estado_seleccionado },
            type: 'GET',
            success: function (result) {
                $("#municipios").html("");
                $("#municipios").append('<option value="0">Seleccionar</option>');
                $.each(result, function (key, value) {
                    $("#municipios").append(` <option value="${value.id_municipio}">${value.municipio}</option>`);

                })
            },
            dataType: 'json'
            
        });
        return false;
    });

    $("#municipios").change(function() {
        var id_municipio_seleccionado = $(this).val();

        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'id_municipio': id_municipio_seleccionado },
            type: 'GET',
            success: function(result) {
                $("#parroquias").html("");
                $("#parroquias").append('<option value="0">Seleccionar</option>');
                $.each(result, function(key, value) {
                    $("#parroquias").append(` <option value="${value.id_parroquia}">${value.parroquia}</option>`);
                })

            },
            dataType: 'json'
        });

        return false;
    });

    $("#parroquias").change(function() {
        var id_parroquia_seleccionada = $(this).val();


        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'id_parroquia': id_parroquia_seleccionada },
            type: 'GET',
            success: function(result) {
                $("#comunidades").html("");
                $("#comunidades").append('<option value="0">Seleccionar</option>');
                if (result !== 'no hay') {
                    $.each(result, function(key, value) {
                        $("#comunidades").append(`<option value="${value.id_comunidad}">${value.comunidad}</option>`);
                    })
                }
                
            },
            dataType: 'json'
        });

        return false;
    });




})