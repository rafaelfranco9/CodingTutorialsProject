<?php

//Login usuario
function postLogin(){

	$loginData = json_decode(file_get_contents("php://input"), true);
	$db = databaseConection();

	$email = mysqli_real_escape_string($db,$loginData['email']);
	$password = mysqli_real_escape_string($db,$loginData['password']);
	$result = mysqli_query($db,"SELECT id,nombre,apellido FROM usuario WHERE correo = '$email' AND contrasena = '$password'");


	if(mysqli_affected_rows($db) == 1) {

		$fila = mysqli_fetch_assoc($result);
		$data = [
			'id' => $fila['id'],
			'nombre' => $fila['nombre'],
			'apellido' => $fila['apellido'],
			'email' => $loginData['email'],
		];
		$jwt = JWT::encode(
					$data,      // Datos a codificar en el JWT
					SECRET_KEY, // Clave de coficicación/firma del token
					ALGORITMO   // Algoritmo usado para codificar/firmar el token
					);
					
		$arregloToken = ['token' => $jwt];
		output($arregloToken);

	} else {
		outputError(401);
	}
}

function getIsAdmin(){
	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);
	$user_id = $dataUsuario['id'];
	$db = databaseConection();

	$sql = "SELECT tipo FROM usuario WHERE id = $user_id";
	if($result = mysqli_query($db,$sql)){
		$fila = mysqli_fetch_assoc($result);
		
		if($fila['tipo'] == 'admin'){
			$answer = true;
		}else{
			$answer = false;
		}
		output($answer);

	}else{
		outputError(401);
	}

}


//Sign up nuevo usuario
function postSignUp(){

	$loginData = json_decode(file_get_contents("php://input"), true);
	$db = databaseConection();

	$nombre = mysqli_real_escape_string($db,$loginData['nombre']);
	$apellido = mysqli_real_escape_string($db,$loginData['apellido']);
	$email = mysqli_real_escape_string($db,$loginData['email']);
	$password = mysqli_real_escape_string($db,$loginData['password']);

	$result = mysqli_query($db,"SELECT * FROM usuario WHERE correo = '$email'");

	if(mysqli_affected_rows($db) == 0){

		$result = mysqli_query($db,"INSERT INTO usuario VALUES(DEFAULT,'$nombre','$apellido','$email','$password',DEFAULT,NULL,DEFAULT)");
		$userId = mysqli_insert_id($db);
		
		try{
			mkdir("../usuarios/USER_".$userId);
			mkdir("../usuarios/USER_".$userId."/imagenes");
			mkdir("../usuarios/USER_".$userId."/tutoriales");
		}catch(Exception $e){
			echo 'No se pudo crear el directorio ',$e->getMessage();
		}

		if(mysqli_affected_rows($db) == 1){
			header(' ', true, 200);
		}
	}else{
		outputError(401);
	}

}

function validateUser($authHeader){

	if (isset($authHeader['Authorization'])) {

		list($jwt) = sscanf( $authHeader['Authorization'], 'Bearer %s');
		try
		{
			$token = JWT::decode($jwt, SECRET_KEY, ALGORITMO);
			$data = json_decode(json_encode($token), true);
			return $data;
		}
		catch(Exception $e) 
		{
			outputError(401);
		}
	
	} else {
		outputError(401);
	}

}




?>