<?

function moodbile_get_menu_items() {
    global $CFG;
    
    //$basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];
    
    foreach($active_modules as $module) {
        if(file_exists('modules/'. $module .'/'. $module .'.info')){ //Si existe archivo, lo analizamos.
            $file_info = file('modules/'. $module .'/'. $module .'.info'); //BASEPATH!  
            
            //TODO: Hacer que descarte aquellos que no tienen definido un item de menu
            foreach($file_info as $file_info) {
                $file_info = explode(" = ", $file_info); //Separamos la fila del fichero en 2
                
                if(!empty($file_info[1])) { // Si el item 1 esta vacio, quiere decir que descartemos la fila
                    $info[$file_info[0]] = $file_info[1];
                }
            }
            if (array_key_exists('menu_item', $info)) {
                $menu_items[] = $info;
            }
        }
    }
    _debug($info);
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