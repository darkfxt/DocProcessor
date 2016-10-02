<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of scriptProcessor
 *
 * @author Maty
 */
class scriptProcessor {
    //put your code here
    private $headerLines;
    
    function parseToDB($text){
        $separado = explode('|', $text);
        $salida['time'] = $separado[0];
        $salida['char'] = $separado[1];
        $salida['text'] = $separado[2];
    }
}
