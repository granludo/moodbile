<?

function moodbile_get_menu_items() {
    global $CFG;
    
    //$basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];
    
    foreach($active_modules as $module) {
        if(file_exists('modules/'. $module .'/'. $module .'.info')){
            $file_info = file('modules/'. $module .'/'. $module .'.info'); //BASEPATH!  
        
            foreach($file_info as $file_info) {
                $file_info = explode(" = ", $file_info);
            
                if(!empty($file_info[1])) {
                    $info[$file_info[0]] = $file_info[1];
                }
            }
        $menu_items[] = $info;
        }
    }
     
    //_debug($menu_items);
    return $menu_items;
}

function moodbile_render_menu($menu_items = NULL){

    $output = '<ul>';
    if(is_array($menu_items)) {
        foreach($menu_items as $item) {
            $output .= '<li id="'.$item['name'].'"><a href="#">'.$item['menu_item'].'</a></li>';
        }
    }
    $output .= '</ul>';
    
    return $output;
}