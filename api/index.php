<?php

define('ALGORITMO', 'HS512'); // Algoritmo de codificación/firma
define('SECRET_KEY', 'AS..-.DJKLds·ak$dl%Ll!3kj12l3k1sa4_ÑÑ312ñ12LK3Jj4DK5A6LS7JDLK¿?asDqiwUEASDL,NMQWIEUIO'); //String largo y "complicado"

require_once 'jwt_helper.php';
require_once 'database.php';
require_once 'categorias.php';
require_once 'helperImagenes.php';
require_once 'helpers.php';
require_once 'home.php';
require_once 'login.php';
require_once 'tutorial.php';
require_once 'user.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$comandos = explode('/', strtolower($_GET['accion']));
$funcionNombre = $metodo.ucfirst($comandos[0]);

if(function_exists($funcionNombre))
	call_user_func_array ($funcionNombre, array_slice($comandos, 1) );
else
	outputError(401);

?>

