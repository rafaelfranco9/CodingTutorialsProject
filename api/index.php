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
	outputError(401);


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

		$result = mysqli_query($db,"INSERT INTO usuario VALUES(DEFAULT,'$nombre','$apellido','$email','$password',DEFAULT,NULL)");
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

//Devuelve todos los datos de una tutorial en especifico
function getTutorial($id_tutorial){

	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$db = databaseConection();

	$result = mysqli_query($db,"SELECT * FROM tutorial WHERE id = $id_tutorial");
	if($result === false){
		outputError(401);
	}

	$fila = mysqli_fetch_assoc($result);
	$tutorialData = ["id" => $fila['id'],"titulo" => $fila['titulo'],"descripcion" => $fila['descripcion'],"imagen" => $fila['imagen'],"categoria" => $fila['categoria'],"etiquetas" => $fila['etiquetas'],"herramientas" => $fila['herramientas'],"estado" => $fila['estado'],"visitas" => $fila['visitas']];
	output($tutorialData);

}

//Crea un nuevo tutorial en la base de datos
function postTutorial(){

	//validaciones y llegada de informacion
	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);
	$user_id = $dataUsuario['id'];
	$dataTutorial = json_decode(file_get_contents("php://input"), true);

	//Guardar informacion en variables para la query
	$titulo = $dataTutorial['titulo'];
	$descripcion = isset($dataTutorial['descripcion']) ? "'".$dataTutorial['descripcion']."'" : "NULL";
	$imagen =  isset($dataTutorial['imagenTutorial']) ? "'".$dataTutorial['imagenTutorial']."'" : "NULL";
	$categoria =  isset($dataTutorial['categoria']) ? "'".$dataTutorial['categoria']."'" : "NULL";
	$etiquetas = isset($dataTutorial['etiquetas']) ? "'".json_encode($dataTutorial['etiquetas'])."'" : "NULL";
	$herramientas = isset($dataTutorial['herramientas']) ? "'".json_encode($dataTutorial['herramientas'])."'" : "NULL";
	$estado = $dataTutorial['estado'];
	$id = $dataTutorial['id_tutorial'];

	//Conexion a la DB
	$db = databaseConection();

	$sql = "INSERT INTO tutorial VALUES(DEFAULT,'$titulo',$descripcion,$imagen,$categoria,$etiquetas,$herramientas,'$estado',DEFAULT)";
	if($result = mysqli_query($db,$sql)){
		
		$sql = "INSERT INTO tutorial_usuario VALUES(DEFAULT,$user_id,$id)";
		if($result = mysqli_query($db,$sql)){

			try{
				mkdir("../usuarios/USER_".$user_id."/tutoriales/tutorial_".$id);
			}catch(Exception $e){
				echo 'No se pudo crear el directorio ',$e->getMessage();
				die;
			}

			mysqli_close($db);
			header(' ', true, 200);

		}else{
			outputError(401);
		}

	}else{
		outputError(401);
	}

}



//Actualizar tutorial
function patchTutorial(){


	//validaciones y llegada de informacion
	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);
	$user_id = $dataUsuario['id'];
	$dataTutorial = json_decode(file_get_contents("php://input"), true);

	//Guardar informacion en variables para la query
	$titulo = $dataTutorial['titulo'];
	$descripcion = isset($dataTutorial['descripcion']) ? "'".$dataTutorial['descripcion']."'" : "NULL";
	$imagen =  isset($dataTutorial['imagenTutorial']) ? "'".$dataTutorial['imagenTutorial']."'" : "NULL";
	$categoria =  isset($dataTutorial['categoria']) ? "'".$dataTutorial['categoria']."'" : "NULL";
	$etiquetas = isset($dataTutorial['etiquetas']) ? "'".json_encode($dataTutorial['etiquetas'])."'" : "NULL";
	$herramientas = isset($dataTutorial['herramientas']) ? "'".json_encode($dataTutorial['herramientas'])."'" : "NULL";
	$estado = $dataTutorial['estado'];
	$id = $dataTutorial['id_tutorial'];

	//Conexion a la DB
	$db = databaseConection();

	$sql = "UPDATE tutorial SET titulo='$titulo',descripcion=$descripcion,imagen=$imagen,categoria=$categoria,etiquetas=$etiquetas,herramientas=$herramientas,estado='$estado' WHERE id=$id";
	if($result = mysqli_query($db,$sql)){

		$folderPath = $_SERVER['DOCUMENT_ROOT']."CodingTutorials/usuarios/USER_".$user_id."/tutoriales/tutorial_".$id;
		borrarImagenesSinUsar($folderPath,$dataTutorial);
		mysqli_close($db);
		header(' ', true, 200);

	}else{
		outputError(401);
	}

}


