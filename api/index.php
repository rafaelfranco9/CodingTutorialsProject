<?php

define('ALGORITMO', 'HS512'); // Algoritmo de codificación/firma
define('SECRET_KEY', 'AS..-.DJKLds·ak$dl%Ll!3kj12l3k1sa4_ÑÑ312ñ12LK3Jj4DK5A6LS7JDLK¿?asDqiwUEASDL,NMQWIEUIO'); //String largo y "complicado"

require_once 'jwt_helper.php';
require_once 'database.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$comandos = explode('/', strtolower($_GET['accion']));
$funcionNombre = $metodo.ucfirst($comandos[0]);

if(function_exists($funcionNombre))
	call_user_func_array ($funcionNombre, array_slice($comandos, 1) );
else
	header(' ', true, 400);



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
		
		header(' ', true, 200);
		header('Content-type: application/json');
		echo json_encode($arregloToken);

	} else {
		header(' ', true, 401);
	}
}


function postSignUp(){

	$loginData = json_decode(file_get_contents("php://input"), true);
	$db = databaseConection();

	$nombre = mysqli_real_escape_string($db,$loginData['nombre']);
	$apellido = mysqli_real_escape_string($db,$loginData['apellido']);
	$email = mysqli_real_escape_string($db,$loginData['email']);
	$password = mysqli_real_escape_string($db,$loginData['password']);

	$result = mysqli_query($db,"SELECT * FROM usuario WHERE correo = '$email'");

	if(mysqli_affected_rows($db) == 0){

		$result = mysqli_query($db,"INSERT INTO usuario VALUES(DEFAULT,'$nombre','$apellido','$email','$password')");
		$userId = mysqli_insert_id($db);
		
		try{
			mkdir("../usuarios/USER_".$userId);
			mkdir("../usuarios/USER_".$userId."/imagenes");
		}catch(Exception $e){
			echo 'No se pudo crear el directorio ',$e->getMessage();
		}

		if(mysqli_affected_rows($db) == 1){
			header(' ', true, 200);
		}
	}else{
		header(' ', true, 401);
	}

}


function postloadTutorial(){

	$tutorialData = json_decode(file_get_contents("php://input"), true);
	$titulo = $tutorialData['titulo'];
	$descripcion = !empty($tutorialData['descripcion']) ? "'".$tutorialData['descripcion']."'" : "NULL";
	$imagen = $tutorialData['imagenTutorial'];
	$etiquetas = !empty($tutorialData['etiquetas']) ? "'".json_encode($tutorialData['etiquetas'])."'" : "NULL";
	$herramientas = !empty($tutorialData['herramientas']) ? "'".json_encode($tutorialData['herramientas'])."'" : "NULL";
	$estado = $tutorialData['estado'];

	$db = databaseConection();
	
	if(isset($tutorialData['id_tutorial'])){
		$id = $tutorialData['id_tutorial'];
		$sql = "UPDATE tutorial SET titulo='$titulo',descripcion=$descripcion,imagen='$imagen',etiquetas=$etiquetas,herramientas=$herramientas,estado='$estado' WHERE id=$id";
	}else{
		$sql = "INSERT INTO tutorial VALUES(DEFAULT,'$titulo',$descripcion,'$imagen',$etiquetas,$herramientas,'$estado')";
	}

	$result = mysqli_query($db,$sql);

	if(mysqli_affected_rows($db) > 0){
		header(' ', true, 200);
		header('Content-type: application/json');
		echo mysqli_insert_id($db);

	}else{
		header(' ', true, 401);
	}

	mysqli_close($db);
}

function postloadImages(){

	$authHeader = getallheaders();

    if (isset($authHeader['Authorization'])) {

		list($jwt) = sscanf( $authHeader['Authorization'], 'Bearer %s');
		try
		{
			$token = JWT::decode($jwt, SECRET_KEY, ALGORITMO);
			$data = json_decode(json_encode($token), true);

			$user = 'USER_'.$data['id'];
			$count = count($_FILES['file']['name']);

			for($i=0;$i<$count;$i++){
				$path = $_SERVER['DOCUMENT_ROOT']. "CodingTutorials/usuarios/".$user."/imagenes/".$_FILES['file']['name'][$i];
	
				if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$path)){
					echo 'se movio satisfactoriamente';
				}
			}

			header(' ', true, 200);
			header('Content-type: application/json');
		}
		catch(Exception $e) 
		{
			header(' ', true, 401);
		}
	
	} else {
		header(' ', true, 401);
	}

}

function getTexto() {
	
	$authHeader = getallheaders();

    if (isset($authHeader['Authorization'])) {

		list($jwt) = sscanf( $authHeader['Authorization'], 'Bearer %s');
		try
		{
			$token = JWT::decode($jwt, SECRET_KEY, ALGORITMO);
			$data = json_decode(json_encode($token), true);
			print_r($data['id']);
			print_r($data['nombre']);


			header(' ', true, 200);
			header('Content-type: application/json');
			echo json_encode('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
		}
		catch(Exception $e) 
		{
			header(' ', true, 401);
		}
	
	} else {
		header(' ', true, 401);
	}
}

?>