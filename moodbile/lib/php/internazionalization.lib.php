<?php
//funcion encargada de incluir el idioma preestablecido para el cliente
//TODO: Que la funcion incluia tambien el idioma por defecto
function moodbile_i18n(){
    global $CFG, $Moodbile;
    
    if(!isset($CFG['lang'])){
        $lang = 'en_EN';
    } else {
        $lang = $CFG['lang'];
    }
    
    if(file_exists('languages/'.$lang.'/'.$lang.'.php')){
        include('languages/'.$lang.'/'.$lang.'.php');
    }
    
    $Moodbile['djs']['i18n'] =  $string;
}

//funcion encargada de devolver el string adecuado
function moodbile_get_string($string){
    global $Moodbile;
    
    if(isset($Moodbile['djs']['i18n'][$string])){
        return $Moodbile['djs']['i18n'][$string];
    } else {
        return "String!";
    }
}