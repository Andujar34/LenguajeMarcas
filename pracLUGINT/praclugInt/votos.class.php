<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

class votos
{
    private $dom,$xpath,$respuesta;

    function __construct()
    {
        $this->dom = new DOMDocument('1.0', 'utf-8');
    }
    function givePoint($user, $votos, $navegador, $idLugar, $tiempo, $ip)
    {
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        foreach ($this->xpath->query("/lugares/lugar[@id='$idLugar']") as $nodo) {
            $node_voto = $this->dom->createElement('voto');
            $node_voto->setAttribute("user", $user);
            $node_voto->setAttribute("t", $tiempo);
            $node_item =$this->dom->createElement('item', $votos);
            $node_ip=$this->dom->createElement('ip', $ip);
            $node_userAgent=$this->dom->createElement('userAgent', $navegador);
            $nodo->appendChild($node_voto);
            $node_voto->appendChild($node_item);
            $node_voto->appendChild($node_ip);
            $node_voto->appendChild($node_userAgent);
        }
        $this->dom->save(FICHERO);
        echo json_encode(array("status"=>"ok","mensaje"=>"votacion realizada"));
    }
    function ranking($idLugar)
    {
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        $contador=0;
        foreach ($this->xpath->query("/lugares/lugar") as $nodo) {
            $id=$nodo->getAttribute('id');
            $puntuacion=0;
            $votos = $this->xpath->query("/lugares/lugar[@id='".$id."']/voto/item");
            $num_votaciones=(int)$votos->length.PHP_EOL;
            foreach ($votos as $nodoItem) {
                $puntuacion+=(int)$nodoItem->nodeValue;
            }
            if ($id==$idLugar && $puntuacion!=0) {
                $media=$puntuacion/$num_votaciones;
                $media=number_format($media, 2, '.', '');
                $puntuaciontotal=$puntuacion;
            } elseif ($id==$idLugar && $puntuacion==0) {
                $media="No tiene puntuacion media";
                $puntuaciontotal="No tiene puntos";
            }
            if ($puntuacion!=null) {
                $ranking[$contador]=$id;
                $ranking_variable[$contador]=($puntuacion);
                $contador++;
            }
        }
    
        $result= $this->burbuja($ranking, $ranking_variable);
        
        foreach ($this->xpath->query("/lugares/lugar") as $nodo) {
            $id=$nodo->getAttribute('id');
            for ($i=0; $i<count($result); $i++) {
                if ($id==$result[$i]) {
                    $nodo->setAttribute('pos', ($i+1));
                }
            }
        }
        $this->dom->save(FICHERO);
        $tiempoinicial=0;
        foreach ($this->xpath->query("/lugares/lugar[@id='$idLugar']/voto") as $nodo) {
            $tiempo= $nodo->getAttribute('t');
            if ($tiempo>$tiempoinicial) {
                $votante = $nodo->getAttribute('user');
                $fecha=new DateTime();
                $fecha->setTimestamp($nodo->getAttribute('t'));
                $fecha=$fecha->format('Y-m-d H:i');
                $tiempoinicial=$tiempo;
            }
        }
        if ($votante==null) {
            $votante="Nadie ha votado esta ubicación";
        }
        if ($fecha==null) {
            $fecha="No existe ninguna puntuacion";
        }
        
        echo json_encode(array("votante"=>$votante,"puntuacion"=>$puntuaciontotal,"fecha"=>$fecha,"media"=>$media));
    }
    function burbuja($ranking, $ranking_variable)
    {
        for ($i=1; $i<count($ranking_variable); $i++) {
            for ($j=0; $j<count($ranking_variable)-$i; $j++) {
                if ($ranking_variable[$j]<$ranking_variable[$j+1]) {
                    $k=$ranking_variable[$j+1];
                    $h=$ranking[$j+1];
                    $ranking_variable[$j+1]=$ranking_variable[$j];
                    $ranking[$j+1]=$ranking[$j];
                    $ranking_variable[$j]=$k;
                    $ranking[$j]=$h;
                }
            }
        }
        
            return $ranking;
    }
}