function deleteTutorial($id){


	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$id_user = $data['id'];
	$db = databaseConection();

	$result = mysqli_query($db,"DELETE FROM tutorial_usuario WHERE id_tutorial = $id");
	if($result === false){
		outputError(401);
	}
	
	$result = mysqli_query($db,"DELETE FROM tutorial WHERE id = $id");
	if($result === false){
		outputError(401);
	}

	try{
		
		$DirectoryPath = "../usuarios/USER_".$id_user."/tutoriales/tutorial_".$id;
		borrarArchivosDeCarpeta($DirectoryPath);
		rmdir($DirectoryPath);

	}catch(Exception $e){
		outputError(401);
	}

	header(' ', true, 200);

}

function getCategorias(){

	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);

	//Conexion a la DB
	$db = databaseConection();
	$sql = "SELECT id,nombre FROM categoria";
	$categorias = [];

	if($result = mysqli_query($db,$sql)){

		while($fila = mysqli_fetch_assoc($result)){
			$categorias [] = ["id" => $fila['id'],'nombre'=>$fila['nombre']];
		}
		output($categorias);
		mysqli_close($db);
	}else{
		outputError(404);
		mysqli_close($db);
	}

}

function deleteCategorias($id){

	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);

	//Conexion a la DB
	$db = databaseConection();
	$sql = "DELETE FROM categoria WHERE id = $id";

	if($result = mysqli_query($db,$sql)){
		header(' ', true, 200);
	}else{
		outputError(404);
	}

}

function postCategorias(){

	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);
	$nuevaCategoria = json_decode(file_get_contents("php://input"), true);
	$nuevaCategoria = $nuevaCategoria['categoria'];

	$db = databaseConection();
	$sql = "INSERT INTO categoria VALUES(DEFAULT,'$nuevaCategoria')";

	if($result = mysqli_query($db,$sql)){
		header(' ', true, 200);
	}else{
		outputError(404);
	}

}



function patchVisitas(){

	$authHeader = getallheaders();
	$dataUsuario = validateUser($authHeader);
	$id_tutorial = json_decode(file_get_contents("php://input"), true);

	//Conexion a la DB
	$db = databaseConection();
	$sql = "UPDATE tutorial SET visitas = visitas + 1 WHERE id = $id_tutorial";

	if($result = mysqli_query($db,$sql)){
		output("visita actualizada");
		mysqli_close($db);
	}else{
		outputError(404);
		mysqli_close($db);
	}

}


function borrarImagenesSinUsar($folderPath,$tutorial){

	$archivosEnDirectorio = dirList($folderPath);
	$archivosEnTutorial = archivosTutorial($tutorial);

	if(count($archivosEnTutorial) == 0 && count($archivosEnDirectorio) > 0){
		
		borrarArchivosDeCarpeta($folderPath);
	
	}elseif(count($archivosEnDirectorio) > 0){

		$flag = false;
		foreach($archivosEnDirectorio as $ad){
	
			foreach($archivosEnTutorial as $at){
	
				if($ad == $at){
					$flag = true;
					break;
				}
			}
	
			if($flag == false){
				
				if(!unlink($folderPath."/".$ad)){
					outputError(401);
				}
	
			}else{
				$flag = false;
			}
		}

	}


}

function archivosTutorial($tutorial){

	$archivos = [];

	if(isset($tutorial['herramientas'])){

		foreach($tutorial['herramientas'] as $h){
	
			if($h['label'] == 'image'){
	
				$completePath = $h['valor'];
				$fileName = explode("/",$completePath);
				$fileName =  $fileName[count($fileName)-1];
				$archivos [] = $fileName;
	
			}
	
		}
	
	}

	if(isset($tutorial['imagenTutorial'])){
		
		$tutorialImage = explode("/",$tutorial['imagenTutorial']);
		$tutorialImage = $tutorialImage[count($tutorialImage)-1];
		$archivos [] = $tutorialImage;
	
	}
	
	return $archivos;
}


