<?php

function databaseConection(){

    $conection = mysqli_connect('localhost','root','','codingtutorials');
    mysqli_set_charset($conection,'utf8');
    if($conection === false){
        print mysqli_connect_error();
        outputError(500);
    }

    return $conection;
}



?>