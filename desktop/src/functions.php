<?php
if(isset($_GET['action'])){
	if($_GET['action'] == 'saveUser'){
		saveNewUser($_POST);
	}
}

if(isset($_GET['p'])){
	$decoded_b64 = base64_decode($_GET['p']);
	$data = json_decode($decoded_b64);
	switch($data->action){
		case 'invite':
		if($inviter=getUserByCode($data->inviter_code)){
			$invited = true;
		}
		break;
	}
}

function connect_db(){
	$con = mysql_connect("localhost","appslanz_awafrut","l4nz4ll4m4s")or die("Unable to connect to MySQL");
	mysql_select_db('appslanz_awafrut_madre_mobile',$con);
	mysql_query("SET NAMES 'utf8'", $con);
	return $con;
}

function saveNewUser($data){
	$con = connect_db();
	$res = mysql_query("SELECT id,fbid,code FROM usuarios WHERE fbid = ".$data['id'],$con)or die("consulta mal");
	if(!$user=mysql_fetch_assoc($res)){ //NO EXISTE
		$user_code = md5($data['id']);
		$query = mysql_query("INSERT INTO usuarios(fbid,nombre,apellido,".(isset($data['email'])?'email,':'')."ip,navegador,code) 
		VALUES ('".$data['id']."','".$data['first_name']."','".$data['last_name']."','".(isset($data['email'])?$data['email']."','":'').$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."','".$user_code."')", $con);
		$user_id = mysql_insert_id();
		echo $user_code;
	}else{ //EXISTE
		echo $user['code'];
	}
	if(isset($data['inviter_id'])){
		$invited_id = (isset($user_id)?$user_id:$user['id']);
		if(canGiveChance($data['inviter_id'], $invited_id)){
			$query = mysql_query("INSERT INTO chances (invited_id,inviter_id) VALUES ('".$invited_id."','".$data['inviter_id']."')", $con);
		}
	}
}

function getUserByCode($code){
	$con = connect_db();
	$res = mysql_query("SELECT * FROM usuarios WHERE code = '".$code."'",$con)or die("consulta mal");
	if($user=mysql_fetch_assoc($res)){ //EXISTE
		return $user;
	}else{ //NO EXISTE
		return false;
	}
}

function canGiveChance($inviter_id, $invited_id){
	if($inviter_id!=$invited_id){
		$con = connect_db();
		$res = mysql_query("SELECT * FROM chances WHERE invited_id = ".$invited_id." AND inviter_id = ".$inviter_id,$con)or die("consulta mal");
		if(!mysql_fetch_assoc($res)){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
?>