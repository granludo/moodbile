<?php
//TODO: Mejorar para ofrecer mejor estabilidad
function moodbile_is_loged() {
    if(isset($_COOKIE['Moodbile'])) {
        return true;
    } else {
        return false;
    }
}