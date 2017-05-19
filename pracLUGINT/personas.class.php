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
 echo json_encode(array("datos"=>$datos,"status"=>"ok","mensaje"=>"consulta exitosa"));
}
      function locateIp($ip){
            $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
                $details = json_decode($json, true);
                $ciudad = $details['city'];
                if($details['city']==null){
                     $ciudad="UNKNOWN";
                }
                return $ciudad;
}

}