<?php

//Devuelve todos los datos de una tutorial en especifico
function getTutorial($id_tutorial){

	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$db = databaseConection();
	$sql = "SELECT t.id,t.titulo,t.descripcion,t.imagen,t.categoria AS 'id_categoria',t.etiquetas,t.herramientas,t.estado,t.visitas,c.nombre AS 'categoria'";
	$sql .= " FROM tutorial as t";
	$sql .= " INNER JOIN categoria AS c ON c.id = t.categoria";
	$sql .= " WHERE t.id = $id_tutorial"; 
	
	$result = mysqli_query($db,$sql);
	if($result === false){
		outputError(401);
	}

	$fila = mysqli_fetch_assoc($result);
	$tutorialData = ["id" => $fila['id'],"titulo" => $fila['titulo'],"descripcion" => $fila['descripcion'],"imagen" => $fila['imagen'],"categoria" => $fila['categoria'],"id_categoria" => $fila['id_categoria'],"etiquetas" => $fila['etiquetas'],"herramientas" => $fila['herramientas'],"estado" => $fila['estado'],"visitas" => $fila['visitas']];
	output($tutorialData);

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
				echo "se creo la carpeta";
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


?>