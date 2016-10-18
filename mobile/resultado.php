<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi" />
	<title>El sabor de mamá</title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/styleResultado.css" />
	<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<script src="js/flowType.js"></script>
</head>
<?php include('src/functions.php'); ?>
<?php $madre = getResult(); ?>
<script>
function doOnOrientationChange()
{
	if(window.orientation !== undefined){
		switch(window.orientation) 
		{  
			case -90:
			case 90:
				$('.text p').flowtype({fontRatio : 40, lineRatio : 1.45});
				$('.text h1').flowtype({fontRatio : 30, lineRatio : 1.45});
				break; 
			default:
				$('.text p').flowtype({fontRatio : 30, lineRatio : 1.45});
				$('.text h1').flowtype({fontRatio : 20, lineRatio : 1.2});
				break; 
		}
	}else{
		$('.text p').flowtype({fontRatio : 40, lineRatio : 1.45});
		$('.texto h1').flowtype({fontRatio : 30, lineRatio : 1.45});
	}
	var height = $('.fondo').height();
	$('.datos').css('height',height*0.1);
	$('.botones').css('height',height*0.2);
}
	  
$(document).ready(function(){
	window.addEventListener('orientationchange', doOnOrientationChange);
	doOnOrientationChange();
});
</script>
<body class="index">
	<div id="container">
		<div id="header"></div>
		<div id="content">
			<div class="texto" style="background-image: url(images/awafrut/madres/<?php echo $madre['nombre']; ?>.jpg);"></div>
			<div class="fondo" style="background-image: url(images/awafrut/fondos/<?php echo $madre['nombre']; ?>.jpg);">
				<div class="resultado <?php echo $madre['nombre']; ?>">
					<div class="text"><?php echo $madre['descripcion']; ?></div>
					<a href="registro.php" class="datos"></a>
				</div>
				<div class="botones">
					<a class="fan" href="fb://profile/210227459693"></a>
					<a class="share" href="javscript:void(0);" onclick="window.open('http://www.facebook.com/sharer/sharer.php?s=100&p[url]=http://nskolaris.show.enclave.com.ar/awafrut-madre-mobile/site/home.php&p[images][0]=&p[title]=El%20sabor%20de%20Mam%C3%A1&p[summary]=Test', 'newwindow', 'width=500, height=250'); return false;"></a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>