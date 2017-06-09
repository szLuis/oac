$(document).ready(function() {

    $('#formdatos').submit(function() {

        xdismissible = '<button type="button" class="close" data-dismiss="alert" aria-label="Hide"><span aria-hidden="true">&times;</span></button></div>';
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

                    $("#txtcedula").val("");
                    $("#txtapellidos").val("");
                    $("#txtnombres").val("");
                    $("#txtdireccion").val("");
                    $("#txttelefonos").val("");
                    $("#txtcorreo").val("");
                    $("#txtobservaciones").val("");
                    $("#txtnarracionhechos").val("");

                    divs = '<div  class="alert alert-success alert-dismissible" role="alert">';
                    solicitud = '<a href="proceso.php?proceso=solicitud">Solicitud</a>';
                    denuncia = '<a href="proceso.php?proceso=denuncia">Denuncia</a>';
                    reclamo = '<a href="proceso.php?proceso=reclamo">Reclamo</a>';

                    $('#result').html(divs + "Los datos se han guardado exitosamente. ¿Registrar una " + solicitud + ", " + denuncia + " o " + reclamo + "?" + xdismissible)

                    //.hide()
                    .fadeIn(1000)
                } else {
                    //$("#result").removeClass("alert-success");
                    //$('#result').addClass("alert-warning");
                    divs = '<div  class="alert alert-warning alert-dismissible" role="alert">'
                    $('#result').html(divs + msg + xdismissible)
                        //.hide()
                        .fadeIn(1000)
                }

            }

        });

        return false;
    });

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



    $('#updateatencion').submit(function() {

        xdismissible = '<button type="button" class="close" data-dismiss="alert" aria-label="Hide"><span aria-hidden="true">&times;</span></button></div>';
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
                    $("#txtnarracionhechos").val("");
                    $("#txtobservaciones").val("");

                    divs = '<div  class="alert alert-success alert-dismissible" role="alert">';

                    $('#result').html(divs + "Los datos se han guardado exitosamente." + xdismissible)

                    .fadeIn(1000)
                } else {
                    divs = '<div  class="alert alert-warning alert-dismissible" role="alert">'
                    $('#result').html(divs + msg + xdismissible)
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
                    //.append("<p>We will be in touch soon.</p>")
                    //.hide()
                    /*.fadeIn(1500, function() {
          			$('#result').append("<img id='checkmark' src='../../images/check.png' />");
        		});*/
            }
        });
        return false;
    });




    $('#btn_citizen').click(function() {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: 'actualizar_ciudadano.php',
            data: $('#formdatos').serialize(),

            beforeSend: function() {
                $('#result').html('Enviando...')
            },

            success: function(msg) {
                if (msg === '1') {
                    $('#result').html('<div class="alert alert-success alert-dismissible" role="alert">Los datos del ciudadano se han actualizado exitosamente.</div>')
                        .hide()
                        .fadeIn(1000)

                    $("#result").removeClass("alert-danger");
                    $('#result').addClass("alert-success");

                    $('#citizen').hide()
                    id_ciudadano.val('');
                    apellidos.val('');
                    nombres.val('');
                    correo.val('');
                    direccion.val('');
                    telefonos.val('');
                } else {
                    $('#result').html('<div  class="alert alert-danger alert-dismissible" role="alert">' + msg + '</div>')
                        .hide()
                        .fadeIn(1000)

                    $("#result").removeClass("alert-success");
                    $('#result').addClass("alert-danger");
                }
            }
        });
        return false;
    });





    $('#myModal').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget) // Button that triggered the modal
        var tabla = link.data('tabla') // Extract info from data-* attributes
        var proceso = link.data('proceso')
        var id_proceso = link.data('id_proceso')
        var codigo_proceso = link.data('codigo')
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            //            var modal = $(this)
            //            modal.find('.modal-title').text('New message to ' + recipient)
        $.ajax({
            type: 'POST',
            url: 'detalles_atencion.php',
            data: { 'tabla': tabla, 'proceso': proceso, 'codigo_proceso': codigo_proceso, 'id_proceso': id_proceso },

            //			beforeSend: function(){
            //                            $('.modal-body').html('Consultando...')
            //   			},			

            success: function(result) {
                    $('#myModalLabel').text(proceso.toUpperCase() + ': #' + codigo_proceso)
                    $('#myModalBody').html(result);
                    //                            modal.find('.modal-body').html(result.narracion_hechos)
                    //                            $('.modal-body').html(result.narracion_hechos)
                    //                            .hide()
                    //                            .fadeIn(1000)
                    //.append("<p>We will be in touch soon.</p>")
                    //.hide()
                    /*.fadeIn(1500, function() {
                            $('#result').append("<img id='checkmark' src='../../images/check.png' />");
                    });*/
                }
                //                        ,
                //                        dataType: 'json'
        })
    });









    //carga de datos en formulario de ciudadanos que solicitan atención en la OAC

    var cedula = $('input[name="txtcedula"]'),
        id_ciudadano = $('input[name="txtid_ciudadano"]'),
        apellidos = $('input[name="txtapellidos"]'),
        nombres = $('input[name="txtnombres"]'),
        correo = $('input[name="txtcorreo"]'),
        direccion = $('input[name="txtdireccion"]'),
        telefonos = $('input[name="txttelefonos"]');

    $('#txtcedula').on('blur', function() {


        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'txtcedula': cedula.val() },
            type: 'post',
            dataType: 'json',
            success: function(result) {
                if (result.id_ciudadano && result.apellidos && result.nombres &&
                    result.correo && result.direccion && result.telefonos) {
                    if (result.denuncias > '0') {
                        $("#span_denuncias").html(result.denuncias);
                        $("#linkdenuncias").attr('href', '../index.php?opcion=denuncia&id_ciudadano=' + result.id_ciudadano);
                    } else {
                        $("#span_denuncias").html(0);
                        $("#linkdenuncias").attr('href', '#');
                    }

                    if (result.solicitudes > '0') {
                        $("#span_solicitudes").html(result.solicitudes);
                        $("#linksolicitudes").attr('href', '../index.php?opcion=solicitud&id_ciudadano=' + result.id_ciudadano);
                    } else {
                        $("#span_solicitudes").html(0);
                        $("#linksolicitudes").attr('href', '#');
                    }

                    if (result.reclamos > '0') {
                        $("#span_reclamos").html(result.reclamos);
                        $("#linkreclamos").attr('href', '../index.php?opcion=reclamo&id_ciudadano=' + result.id_ciudadano);
                    } else {
                        $("#span_reclamos").html(0);
                        $("#linkreclamos").attr('href', '#');
                    }



                    $("#procesos").hide()
                        .fadeIn(1000);
                    $('#citizen').show();
                    id_ciudadano.val(result.id_ciudadano);
                    apellidos.val(result.apellidos);
                    nombres.val(result.nombres);
                    correo.val(result.correo);
                    direccion.val(result.direccion);
                    telefonos.val(result.telefonos);
                } else {
                    $('#citizen').hide();
                    id_ciudadano.val('');
                    apellidos.val('');
                    nombres.val('');
                    correo.val('');
                    direccion.val('');
                    telefonos.val('');
                }
            }

        });
    });


    //***************************************
    // Creamos el evento change para detectar el elemento elegido del combo Comunidad
    $("#comunidad").change(function() {
        //		if ($(this).val() > 5){};
        if ($(this).val() != '0') {
            $("#nueva_comunidad").attr('href', '../comunidades/index.php?id_comunidad=' + $(this).val() + '&comunidad=' + $("option:selected", this).text());
        } else {
            $("#nueva_comunidad").attr('href', '../comunidades/index.php');
        }
        $.ajax({
            url: "../helpers/helpers.php",
            data: { 'comunidad': $(this).val() },
            type: 'post',
            success: function(result) {

                if (result.denuncias > '0') {
                    $("#span_denuncias").html(result.denuncias);
                    $("#linkdenuncias").attr('href', '../index.php?opcion=denuncia');
                } else {
                    $("#span_denuncias").html(0);
                    $("#linkdenuncias").attr('href', '#');
                }

                if (result.solicitudes > '0') {
                    $("#span_solicitudes").html(result.solicitudes);
                    $("#linksolicitudes").attr('href', '../index.php?opcion=solicitud');
                } else {
                    $("#span_solicitudes").html(0);
                    $("#linksolicitudes").attr('href', '#');
                }

                if (result.reclamos > '0') {
                    $("#span_reclamos").html(result.reclamos);
                    $("#linkreclamos").attr('href', '../index.php?opcion=reclamo');
                } else {
                    $("#span_reclamos").html(0);
                    $("#linkreclamos").attr('href', '#');
                }



                $("#totalprocesos").hide()
                    .fadeIn(1000);


            },
            dataType: 'json'
                // capturamos el valor elegido
                // Llamamos al archivo cargar.ups.php
                //				$.post("../usuarios/cambiar.ups.ganado.usuario.php",
                //				{ elegido: $(this).val() },				
                //				function(data){
                //					// Asignamos las nuevas opciones para el cmbUps
                //					$("#liTipGan").html('<strong>Ganado actual: </strong>' + data);								
                //				});        
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