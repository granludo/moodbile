<?php
function moodbile_device() {
    $supported_devices = array('iPod', 'iPad', 'iPhone', 'Android', 'Firefox', 'Safari');
    
    foreach($supported_devices as $device) {
        $supported = strpos($_SERVER['HTTP_USER_AGENT'], $device);
        
        if($supported) {
            $whatDevice = $device;
            break;
        }
    }
    
    if($whatDevice) {
        return $whatDevice;
    } else {
        return FALSE;
    }
}