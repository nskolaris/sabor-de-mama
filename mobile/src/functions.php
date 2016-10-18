<?php
session_start();

if(isset($_GET['action'])){
	if($_GET['action'] == 'save'){
		savePreguntas($_POST);
	}elseif($_GET['action'] == 'register'){
		registerUser($_POST);
	}
}

function connect_db(){
	//$con = mysql_connect("localhost","appslanz_awafrut","l4nz4ll4m4s")or die("Unable to connect to MySQL");
	$con = mysql_connect("localhost","root","milagro06")or die("Unable to connect to MySQL");
	mysql_select_db('awafrut_madre_mobile',$con);
	mysql_query("SET NAMES 'utf8'", $con);
	return $con;
}

function getPreguntas(){
	$con = connect_db();
	$res = mysql_query("SELECT * FROM preguntas",$con)or die("consulta mal");
	$data = array();
	while($row = mysql_fetch_assoc($res)){
		$row['Respuestas'] = array();
		$res2 = mysql_query("SELECT * FROM respuestas WHERE pregunta_id = ".$row['id'],$con)or die("consulta mal");
		while($row2 = mysql_fetch_assoc($res2)){
			array_push($row['Respuestas'],$row2);
		}
		array_push($data,$row);
	}
	return $data;
}

function saveNewUser(){
	$con = connect_db();
	$user_code = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].date("F j, Y, g:i a"));
	$query = mysql_query("INSERT INTO usuarios(ip,navegador,code) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."','".$user_code."')", $con);
	$user_id = mysql_insert_id();
	$_SESSION["usuario_id"] = $user_id;
	$_SESSION["user_code"] = $user_code;
	return $user_id;
}

function savePreguntas($data){
	$con = connect_db();
	if(!isset($_SESSION['usuario_id'])){
		$user_id = saveNewUser();
	}else{
		$user_id = $_SESSION['usuario_id'];
		$query = mysql_query("SELECT * FROM usuarios WHERE id = ".$user_id." AND code = '".$_SESSION["user_code"]."'", $con);
		if(mysql_num_rows($query) < 1){
			$user_id = saveNewUser();
		}
	}
	foreach($data as $i => $pregunta){
		$query = mysql_query("INSERT INTO respuestas_usuarios(usuario_id, pregunta_id, respuesta_id) VALUES ('".$user_id."','".$i."','".$pregunta."')", $con);
	}
	echo json_encode(getResult());
}

function getResult(){
	$con = connect_db();
	$res = mysql_query("SELECT * FROM respuestas_usuarios WHERE usuario_id  = ".$_SESSION['usuario_id']." ORDER BY id DESC LIMIT 5",$con)or die("consulta mal");
	$result = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,);
	while($row = mysql_fetch_assoc($res)){
		$res2 = mysql_query("SELECT * FROM respuestas WHERE id  = ".$row['respuesta_id'],$con)or die("consulta mal");
		$row2 = mysql_fetch_assoc($res2);
		$result[$row2['mama_id']]++;
	}
	$mama_id = array_search(max($result), $result);
	return getMadre($mama_id);
}

function registerUser($data){
	$con = connect_db();
	if(isset($_SESSION['usuario_id'])){
		$res = mysql_query("SELECT * FROM usuarios WHERE dni = '".$data['dni']."' OR email = '".$data['email']."'",$con)or die("consulta mal");
		if(mysql_num_rows($res) < 1){
			$name = $data['nombre'];
			$apellido = $data['apellido'];
			$dni = $data['dni'];
			$email = $data['email'];
			preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $name);
			preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $apellido);
			preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $dni);
			preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $email);
			$query = mysql_query("UPDATE usuarios SET nombre='".mysql_real_escape_string($name)."', apellido='".mysql_real_escape_string($apellido)."', dni='".mysql_real_escape_string($dni)."', email='".mysql_real_escape_string($email)."', es_madre='".mysql_real_escape_string($data['es_madre'])."' WHERE id = ".$_SESSION['usuario_id']." AND code = '".$_SESSION["user_code"]."'", $con);
			echo 'ok';
		}else{
			echo 'existe';
		}
	}else{
		echo 'no login';
	}
}

function getMadre($mama_id){
	$con = connect_db();
	$res = mysql_query("SELECT * FROM madres WHERE id = ".$mama_id,$con)or die("consulta mal");
	return mysql_fetch_assoc($res);
}
?>