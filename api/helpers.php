<?php

function outputError($headerStatus){
	header('Error', true, $headerStatus);
	die;
}

function output($val, $headerStatus = 200){
	header(' ', true, $headerStatus);
	header('Content-Type: application/json');
	print json_encode($val);
	die;
}

?>