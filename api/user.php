<?php

function postUserData(){

	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$userdata = json_decode(file_get_contents("php://input"), true);
	$db = databaseConection();
	$nombre = mysqli_real_escape_string($db,$userdata['nombre']);
	$apellido = mysqli_real_escape_string($db,$userdata['apellido']);
	$descripcion = mysqli_real_escape_string($db,$userdata['descripcion']);
	$imagen = $userdata['imagen'];
	$id_user = $data['id'];
	$sql = "UPDATE usuario SET nombre = '$nombre',apellido='$apellido',descripcion='$descripcion',imagen='$imagen' WHERE id = $id_user";
	$result = mysqli_query($db,$sql);
	if($result === false){
		outputError(401);
	}
	header(' ', true, 200);

}

function getProfileInfo(){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$id = $userdata['id'];
	$db = databaseConection();
	$result = mysqli_query($db,"SELECT * FROM usuario WHERE id = $id");
	if($result===false){
		outputError(500);
	}

	$fila = mysqli_fetch_assoc($result);
	$data = ["id" => $fila['id'],"nombre" => $fila['nombre'],"apellido" => $fila['apellido'],"correo" => $fila['correo'],"imagen" => $fila['imagen'],"descripcion" => $fila['descripcion']];
	output($data);

}

function deleteLastProfilePic(){
	$data = json_decode(file_get_contents("php://input"), true);
	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$filePath = $_SERVER['DOCUMENT_ROOT']. "CodingTutorials/".$data['imagen'];

	if(file_exists($filePath)){

		if(unlink($filePath)){
			header(' ', true, 200);
		}else{
			outputError(500);
		}

	}

}

function getTutorialUser($tutorial_id){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$db = databaseConection();
	$sql = "SELECT u.nombre,u.apellido,u.imagen FROM tutorial_usuario as tu
	INNER JOIN usuario AS u ON u.id = tu.id_usuario
	WHERE tu.id_tutorial = $tutorial_id";
	$tutorialUser = [];

	if($result = mysqli_query($db,$sql)){
		while($fila = mysqli_fetch_assoc($result)){
			$tutorialUser [] = ["nombre" => $fila['nombre'],"apellido" => $fila['apellido'],"imagen" => $fila['imagen']];
		}
		output($tutorialUser);
	}else{
		outputError(401);
	}
}


function postloadProfilePicture(){

	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$user = $data['id'];
	$path = $_SERVER['DOCUMENT_ROOT']. "CodingTutorials/usuarios/USER_".$user."/imagenes/".$_FILES['file']['name'][0];
		
	if(!file_exists($path)){
		if(move_uploaded_file($_FILES['file']['tmp_name'][0],$path)){
			echo 'se movio satisfactoriamente';
		}
	}
	header(' ', true, 200);
}



function getProfileTutorials(){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$db = databaseConection();
	$tutoriales = [];

	$userId = $userdata['id'];
	$result = mysqli_query($db,"SELECT t.id,t.titulo,t.descripcion,t.imagen,t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
								INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
								WHERE tu.id_usuario = $userId");
	if($result === false){
		outputError(500);
	}

	while($fila = mysqli_fetch_assoc($result)){
		$tutoriales [] = ["id"=>$fila["id"],"titulo" => $fila["titulo"],"descripcion" => $fila["descripcion"], "imagen" => $fila["imagen"],"estado" => $fila["estado"],"etiquetas" => $fila['etiquetas'],"visitas" => $fila['visitas']];
	}

	output($tutoriales);
}

?>