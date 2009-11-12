<?
function moodbile_render_breadcrumb(){
    
    $output = '<ul>';
        $output .= '<li id="home"><a href="#"><span>Home</span></a></li>';
        $output .= '<li id="level-1"><a href="#"><span></span></a></li>';
        $output .= '<li id="level-2"><a href="#"><span></span></a></li>';
    $output .= '</ul>';
    
    return $output;
}