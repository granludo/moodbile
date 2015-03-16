<?php
//TODO: Mejorar para ofrecer mejor estabilidad
function moodbile_is_loged() {
    if($_COOKIE['Moodbile']) {
        return true;
    } else {
        return false;
    }
}

function moodbile_get_username () {
    $cookie = $_COOKIE['Moodbile'];
    if($cookie) {
        $cookie = json_decode($_COOKIE['Moodbile']);
        
        return $cookie->user;
    } else {
        return false;
    }
}