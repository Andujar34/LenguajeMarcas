<?php


ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

class personas
{
private $dom,$xpath,$respuesta;

function __construct()
{
    $this->dom = new DOMDocument('1.0', 'utf-8');
}
function giveList()
{
    $this->dom->load(FICHERO);
    $this->xpath = new DOMXPath($this->dom);

    foreach ($this->xpath->query("//voto") as $nodo) {

        $ciudad=$this->locateIp($nodo->childNodes[3]->nodeValue);
        if(empty($datos[$ciudad])){
            $puntuacion=$nodo->childNodes[1]->nodeValue;
            $datos[$ciudad] =array("votantes"=>"1","votos"=>$puntuacion);
           
          }else{ 
            $votantes =((int)$datos[$ciudad]['votantes'])+1;
            $puntuacion=((int)$nodo->childNodes[1]->nodeValue)+((int)$datos[$ciudad]['votos']);
           $datos[$ciudad] =array("votantes"=>$votantes,"votos"=>$puntuacion);
          }
        
        
         
    }
 echo json_encode($datos);
}
      function locateIp($ip){
            $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
            return $meta['geoplugin_region'];
    
}
}