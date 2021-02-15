<?php

require_once 'database.php';
require_once 'helpers.php';

$logFile = fopen("../logs/log.txt", "r") or die("Unable to open file!");
$message = fread($logFile,filesize("../logs/log.txt"));
fclose($logFile);

if($message == "Aplicacion no instalada"){

    //Base de datos mysql
    $db = databaseConection();
    $sqlFile = fopen("../sql/mysqlStructure.sql", "r") or die("Unable to open file!");
    $sql = fread($sqlFile,filesize("../sql/mysqlStructure.sql"));
    fclose($sqlFile);
    
    if($result = mysqli_query($db,$sql)){
        header(' ', true, 200);

        $logFile = fopen("../logs/log.txt", "w") or die("Unable to open file!");
        $message = "Aplicacion instalada";
        fwrite($logFile, $message);

    }else{
        header('Error', true, 500);
    }
        
}


?>