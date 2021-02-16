<?php

$logFile = fopen("../logs/log.txt", "r+") or die("Unable to open file!");
$message = fread($logFile,filesize("../logs/log.txt"));
fclose($logFile);

if($message == "Aplicacion no instalada"){
    
    $connection = mysqli_connect("localhost","root");
    if (mysqli_connect_error()) {
      echo "Error conectandose a la base de datos MySQL: " . mysqli_connect_error();
      exit();
    }
    
    $sqlScript =  file_get_contents("../sql/databaseStructure.sql");  
    
    if($result = mysqli_multi_query($connection, $sqlScript)){
        echo "<h2>Se creo la db y tablas exitosamente</h2><br>---datos del usuario Admin---<br>Email: admin@gmail.com<br>Contraseña: admin1234<br><br>Que disfrute de la aplicacion!";
        $logFile = fopen("../logs/log.txt", "w") or die("Unable to open file!");
        fwrite($logFile,"Aplicacion instalada");
        fclose($logFile);
    }
        
}else{
    echo "<h2>La aplicacion ya fue instalada.</h2><br>En caso de querer reinstalarla modifique el archivo logs/log.txt y escriba 'Aplicacion no instalada'";
}


?>