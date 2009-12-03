<?

function moodbile_get_menu_items() {
    global $CFG;
    
    //$basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];
    
    foreach($active_modules as $module) {
        if(file_exists('modules/'. $module .'/'. $module .'.info')){ //Si existe archivo, lo analizamos.
            $file_info = file('modules/'. $module .'/'. $module .'.info'); //BASEPATH!  
            unset($info); //Borramos la variable info para evitar que la informaciones del modulo se mezclen
    
            foreach($file_info as $file_info) {
                $file_info = explode(" = ", $file_info); //Separamos la fila del fichero en 2
                
                if(!empty($file_info[1])) { // Si el item 1 esta vacio, quiere decir que descartemos la fila
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
            if (array_key_exists('menu_item', $item)) {
                $output .= '<li id="'.$item['name'].'"><a href="#">'.$item['menu_item'].'</a></li>';       
            }
        }
    }
    $output .= '</ul>';
    
    return $output;
}