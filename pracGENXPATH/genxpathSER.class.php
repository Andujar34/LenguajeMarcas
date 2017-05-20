<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

class genxpathSER{
     function __construct()
    {
        
    }
    function prueba(){
        echo json_encode(array("status"=>"ok","mensaje"=>"correcto"));
    }
}