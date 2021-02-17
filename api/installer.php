<?php

    $config = json_decode(file_get_contents("../config/config.json"),true);
   
    if($config["hostDB"] == "" || $config["userDB"] == "" || $config["databaseName"] == ""){
        printError();
    }

    if($config["status"] == "no instalada"){
        
        $connection = mysqli_connect($config["hostDB"],$config["userDB"],$config["passwordDB"],$config["databaseName"]);
        if (mysqli_connect_error()) {
          echo "Error conectandose a la base de datos MySQL: " . mysqli_connect_error();
          exit();
        }
     
        $sqlScript =  file_get_contents("../sql/databaseStructure.sql");  

        if($result = mysqli_multi_query($connection, $sqlScript)){
            echo "<h1>".$config["appName"]."</h1>";
            echo "<h2>Se creo la db y tablas exitosamente</h2><br>---datos del usuario Admin---<br>Email: admin@gmail.com<br>Contraseña: admin1234<br><br>Que disfrute de la aplicacion!";
            $config["status"] = "instalada";
            file_put_contents("../config/config.json",json_encode($config));
        }else{
            echo "Error creando tablas para la base de datos...";
        }
    
    }else{
        echo "<h2>La aplicacion ".$config["appName"]." ya esta instalada!</h2>";
    }


    function printError(){
        echo "<h1>La configuracion esta incompleta...Complete los datos del archivo config.json</h1>";
        die;
    }

?>