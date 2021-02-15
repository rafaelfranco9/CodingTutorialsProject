<?php

function getPublishedTutorials(){

	$authHeader = getallheaders();
	$userdata = validateUser($authHeader);
	$db = databaseConection();
	$tutoriales = [];

	$result = mysqli_query($db,"SELECT u.id as 'id_usuario',u.nombre,u.apellido,u.imagen as 'imagen_usuario',t.id,t.titulo,t.descripcion,t.imagen,c.nombre as 'categoria',t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
								INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
								INNER JOIN usuario AS u ON u.id = tu.id_usuario
								INNER JOIN categoria AS c ON c.id = t.categoria
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

	$result = mysqli_query($db,"SELECT u.id as 'id_usuario',u.nombre,u.apellido,u.imagen as 'imagen_usuario',t.id,t.titulo,t.descripcion,t.imagen,c.nombre as 'categoria',t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
								INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
								INNER JOIN usuario AS u ON u.id = tu.id_usuario
								INNER JOIN categoria AS c ON c.id = t.categoria
								WHERE t.estado = 'publicado' AND categoria = $cat");
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
	$sql = "SELECT u.id as 'id_usuario',u.nombre,u.apellido,u.imagen as 'imagen_usuario',t.id,t.titulo,t.descripcion,t.imagen,t.categoria as 'id_categoria',c.nombre as 'categoria',t.estado,t.etiquetas,t.visitas from tutorial_usuario AS tu
			INNER JOIN tutorial AS t ON t.id = tu.id_tutorial
			INNER JOIN usuario AS u ON u.id = tu.id_usuario
			INNER JOIN categoria AS c ON c.id = t.categoria
			WHERE ((lower(t.etiquetas) LIKE '$inputUsuario' OR lower(t.titulo) LIKE '$inputUsuario' OR lower(t.descripcion) LIKE '$inputUsuario' OR lower(u.nombre) LIKE '$inputUsuario' OR lower(c.nombre) LIKE '$inputUsuario') AND t.estado = 'publicado')";

	$result = mysqli_query($db,$sql);
	if($result === false){
		outputError(500);
	}

	while($fila = mysqli_fetch_assoc($result)){
		$tutoriales [] = ["id_usuario" => $fila['id_usuario'],"nombre" => $fila['nombre'], "apellido" => $fila['apellido'],"imagen_usuario" => $fila['imagen_usuario'],"id"=>$fila["id"],"titulo" => $fila["titulo"],"descripcion" => $fila["descripcion"], "imagen" => $fila["imagen"],"id_categoria"=>$fila['id_categoria'],"categoria"=>$fila['categoria'],"estado" => $fila["estado"],"etiquetas" => $fila['etiquetas'],"visitas" => $fila['visitas']];
	}

	output($tutoriales);
	
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

?>