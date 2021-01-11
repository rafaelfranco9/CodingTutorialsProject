<?php

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

?>