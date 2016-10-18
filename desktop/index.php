<?php include('src/functions.php'); ?>
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
	<meta property="og:url" content="http://apps-lanzallamas.com.ar/el-sabor-de-mama/"/>
	<meta property="og:site_name" content="Awafrut. El sabor de Mamá"/>
	<meta property="og:image" content="http://apps-lanzallamas.com.ar/el-sabor-de-mama/web-icon120x120.png"/>
	<meta name="description" content="En el verano, ¿qué tipo de mamá sos? Sobreprotectora, relajada, práctica, distraída, sociable… Contestá el test y descubrí cómo sos. ¡Podés ganar una Tablet!.">
	<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	<script src="js/flowType.js"></script>
</head>
<body>
	<script>
	/*FACEBOOK*/
	var userData;
	var code;
	
	FB.init({
		appId      : '1448061988759598',
		status     : true,
		cookie     : true,
		xfbml      : true 
	});
	
	function fbLogin(){
		FB.getLoginStatus(function(response) {
			if (response.status === 'connected') {
				FB.api('/me', function(response) {
					userData = response;
					checkLiked();
				});
			} else if (response.status === 'not_authorized') {
				FB.login(function(response) {
					if (response.authResponse){FB.api('/me', function(response) {userData = response;checkLiked();});}
				});
			} else {
				FB.login(function(response) {
					if (response.authResponse){FB.api('/me', function(response) {userData = response;checkLiked();});}
				});
			}
		});
	}
	
	function checkLiked(){
		var user_id = userData.id;
		var page_id = "200175803349193";
		var fql_query = "SELECT uid FROM page_fan WHERE page_id = "+page_id+"and uid="+user_id;
		var the_query = FB.Data.query(fql_query);
		the_query.wait(function(rows) {
			if (rows.length == 1 && rows[0].uid == user_id) {
				saveUser();
			} else {
				changePage('fan');
			}
		});
	}
	
	function saveUser(){
		<?php if($invited){ ?>userData.inviter_id = <?php echo $inviter['id']; ?>;<?php } ?>
		$.post("src/functions.php?action=saveUser", userData, function(data){
			if(data != 'error'){
				code = data;
				changePage('resultado');
			}
		});
	}
	
	function share(){
		var data = {action:'asdf',qwer:'qwer'};
		var string = JSON.stringify(data);
		FB.ui({
			method: 'feed',
			link: 'apps-lanzallamas.com.ar/multiplatform/?a=test&p='+base64_encode(string),
			caption: 'An example caption',
			name: 'An example name',
			description: 'An example description',
			picture: 'http://apps-lanzallamas.com.ar/el-sabor-de-mama/images/awafrut/thumb.png'
		}, function(response){});
	}
	
	function shareWithFriend(){
		FB.ui({
			method: 'send',
			link: 'apps-lanzallamas.com.ar/multiplatform/?a=test',
			caption: 'An example caption',
			name: 'An example name',
			description: 'An example description',
			picture: 'http://apps-lanzallamas.com.ar/el-sabor-de-mama/images/awafrut/thumb.png'
		});
	}
	
	function invite(){
		var data = {action:'invite',inviter_code:code};
		var string = JSON.stringify(data);
		FB.ui({
			method: 'feed',
			link: 'apps-lanzallamas.com.ar/multiplatform/?a=test&p='+base64_encode(string),
			caption: 'An example caption',
			name: 'An example name',
			description: 'An example description',
			picture: 'http://apps-lanzallamas.com.ar/el-sabor-de-mama/images/awafrut/thumb.png'
		}, function(response){});
	}
	
	window.fbAsyncInit = function() {
		changePage('home');
		FB.Event.subscribe('edge.create', function(response) {
			saveUser();
		});
	};
	
	/*END FACEBOOK*/
	
	function changePage(id){
		$('.content').hide();
		$('.content#'+id).show();
		switch(id){
			case 'resultado':
				initializeResultado();
				break;
		}
	}
	
	function alignHeight(element){
		var height = element.height();
		var parentHeight = element.parent().height();
		element.css('padding-top',(parentHeight-height)/2)
	}
	
	/* Código resultado */
	function initializeResultado(){
		showResult();
		window.addEventListener('orientationchange', OrientationChangeResultado);
		OrientationChangeResultado();
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
	
	function showResult(){
		var nombreMadre = 'mango';
		$('.content#resultado .texto').css('background-image','url(images/awafrut/madres/'+nombreMadre+'.jpg)');
		$('.content#resultado .fondo').css('background-image','url(images/awafrut/fondos/'+nombreMadre+'.jpg)');
		$('.content#resultado .fondo .resultado').addClass(nombreMadre);
		$('.content#resultado .fondo .resultado .text').html('sas');
	}
	
	function base64_encode(data) {
		var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
		var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
		ac = 0,
		enc = '',
		tmp_arr = [];

		if (!data) {
			return data;
		}

		do { // pack three octets into four hexets
			o1 = data.charCodeAt(i++);
			o2 = data.charCodeAt(i++);
			o3 = data.charCodeAt(i++);

			bits = o1 << 16 | o2 << 8 | o3;

			h1 = bits >> 18 & 0x3f;
			h2 = bits >> 12 & 0x3f;
			h3 = bits >> 6 & 0x3f;
			h4 = bits & 0x3f;

			// use hexets to index into b64, and append result to encoded string
			tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
		} while (i < data.length);

		enc = tmp_arr.join('');

		var r = data.length % 3;

		return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
	}

	</script>
	<div id="container">
		<div id="header" onClick="location.reload();"></div>
		<!-- Home -->
		<div class="content" id="home">
			<div class="texto"></div>
			<div class="fondo">
				<a class="participar" href="javascript:void(0);" onClick="fbLogin();">
					<div class="tablet"></div>
				</a>
				<?php if($invited){ ?>
					Fuiste invitado por <?php echo $inviter['nombre'].' '.$inviter['apellido']; ?>!! Dale una chance de ganar participando
				<?php } ?>
			</div>
		</div>
		<!-- FAN -->
		<div class="content" id="fan" style="display: block;">
			<div class="texto">Hacete fan para jugar</div>
			<div class="botones">
				<div class="fb-like" data-href="https://www.facebook.com/pages/TABS-Lanzallamas/200175803349193" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</div>
		</div>
		<!-- Resultado -->
		<div class="content" id="resultado">
			<div class="texto"></div>
			<div class="fondo">
				<div class="resultado">
					<div class="text"></div>
				</div>
				<div class="botones">
					<a onclick="share();">compartir resultado</a>
					<a onclick="shareWithFriend();">invitar amigos</a>
					<a onclick="invite();">compartir (a lo invite)</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>