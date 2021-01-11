<?php

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

function postloadImages($id_tutorial){

	$authHeader = getallheaders();
	$data = validateUser($authHeader);
	$user = $data['id'];
	$count = count($_FILES['file']['name']);

	for($i=0;$i<$count;$i++){

		$path = $_SERVER['DOCUMENT_ROOT']. "/CodingTutorials/usuarios/USER_".$user."/tutoriales/tutorial_".$id_tutorial."/".$_FILES['file']['name'][$i];
		
		if(!file_exists($path)){
			if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$path)){
				echo 'se movio satisfactoriamente';
			}
		}

	}

	header(' ', true, 200);

}


function borrarArchivosDeCarpeta($folderPath){

	$listOfFiles = dirList($folderPath);
	foreach($listOfFiles as $file){
		unlink($folderPath."/".$file);
	}

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