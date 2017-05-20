<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
require_once RUTA . 'genxpathSER.class.php';

$accion = filter_input(INPUT_POST, 'check');
$xmlD = filter_input(INPUT_POST, 'xml');

$gen = new genxpathSER();
if($accion == "generacion"){

    echo $xmlD;
}