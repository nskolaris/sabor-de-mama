<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi" />
	<title>El sabor de mamá</title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/styleRegistro.css" />
	<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<script src="js/flowType.js"></script>
</head>
<script>
function doOnOrientationChange()
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

$(document).ready(function(){
	window.addEventListener('orientationchange', doOnOrientationChange);
	doOnOrientationChange();
	console.log(validateString());
	$('.enviar').click(function(){
		if(validateFields()){
			var data = {nombre:$('#nombre').val(),apellido:$('#apellido').val(),dni:$('#DNI').val(),email:$('#email').val(),es_madre:$('#mama').val()};
			$.post("src/functions.php?action=register", data, function(result){
				if(result == 'existe'){
					alert('El usuario ya existe');
				}else if(result == 'no login'){
					
				}else if(result == 'ok'){
					window.location = "final.php";
				}
			});
		}
	});
});

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
	re = /^[a-zA-Z0-9- ]*$/;
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
<body class="index">
	<div id="container">
		<div id="header"></div>
		<div id="content">
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
	</div>
</body>
</html>