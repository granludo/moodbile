<?php
function moodbile_render_breadcrumb(){
    
    $output = '<ul>';
        $output .= '<li id="home"><a href="#"><span>Home</span></a></li>';
    $output .= '</ul>';
    
    return $output;
}