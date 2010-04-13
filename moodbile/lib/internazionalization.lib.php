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

//funcion que procesa las variables para habilitar la internacionalizacion con JS
/*function moodbile_i18n_process_script(){
    //Renderizamos variables 'globales'. ej: array Moodbile.locale, con los strings
    //TODO: Algo que añada idioma por defecto y idioma a usar, o que mezcle los dos en un mismo array (esta ultima idea mola mas, para no cargar recursos
    global $i18n;
    
    
    $script = '<script type="text/javascript">';
    $script .= 'Moodbile.i18n = [];';
    foreach($i18n as $str => $value){
        $script .= 'Moodbile.i18n["'.$str.'"] = "'.$value.'";';
    }
    $script .= '</script>';
    
    return $script;
}*/

//funcion encargada de devolver el string adecuado
function moodbile_get_string($string){
    global $Moodbile;
    
    if(isset($Moodbile['djs']['i18n'][$string])){
        return $Moodbile['djs']['i18n'][$string];
    } else {
        return "String!";
    }
}