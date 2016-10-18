<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi" />
	<title>El sabor de mamá</title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/stylePreguntas.css" />
	<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<!--<script src="js/jquery.mobile.custom.js"></script>-->
	<script src="js/flowType.js"></script>
</head>
<?php include('src/functions.php'); ?>
<?php $preguntas = getPreguntas(); ?>
<script>
	var preguntas = new Array();
	var current = 0;
	var answers = new Array();
	var timeout;
	
	function doOnOrientationChange()
	{
		if(window.orientation !== undefined){
			switch(window.orientation) 
			{  
				case -90:
				case 90:
					$('.texto h1').flowtype({fontRatio : 40, lineRatio : 1.45});
					$('.respuestas#'+current+' .respuesta').flowtype({fontRatio : 30, lineRatio : 1.45});
					break; 
				default:
					$('.texto h1').flowtype({fontRatio : 20, lineRatio : 1.2});
					$('.respuestas#'+current+' .respuesta').flowtype({fontRatio : 20, lineRatio : 1.2});
					break; 
			}
		}else{
			$('.texto h1').flowtype({fontRatio : 40, lineRatio : 1.45});
			$('.respuestas#'+current+' .respuesta').flowtype({fontRatio : 60, lineRatio : 1.45});
		}
		alignHeight($('.texto h1'));
	}
	  
	$(document).ready(function(){
		window.addEventListener('orientationchange', doOnOrientationChange);
		cambiarPregunta();
		doOnOrientationChange();
		$('.respuesta').click(function(){
			$('.respuesta').removeClass('selected');
			$(this).addClass('selected');
			clearTimeout(timeout);
			answers[current] = $(this).attr('id');
			timeout = setTimeout(cambiarPregunta,1000);
		});
	});
	
	function alignHeight(element){
		var height = element.height();
		var parentHeight = element.parent().height();
		element.css('padding-top',(parentHeight-height)/2)
	}
	
	function cambiarPregunta(){
		current++;
		$('.respuestas').hide();
		if(typeof preguntas[current] != 'undefined'){
			$('.texto h1').html(preguntas[current]);
			$('.numero').css('background-image','url(images/awafrut/numeros/numeros-0'+current+'.jpg)');
			$('.respuestas#'+current).show();
			doOnOrientationChange();
		}else{
			$.post("src/functions.php?action=save", $.extend({}, answers), function(data){
				window.location = "resultado.php";
			});
		}
	}
</script>
<body class="index">
	<div id="container">
		<div id="header"></div>
		<div id="content">
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
			</div>
		</div>
	</div>
</body>
</html>