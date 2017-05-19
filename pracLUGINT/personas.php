<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

require_once RUTA . 'personas.class.php';

$accion = filter_input(INPUT_POST, 'check'); 
$per = new personas();
if ($accion == "votacion") {
    echo $per->giveList();
}