<?php
//alert types: error, warming, success
function moodbile_add_alert($type = "error", $error_str_key = "Uknow") {
    global $CFG, $Moodbile;
    
    $Moodbile['djs']['alert'][$type][] = $error_str_key;
}