<?php

function databaseConection(){

    $config = json_decode(file_get_contents("../config/config.json"),true);

    $conection = mysqli_connect($config["hostDB"],$config["userDB"],$config["passwordDB"],$config["databaseName"]);
    mysqli_set_charset($conection,'utf8');
    if($conection === false){
        print mysqli_connect_error();
        outputError(500);
    }

    return $conection;
}



?>