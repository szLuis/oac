$(document).ready(function() {
	
    $('#formdatos').submit(function()  {
            
            xdismissible='<button type="button" class="close" data-dismiss="alert" aria-label="Hide"><span aria-hidden="true">&times;</span></button></div>';
            // Enviamos el formulario usando AJAX
            $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),			

                    beforeSend: function(){
                    $('#result').html('Enviando...')
                    },	

                    success: function(msg){

                    if (msg == 1)
                    {     

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
                    }else{
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
        $('#formNuevaComunidad').submit(function()  {
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){
                            $('#result').html('Enviando...')
   			},	
                        
			success: function(msg){
                        if (msg > 0)
                        {     
                            $("#result").removeClass("alert-danger");
                            $('#result').addClass("alert-success");
                            $('#comunidad').html("");
                            $('#result').html("Los datos se han guardado exitosamente") 
                            .hide()
                            .fadeIn(1000) 
                    

                        }else{
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
        
        /*
         * Actualización del estatus del proceso
         */
        $('#formestatus').submit(function()  {
            
                
                xdismissible='<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){
     			$('#result').html('Enviando...')
   			},	
                        
			success: function(msg){
                        if (msg > 0)
                        {     
                            $("#estatus").val("");
                            $("#observacion").val("");
                            
                            divs = '<div  class="alert alert-success alert-dismissible" role="alert">';
                            reporte = '<a href="../index.php?opcion=' + $('#tipo_proceso').val() + '&id_ciudadano='+ msg + '">Volver</a>';
                            
                            $('#result').html(divs + "Los datos se han guardado exitosamente. Actualizando valores de la tabla..." + xdismissible) 
                            //.hide()
                            .fadeIn(1000) 
                                                location.reload(true);

                        }else{
                            divs = '<div  class="alert alert-warning alert-dismissible" role="alert">';
                            $('#result').html(divs + msg + xdismissible) 
                            //.hide()
                            .fadeIn(1000) 
                        }
			}
                        
		}); 		
                
		return false;
	});

    $('#updateatencion').submit(function()  {
            
                xdismissible='<button type="button" class="close" data-dismiss="alert" aria-label="Hide"><span aria-hidden="true">&times;</span></button></div>';
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){
     			$('#result').html('Enviando...')
   			},	
                        
			success: function(msg){
                             
                            if (msg > 0)
                            {     
                                $("#txtnarracionhechos").val("");
                                $("#txtobservaciones").val("");

                                divs = '<div  class="alert alert-success alert-dismissible" role="alert">';

                                $('#result').html(divs + "Los datos se han guardado exitosamente."+ xdismissible) 

                                .fadeIn(1000) 
                            }else{
                                divs = '<div  class="alert alert-warning alert-dismissible" role="alert">'
                                $('#result').html(divs + msg + xdismissible) 
                                .fadeIn(1000) 
                            }
			}
                        
		}); 		
                
		return false;
	});


	$('#formdatosproceso').submit(function()  {
            
                xdismissible='<button type="button" class="close" data-dismiss="alert" aria-label="Hide"><span aria-hidden="true">&times;</span></button></div>';
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){
     			$('#result').html('Enviando...')
   			},	
                        
			success: function(msg){
                             vector = msg.split(",");
                             id = vector[0];
                             proceso = vector[1];
                             tabla = vector[2];
                             
                        if (id > 0)
                        {     
                            $("#txtcedula").val("");
                            $("#txtapellidos").val("");
                            $("#txtnombres").val("");
                            $("#txtdireccion").val("");
                            $("#txttelefonos").val("");
                            $("#txtcorreo").val("");
                            $("#txtobservaciones").val("");
                            
                            divs = '<div  class="alert alert-success alert-dismissible" role="alert">';
                            reporte = '<a href="reporte_proceso.php?id='+ id + '&proceso=' + proceso + '&tabla=' + tabla + '">Ver reporte</a>';
                            
                            $('#result').html(divs + "Los datos se han guardado exitosamente. Clic para "+ reporte + xdismissible) 
                            
                            //.hide()
                            .fadeIn(1000) 
                        }else{
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
        
        
        	//buscar datos del usuario
	$('#bususu').submit(function()  {
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){
     			$('#resultado').html('Consultando...')
   			},			
			
			success: function(msg){
				$('#resultado').html(msg)
				.hide()
        		.fadeIn(1000)
				
			}
		}); 		
		return false;
	});
	
	
	//buscar datos del usuario
	$('#inisesusu').submit(function()  {
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){     			
                            $('#result').html('Iniciando...')                        
   			},			
			
			success: function(msg){
                            if (msg === '1')
                            {     
                                $('#result').html("Iniciando sesión...") 
                                .hide()
                                .fadeIn(1000) 
                                var url = "home.php";  
                                $(location).attr('href',url);  

                            }else{
                                $('#result').html(msg) 
                                .hide()
                                .fadeIn(1000) 
                            }
			},
                        
                        error: function(){                       
27                          .alert("Ha ocurrido un problema, disculpas por las molestias causadas.");
28}
		}); 		
		return false;
	});
        
        $('#btn_citizen').click(function()  {
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: 'actualizar_ciudadano.php',
			data: $('#formdatos').serialize(),			
			
			beforeSend: function(){
     			$('#result').html('Enviando...')
   			},			
			
			success: function(msg){
                        if (msg === '1')
                        { 
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
                        }else{
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
	
	
	$('#actusu').submit(function()  {
		// Enviamos el formulario usando AJAX
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),			
			
			beforeSend: function(){
     			$('#result').html('Enviando...')
   			},			
			
			success: function(msg){
				$('#result').html(msg)
				//.append("<p>We will be in touch soon.</p>")
        		.hide()
        		.fadeIn(1000)
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
        
        
        $('#myModal').on('show.bs.modal', function (event) {
            var link = $(event.relatedTarget) // Button that triggered the modal
            var tabla = link.data('tabla') // Extract info from data-* attributes
            var proceso = link.data('proceso')
            var id_proceso = link.data('id_proceso')
            var codigo_proceso = link.data('codigo')
            
            $.ajax({
			type: 'POST',
			url: 'ajax_detalles_procesos.php',
			data:{'tabla' : tabla, 'proceso' : proceso , 'codigo_proceso' : codigo_proceso, 'id_proceso' : id_proceso},			
			
			
			success: function(result){
                            $('#myModalLabel').text(proceso.toUpperCase() + ': #' + codigo_proceso)
                            $('#myModalBody').html(result);
			}
//                        ,
//                        dataType: 'json'
		}) 		
        });
        
        $('#cambiarEstatus').on('show.bs.modal', function (event) {
            var link = $(event.relatedTarget) // Button that triggered the modal
            $("#codigo_proceso").val(link.data('codigo_proceso'))
            $("#tipo_proceso").val(link.data('tipo_proceso'))
            $("#id_proceso").val(link.data('id_proceso'))
            $("#tabla").val(link.data('tabla')) // Extract info from data-* attributes
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
                url: "ajax.php",
                data: {'txtcedula': cedula.val()},
                        type: 'post',
                success: function(result) {
    
                if(result.id_ciudadano && result.apellidos && result.nombres && 
                   result.correo && result.direccion && result.telefonos) {
                    if (result.denuncias > '0'){
                        $("#span_denuncias").html(result.denuncias);
                        $("#linkdenuncias").attr('href','../index.php?opcion=denuncia&id_ciudadano='+result.id_ciudadano);
                    }else{
                        $("#span_denuncias").html(0);
                        $("#linkdenuncias").attr('href','#');
                    }
                
                    if (result.solicitudes > '0'){
                        $("#span_solicitudes").html(result.solicitudes);
                        $("#linksolicitudes").attr('href','../index.php?opcion=solicitud&id_ciudadano='+result.id_ciudadano);
                    }else
                    {
                        $("#span_solicitudes").html(0);
                        $("#linksolicitudes").attr('href','#');
                    }
                
                    if (result.reclamos > '0'){
                        $("#span_reclamos").html(result.reclamos);
                        $("#linkreclamos").attr('href','../index.php?opcion=reclamo&id_ciudadano='+result.id_ciudadano);
                    }else
                    {
                        $("#span_reclamos").html(0);
                        $("#linkreclamos").attr('href','#');
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
            },
            dataType: 'json'
            });
        });
        
        
        //***************************************
	// Creamos el evento change para detectar el elemento elegido del combo Comunidad
	$("#comunidad").change(function () {
//		if ($(this).val() > 5){};
                    if ($(this).val()!='0'){
                        $("#nueva_comunidad").attr('href','nueva_comunidad.php?id_comunidad='+$(this).val()+'&comunidad='+$("option:selected", this).text());
                    }else{
                        $("#nueva_comunidad").attr('href','nueva_comunidad.php');
                    }
                    $.ajax({
                            url: "ajax.php",
                            data: {'comunidad': $(this).val()},
                            type: 'post',
                            success: function(result) {
                                
                                if (result.denuncias > '0'){
                                    $("#span_denuncias").html(result.denuncias);
                                    $("#linkdenuncias").attr('href','../index.php?opcion=denuncia');
                                }else{
                                    $("#span_denuncias").html(0);
                                    $("#linkdenuncias").attr('href','#');
                                }

                                if (result.solicitudes > '0'){
                                    $("#span_solicitudes").html(result.solicitudes);
                                    $("#linksolicitudes").attr('href','../index.php?opcion=solicitud');
                                }else
                                {
                                    $("#span_solicitudes").html(0);
                                    $("#linksolicitudes").attr('href','#');
                                }

                                if (result.reclamos > '0'){
                                    $("#span_reclamos").html(result.reclamos);
                                    $("#linkreclamos").attr('href','../index.php?opcion=reclamo');
                                }else
                                {
                                    $("#span_reclamos").html(0);
                                    $("#linkreclamos").attr('href','#');
                                }



                                $("#totalprocesos").hide()
                                        .fadeIn(1000);

                            
                        },
                        dataType: 'json'
                    });
                
		return false;
	});	
	
	
	
})		  
