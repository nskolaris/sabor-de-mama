<?php
$str_data = file_get_contents("data/logros.json");
$logros = json_decode(utf8_encode($str_data),true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi" />
	<title>Ecológica - EcoLogros</title>
    <link rel="stylesheet" href="css/jquery.mobile.custom.theme.css" />
    <link rel="stylesheet" href="css/jquery.mobile.custom.structure.css" />
	<link rel="stylesheet" href="css/style.css" />
    <script src="js/jquery.mobile.custom.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<script src='js/swipe.min.js'></script>
</head>
<script>
	var slider;
	var bullets;
	var doit;
	
	$( document ).ready(function() {
		clearTimeout(doit);
		doit = setTimeout(updateSize, 500);
	});
	
	$(window).resize(function() {
	  clearTimeout(doit);
	  doit = setTimeout(updateSize, 500);
	});
	
	function updateSize(){
		var ulheight = $('ul.logros').height();
		$('ul.logros li').css('height',ulheight);
		var descheight = $('.desc-logro').height();
		var descwidth = $('.desc-logro').width();
		if(descwidth<descheight){$('.desc-logro').css('font-size',descwidth/20);}
		else{$('.desc-logro').css('font-size',descheight/15);}
	}
	
	setTimeout(function(){slider = new Swipe(document.getElementById('logros-wrapper'), {})}, 200);
</script>
<body class="logros">
	<div data-role="page" id="container">
		<div data-role="header" id="header"></div>
		<div data-role="content" id="content">
			<div id="logros-wrapper" class="swipe logros-wrapper tipit">	
				<ul class="logros">
					<?php foreach($logros['logros'] as $i=>$logro){ ?>
						<li>
							<div class="logro">
								<div class="imagen" style="background-image:url(images/ecologica/logros/logro-<?php echo ($i+1); ?>.jpg);"></div>
								<div class="descripcion desc-logro">
									<p>
										<?php echo $logro; ?>
									</p>
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="titulo-logros"></div>
			<a class="btn-volver" href="index.php"></a>
			<a href='javascript:void(0);' onclick='slider.prev();return false;' class="anterior tipit"></a> 
			<a href='javascript:void(0);' onclick='slider.next();return false;' class="siguiente tipit"></a>
		</div>
	</div>
</body>
</html>