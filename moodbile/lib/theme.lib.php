<?

//funcion que incluye las plantillas al index.php
function moodbile_get_template($theme = "moodbile") {
    
    if(file_exists('themes/'. $theme .'/page.tpl.php')){
        $template = 'themes/'. $theme .'/page.tpl.php';
    }
    
    return $template;
}

function moodbile_process_theme_variables() {
    global $CFG;
    
    //TODO: MEJORARLO -> DINAMIZARLO MAS CON EL SISTEMA;
    
    $title = $CFG['sitename'];
    
    $breadcrumb = moodbile_render_breadcrumb();
    
    $styles = moodbile_render_css(moodbile_get_theme_css());
    
    $scripts = moodbile_render_scripts(moodbile_get_client_scripts());
    
    $menu_items = moodbile_render_menu(moodbile_get_menu_items());
    
    $footer = "Developed by -- UPC"; //Provisional
    
    $variables = array("title", "styles", "scripts", "menu_items", "breadcrumb", "footer");
    $variables = compact($variables);
    
    return $variables;
}

function moodbile_get_theme_scripts($cache = FALSE) {
    global $CFG;
    
    //Comprobar con cache o no, si es que no, cargar librerias en independientemente, si es que si, comprobar que existe archivo, si no existe, generar archivo de cache <- funcion extra

    $info = moodbile_get_theme_info($CFG['theme']);
    $js = $info['js'];
    
    //TODO: COMPROBAR SI EXISTE Y DEPENDIENDO
    //_debug($js);
    return $js;
}

//ESTO PASARLO A LA LIBRERIA MODULE
function moodbile_get_client_scripts() {
    //Se encargara de unificar los js tanto de los modulos como del sistema
    global $CFG;
    
    
    $basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];
    
    //client scripts
    $js[] = 'misc/jquery/jquery.js';
    $js[] = 'misc/moodbile.js';
    $js[] = 'misc/breadcrumb.js';
    
    //module scripts
    foreach($active_modules as $module) {
        $module_files = moodbile_get_module($module);
        $file = array_intersect($module_files, array($module .'.mod.js'));
        $key = array_keys($file);
        $js[] = 'modules/'. $module .'/'.$file[$key[0]];
    }
    
    //theme scripts
    $themejs = moodbile_get_theme_scripts();
    
    //_debug($themejs);
    foreach ($themejs as $themejs){
        $js[] = 'themes/'.$CFG['theme'].'/'.$themejs;
    }
    
    //_debug($js);
    return $js;
}

function moodbile_render_scripts($js){
    //Renderizamos los scripts agrupados mediante la funcion moodbile_get_client_scripts()
    foreach($js as $js) {
        $scripts[] = '<script type="text/javascript" src="'.$js.'"></script>';
    }
    
    $scripts[] = moodbile_i18n_process_script();
    
    $scripts = implode("\n", $scripts);
    
    //var_dump($script);
    return $scripts;
}

function moodbile_get_theme_css($cache = FALSE) {
    global $CFG;
    
    $info = moodbile_get_theme_info($CFG['theme']);
    
    $css[] = "misc/reset.css"; //provisional
    
    foreach($info['css'] as $themecss){
        $css[] = 'themes/'.$CFG['theme'].'/'.$themecss;
    }
    
    //_debug($css);
    return $css;
}

function moodbile_render_css($css){

    foreach($css as $css) {
        $styles[] = "<link href=\"$css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\"/>";
    }
    
    $styles = implode("\n", $styles);
    
    //var_dump($script);
    return $styles;
}


function moodbile_get_theme_info($theme = "moodbile") {
    global $CFG;
    
    //$basepath = $CFG['basepath'];
    
    if(file_exists('themes/'. $theme .'/'. $theme .'.info')){
        $file_info = file('themes/'. $theme .'/'. $theme .'.info'); //BASEPATH!  
        //_debug($file_info);
        foreach($file_info as $file_info) {
            $file_info = explode(" = ", $file_info);
            
            
            if(!empty($file_info[1])) {
                if($file_info[0] == "css"){
                    $info['css'][] = $file_info[1];
                }
                
                if($file_info[0] == "js"){
                    $info['js'][] = $file_info[1];
                }
            }
            
        }
        //_debug($info);
    }
    
    return $info;
}

//funcion que renderizan diferentes variables con informacion, como podria ser $script
function moodbile_render_theme($template, $variables) {
    extract($variables, EXTR_SKIP);
    
    ob_start();
        header('Content-Type: text/html; charset=utf-8');
        
        include "$template";
        $content = ob_get_contents();
    ob_end_clean();
    
    print $content;
}