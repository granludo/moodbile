<?php
//funcion encargada de incluir el idioma preestablecido para el cliente
//TODO: Que la funcion incluia tambien el idioma por defecto
function moodbile_i18n(){
    global $CFG, $Moodbile;
    
    if(moodbile_is_loged()){
        $cookie = json_decode($_COOKIE['Moodbile']);
        
        $lang = $cookie->lang;
    } else {
        if(!isset($CFG['lang'])){
            $lang = 'en';
        } else {
            $lang = $CFG['lang'];
        }
    }
    
    if(file_exists('languages/'.$lang.'/'.$lang.'.js')){
        return 'languages/'.$lang.'/'.$lang.'.js';
    } else {
        return 'languages/en/en.js';
    }
}