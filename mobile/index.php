<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi" />
	<title>Awafrut || El sabor de Mamá</title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="apple-touch-icon" sizes="76x76" href="web-icon76x76.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="web-icon152x152.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="web-icon120x120.png" />
	<meta property="og:title" content="El sabor de Mamá"/>
	<meta property="og:url" content="http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/"/>
	<meta property="og:site_name" content="Awafrut. El sabor de Mamá"/>
	<meta property="og:image" content="http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/web-icon120x120.png"/>
	<meta name="description" content="En el verano, ¿qué tipo de mamá sos? Sobreprotectora, relajada, práctica, distraída, sociable… Contestá el test y descubrí cómo sos. ¡Podés ganar una Tablet!.">
	<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<script src="js/flowType.js"></script>
</head>
<?php include('src/functions.php'); ?>
<?php $_SESSION["usuario_id"] = NULL; ?>
<?php $preguntas = getPreguntas(); ?>
<body>
	<script>
	var resultado;
	
	$(document).ready(function(){
		changePage('home');
	});
	
	function changePage(id){
		$('.content').hide();
		$('.content#'+id).show();
		switch(id){
			case 'preguntas':
				initializePreguntas();
				break;
			case 'resultado':
				initializeResultado();
				break;
			case 'registro':
				initializeRegistro();
				break;
		}
	}
	
	function alignHeight(element){
		var height = element.height();
		var parentHeight = element.parent().height();
		element.css('padding-top',(parentHeight-height)/2)
	}
	
	/* Código preguntas */
	var preguntas = new Array();
	var current = 0;
	var answers = new Array();
	var timeout;
	
	function initializePreguntas(){
		window.addEventListener('orientationchange', OrientationChangePreguntas);
		cambiarPregunta();
		OrientationChangePreguntas();
		$('.respuesta').click(function(){
			$('.respuesta').removeClass('selected');
			$(this).addClass('selected');
			clearTimeout(timeout);
			answers[current] = $(this).attr('id');
			timeout = setTimeout(cambiarPregunta,1000);
		});
	}
			
	function OrientationChangePreguntas(){
		if(window.orientation !== undefined){
			switch(window.orientation) 
			{  
				case -90:
				case 90:
					$('.content#preguntas .texto h1').flowtype({fontRatio : 40, lineRatio : 1.45});
					$('.content#preguntas .respuestas#'+current+' .respuesta').flowtype({fontRatio : 30, lineRatio : 1.45});
					break; 
				default:
					$('.content#preguntas .texto h1').flowtype({fontRatio : 20, lineRatio : 1.2});
					$('.content#preguntas .respuestas#'+current+' .respuesta').flowtype({fontRatio : 20, lineRatio : 1.2});
					break; 
			}
		}else{
			$('.content#preguntas .texto h1').flowtype({fontRatio : 40, lineRatio : 1.45});
			$('.content#preguntas .respuestas#'+current+' .respuesta').flowtype({fontRatio : 60, lineRatio : 1.45});
		}
		var width = $('.numero').width();
		$('.numero').css('height',width);
		alignHeight($('.content#preguntas .texto h1'));
		alignHeight($('.content#preguntas .numero'));
	}	  
				
	function cambiarPregunta(){
		current++;
		$('.content#preguntas .respuestas').hide();
		if(typeof preguntas[current] != 'undefined'){
			$('.content#preguntas .texto h1').html(preguntas[current]);
			$('.content#preguntas .numero').css('background-image','url(images/awafrut/numeros/numeros-0'+current+'.jpg)');
			$('.content#preguntas .respuestas#'+current).show();
			OrientationChangePreguntas();
		}else{
			$('.loading').show();
			$('.content#preguntas .texto h1').html('Calculando...');
			$('.content#preguntas .numero').css('background-image','none');
			var height = $('.content#preguntas .fondo').height();
			$('.loading').css('height',height);
			$.post("src/functions.php?action=save", $.extend({}, answers), function(data){
				if(data != 'error'){
					resultado = JSON.parse(data);
					setTimeout(endPreguntas,2000);
				}
			});
		}
	}
	
	function endPreguntas(){
		changePage('resultado');
	}
	
	/* Código resultado */
	function initializeResultado(){
		showResult();
		window.addEventListener('orientationchange', OrientationChangeResultado);
		OrientationChangeResultado();
	}
	
	function showResult(){
		var nombreMadre = resultado.nombre;
		$('.content#resultado .texto').css('background-image','url(images/awafrut/madres/'+nombreMadre+'.jpg)');
		$('.content#resultado .fondo').css('background-image','url(images/awafrut/fondos/'+nombreMadre+'.jpg)');
		$('.content#resultado .fondo .resultado').addClass(nombreMadre);
		$('.content#resultado .fondo .resultado .text').html(resultado.descripcion);
	}
	
	function OrientationChangeResultado(){
		if(window.orientation !== undefined){
			switch(window.orientation) 
			{  
				case -90:
				case 90:
					$('.content#resultado .text p').flowtype({fontRatio : 40, lineRatio : 1.45});
					$('.content#resultado .text h1').flowtype({fontRatio : 30, lineRatio : 1.45});
					break; 
				default:
					$('.content#resultado .text p').flowtype({fontRatio : 30, lineRatio : 1.45});
					$('.content#resultado .text h1').flowtype({fontRatio : 20, lineRatio : 1.2});
					break; 
			}
		}else{
			$('.content#resultado .text p').flowtype({fontRatio : 40, lineRatio : 1.45});
			$('.content#resultado .texto h1').flowtype({fontRatio : 30, lineRatio : 1.45});
		}
		var height = $('.content#resultado .fondo').height();
		$('.content#resultado .datos').css('height',height*0.1);
		$('.content#resultado .botones').css('height',height*0.2);
	}
	
	/* Codigo registro */
	function initializeRegistro(){
		window.addEventListener('orientationchange', OrientationChangeRegistro);
		OrientationChangeRegistro();
		$('.enviar').click(function(){
			if(validateFields()){
				var data = {nombre:$('#nombre').val(),apellido:$('#apellido').val(),dni:$('#DNI').val(),email:$('#email').val(),es_madre:$('input[name=mama]:checked', '#registro').val()};
				$.post("src/functions.php?action=register", data, function(result){
					if(result == 'existe'){
						alert('El usuario ya existe');
					}else if(result == 'no login'){
						
					}else if(result == 'ok'){
						changePage('final');
					}
				});
			}
		});
	}
	
	function OrientationChangeRegistro()
	{
		if(window.orientation !== undefined){
			switch(window.orientation) 
			{
				case -90:
				case 90:
					$('.input').flowtype({fontRatio : 30});
					break; 
				default:
					$('.input').flowtype({fontRatio : 20});
					break; 
			}
		}else{
			$('.input').flowtype({fontRatio : 40});
		}
		setElementHeight($('.enviar'), $('.input'))
	}


	function setElementHeight(element1, reference){
		var referenceHeight = reference.outerHeight();
		element1.css('height',referenceHeight);
	}

	function validateFields(){
		var error = '';
		if(!$('#bases').attr('checked')){error = 'Debe aceptar las bases y condiciones';}
		if($('#nombre').val()==''){error = 'Debe ingresar su nombre';}
		if(!validateString($('#nombre').val())){error = 'El nombre ingresado contiene caracteres invalidos';}
		if($('#apellido').val()==''){error = 'Debe ingresar su apellido';}
		if(!validateString($('#apellido').val())){error = 'El apellido ingresado contiene caracteres invalidos';}
		if($('#DNI').val()==''){error = 'Debe ingresar su DNI';}
		if(!isNumber($('#DNI').val())){error = 'El DNI ingresado no es válido';}
		if($('#email').val()==''){error = 'Debe ingresar su email';}
		if(!IsEmail($('#email').val())){error = 'El email ingresado no es valido';}
		if(error == ''){
			return true;
		}else{
			displayErrors(error);
			return false;
		}
	}

	function validateString(string){
		re = /^[a-zA-Z0-9áéíóúÁÉÍÓÚ]*$/;
		var isValid = re.test(string);
		return isValid
	}

	function IsEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	function displayErrors(errors){
		alert(errors);
	}

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}
	</script>
	<div id="container">
		<div id="header"></div>
		<!-- Home -->
		<div class="content" id="home">
			<div class="texto"></div>
			<div class="fondo">
				<a class="participar" href="javascript:void(0);" onClick="changePage('preguntas');">
					<div class="tablet"></div>
				</a>
			</div>
			<div class="botones">
				<a class="fan" href="fb://profile/210227459693"></a>
				<a class="share" href="javscript:void(0);" onclick="window.open('http://www.facebook.com/sharer.php?s=100&p[url]=http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/index.php&p[images][0]=http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/web-icon120x120.png&p[title]=test&p[summary]=test', 'newwindow', 'width=500, height=250'); return false;"></a>
			</div>
		</div>
		<!-- Preguntas -->
		<div class="content" id="preguntas">
			<div class="texto"><div class="numero"></div><h1></h1></div>
			<div class="fondo">
				<?php foreach($preguntas as $pregunta){ ?>
				<script>preguntas[<?php echo $pregunta['id']; ?>] = '<?php echo $pregunta['pregunta']; ?>';</script>
				<div class="respuestas" id="<?php echo $pregunta['id']; ?>">
					<?php foreach($pregunta['Respuestas'] as $respuesta){ ?>
						<a class="respuesta" id="<?php echo $respuesta['id']; ?>"href="javascript:void(0);"><?php echo $respuesta['respuesta']; ?></a>
					<?php } ?>
				</div>
				<?php } ?>
				<div class="loading"></div>
			</div>
		</div>
		<!-- Resultado -->
		<div class="content" id="resultado">
			<div class="texto"></div>
			<div class="fondo">
				<div class="resultado">
					<div class="text"></div>
					<a href="javascript:void(0);" onClick="changePage('registro');" class="datos"></a>
				</div>
				<div class="botones">
					<a class="fan" href="fb://profile/210227459693"></a>
					<a class="share" href="javscript:void(0);" onclick="window.open('http://www.facebook.com/sharer.php?s=100&p[url]=http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/index.php&p[images][0]=http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/web-icon120x120.png&p[title]=test&p[summary]=test', 'newwindow', 'width=500, height=250'); return false;"></a>
				</div>
			</div>
		</div>
		<!-- Registro -->
		<div class="content" id="registro">
			<div class="errors"></div>
			<div class="texto"></div>
			<div class="fondo">
				<form id="registro">
					<input id="nombre" class="input" placeholder="Nombre"/>
					<input id="apellido" class="input" placeholder="Apellido" />
					<input id="DNI" type="number" class="input" placeholder="DNI"/>
					<input id="email" type="email" class="input" placeholder="Mail"/>
					<div class="input">
						¿Sos mamá?
						Si<input type="radio" id="mama" name="mama" value="si">
						No<input type="radio" id="mama" name="mama" value="no">
					</div>
					<div class="input">
						<input type="checkbox" name="bases" id="bases" value="1">Acepto <a href="javascript:void(0);">bases y condiciones</a>
					</div>
					<a href="javascript:void(0);" class="enviar"></a>
				</form>
			</div>
		</div>
		<!-- Final -->
		<div class="content" id="final">
			<div class="texto"></div>
			<div class="fondo">
				<div class="caja">
					<div class="text"></div>
					<div class="botellas"></div>
					<a href="fb://profile/210227459693" class="fb-awafrut"></a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>