function getNextTutorialId(){
	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$db = databaseConection();
	if($result = mysqli_query($db,"SELECT `AUTO_INCREMENT` as id FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'codingtutorials' AND TABLE_NAME = 'tutorial'")){
		$id = mysqli_fetch_assoc($result);
		output($id['id']);
	}else{
		outputError(401);
	}
}



function borrarArchivosDeCarpeta($folderPath){

	$listOfFiles = dirList($folderPath);
	foreach($listOfFiles as $file){
		unlink($folderPath."/".$file);
	}

}


function postloadImages($id_tutorial){

	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$user = $data['id'];
	$count = count($_FILES['file']['name']);

	for($i=0;$i<$count;$i++){

		$path = $_SERVER['DOCUMENT_ROOT']. "CodingTutorials/usuarios/USER_".$user."/tutoriales/tutorial_".$id_tutorial."/".$_FILES['file']['name'][$i];
		
		if(!file_exists($path)){
			if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$path)){
				echo 'se movio satisfactoriamente';
			}
		}

	}

	header(' ', true, 200);

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


function getPublishedTutorials(){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$db = databaseConection();
	$tutoriales = [];

	$result = mysqli_query($db,"SELECT u.id as 'id_usuario',u.nombre,u.apellido,u.imagen as 'imagen_usuario',t.id,t.titulo,t.descripcion,t.imagen,t.categoria,t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
								INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
								INNER JOIN usuario AS u ON u.id = tu.id_usuario
								WHERE t.estado = 'publicado'");
	if($result === false){
		outputError(500);
	}

	while($fila = mysqli_fetch_assoc($result)){
		$tutoriales [] = ["id_usuario" => $fila['id_usuario'],"nombre" => $fila['nombre'], "apellido" => $fila['apellido'],"imagen_usuario" => $fila['imagen_usuario'],"id"=>$fila["id"],"titulo" => $fila["titulo"],"descripcion" => $fila["descripcion"], "imagen" => $fila["imagen"],"categoria"=>$fila['categoria'],"estado" => $fila["estado"],"etiquetas" => $fila['etiquetas'],"visitas" => $fila['visitas']];
	}

	output($tutoriales);
	
}

function getPublishedTutorialsFilter($cat){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$db = databaseConection();
	$tutoriales = [];

	$result = mysqli_query($db,"SELECT u.id as 'id_usuario',u.nombre,u.apellido,u.imagen as 'imagen_usuario',t.id,t.titulo,t.descripcion,t.imagen,t.categoria,t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
								INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
								INNER JOIN usuario AS u ON u.id = tu.id_usuario
								WHERE t.estado = 'publicado' and t.categoria = '$cat'");
	if($result === false){
		outputError(500);
	}

	while($fila = mysqli_fetch_assoc($result)){
		$tutoriales [] = ["id_usuario" => $fila['id_usuario'],"nombre" => $fila['nombre'], "apellido" => $fila['apellido'],"imagen_usuario" => $fila['imagen_usuario'],"id"=>$fila["id"],"titulo" => $fila["titulo"],"descripcion" => $fila["descripcion"], "imagen" => $fila["imagen"],"categoria"=>$fila['categoria'],"estado" => $fila["estado"],"etiquetas" => $fila['etiquetas'],"visitas" => $fila['visitas']];
	}

	output($tutoriales);
	
}

function getPublishedTutorialsSearch($inputUsuario){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$db = databaseConection();
	$tutoriales = [];
	$inputUsuario = "%".strtolower($inputUsuario)."%";
	$sql = "SELECT u.id as 'id_usuario',u.nombre,u.apellido,u.imagen as 'imagen_usuario',t.id,t.titulo,t.descripcion,t.imagen,t.categoria,t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
			INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
			INNER JOIN usuario AS u ON u.id = tu.id_usuario
			WHERE ((lower(t.etiquetas) LIKE '$inputUsuario' OR lower(t.titulo) LIKE '$inputUsuario' OR lower(t.descripcion) LIKE '$inputUsuario' OR lower(u.nombre) LIKE '$inputUsuario') AND t.estado = 'publicado')";

	$result = mysqli_query($db,$sql);
	if($result === false){
		outputError(500);
	}

	while($fila = mysqli_fetch_assoc($result)){
		$tutoriales [] = ["id_usuario" => $fila['id_usuario'],"nombre" => $fila['nombre'], "apellido" => $fila['apellido'],"imagen_usuario" => $fila['imagen_usuario'],"id"=>$fila["id"],"titulo" => $fila["titulo"],"descripcion" => $fila["descripcion"], "imagen" => $fila["imagen"],"categoria"=>$fila['categoria'],"estado" => $fila["estado"],"etiquetas" => $fila['etiquetas'],"visitas" => $fila['visitas']];
	}

	output($tutoriales);
	
}




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


function output($val, $headerStatus = 200){
	header(' ', true, $headerStatus);
	header('Content-Type: application/json');
	print json_encode($val);
	die;
}

function dirList($directory) {
	
	$results = array();
	$handler = opendir($directory);
	
	while ($file = readdir($handler)) {
		
		if ($file != '.' && $file != '..') $results[] = $file;
		
	}
	
	closedir($handler);
    return $results;
    
}



?>



<!-- function postloadTutorial(){

$authHeader = getallheaders();
$data = validateUser($authHeader);
$tutorialData = json_decode(file_get_contents("php://input"), true);

$titulo = $tutorialData['titulo'];
if(isset($tutorialData['descripcion'])){
	$descripcion = !empty($tutorialData['descripcion']) ? "'".$tutorialData['descripcion']."'" : "NULL";
}else{
	$descripcion = "NULL";
}

$imagen = $tutorialData['imagenTutorial'];
if(isset($tutorialData['etiquetas'])){
	$etiquetas = !empty($tutorialData['etiquetas']) ? "'".json_encode($tutorialData['etiquetas'])."'" : "NULL";
}else{
	$etiquetas = "NULL";
}

$herramientas = !empty($tutorialData['herramientas']) ? "'".json_encode($tutorialData['herramientas'])."'" : "NULL";
$estado = $tutorialData['estado'];
$id = $tutorialData['id_tutorial'];
$user = $data['id'];

$DirectoryPath = $_SERVER['DOCUMENT_ROOT']. "CodingTutorials/usuarios/USER_".$user."/tutoriales/tutorial_".$id;

$db = databaseConection();

if($tutorialData['existe_tutorial']){
	$sql = "UPDATE tutorial SET titulo='$titulo',descripcion=$descripcion,imagen='$imagen',etiquetas=$etiquetas,herramientas=$herramientas,estado='$estado' WHERE id=$id";
	$accion = "UPDATE";
}else{
	$sql = "INSERT INTO tutorial VALUES(DEFAULT,'$titulo',$descripcion,'$imagen',$etiquetas,$herramientas,'$estado',DEFAULT)";
	$accion = "INSERT";
}

$result = mysqli_query($db,$sql);

if($result !== false){

	$lastId = mysqli_insert_id($db);
	
	if($accion == "INSERT"){

		$sql = "INSERT INTO tutorial_usuario VALUES(DEFAULT,$user,$lastId)";
		$result = mysqli_query($db,$sql);

		try{
			mkdir("../usuarios/USER_".$user."/tutoriales/tutorial_".$lastId);
		}catch(Exception $e){
			echo 'No se pudo crear el directorio ',$e->getMessage();
			die;
		}

	}else{

		$listOfFiles = dirList($DirectoryPath);

		$flag = false;
		foreach($listOfFiles as $file){
	
			foreach($tutorialData['herramientas'] as $fileToLoad){
				
				if($fileToLoad['label'] == 'image'){
					
					$completePath = $fileToLoad['valor'];
					$fileName = explode("/",$completePath);
					$fileName =  $fileName[count($fileName)-1];
					echo $fileName."--";
					if($file == $fileName){
						$flag = true;
						break;
					}

					if($flag == false){
			
						if(!unlink($DirectoryPath."/".$file)){
							outputError(500);
						}
			
					}else{
						$flag = false;
					}

				}
			}
	
		}

	}

	mysqli_close($db);
	output($lastId);

}else{
	mysqli_close($db);
	outputError(401);
}

} -->

