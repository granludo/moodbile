<?php
function moodbile_process_template_script() {
    //Cargamos en un array, los templates que son por defecto
    //Comprobamos si existen templates en el directorio del tema, si es si, substituimos el default por el del tema. Si es no, nada.
    global $CFG;

    $theme = $CFG['theme'];
    $templatepath = $CFG['basepath'].'/misc/templates';
    
    if($templates = scandir($templatepath)) {

        $templates = array_diff($templates, array('.', '..'));
        
    }
    
    //Comprobamos si los templates existen en el directorio del tema y a su vez, formamos la url donde estan los templates
    foreach($templates as $key => $value) {
        if(file_exists('themes/'. $theme .'/templates/'. $value)) {
            $templates[$key] = 'themes/'. $theme .'/'.$value;
        } else {
            $templates[$key] = 'misc/templates/'.$value;
        }
    }
    
    //Procesamos el array para ser imprimido.
    $script = '<script type="text/javascript">';
    $script .= 'Moodbile.templatesUrl = [];';
    foreach($templates as $template){
        $template_name = explode('/', $template);
        $template_name = explode('.', $template_name[count($template_name)-1]);
        $template_name = $template_name[0];
        
        $script .= 'Moodbile.templatesUrl["'.$template_name.'"] = "'.$template.'";';
    }
    $script .= '</script>';
    
    return $script;
}

function moodbile_get_theme_scripts() {
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
    global $CFG, $Moodbile;
    
    
    $basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];
    
    //client scripts
    $js_cache[] = 'misc/jquery/jquery.js';
    $js_cache[] = 'misc/jquery/jquery.cooquery.min.js';
    $js_cache[] = 'misc/jquery/jquery.json.min.js';
    $js_cache[] = 'misc/moodbile.js';
    $js_cache[] = 'misc/authentication.js';
    $js_cache[] = 'misc/ajax.js';
    $js_cache[] = 'misc/webdb.js';
    $js_cache[] = 'misc/templates.js';
    $js_cache[] = 'misc/toolbar.js';
    $js_cache[] = 'misc/breadcrumb.js';
    
    //module scripts        
    foreach($active_modules as $module) {
        $module_files = moodbile_get_module($module);
        $file = array_intersect($module_files, array($module .'.mod.js'));
        $key = array_keys($file);
        $js_cache[] = 'modules/'. $module .'/'.$file[$key[0]];
    }
    
    if($CFG['cache'] !== FALSE) {
        $js[] = moodbile_cache($CFG['cache'], $js_cache, 'js');
    } else {
        $js = $js_cache;
    }
    
  
    $themejs = moodbile_get_theme_scripts();
    foreach ($themejs as $themejs){
        $js[] = 'themes/'.$CFG['theme'].'/'.$themejs;
    }
    
    $Moodbile['js'] = $js;
    
    return $js;
}

function moodbile_render_scripts(){
    $js = moodbile_get_client_scripts();
    
    //Renderizamos los scripts agrupados mediante la funcion moodbile_get_client_scripts()
    if(is_array($js)) {
        foreach($js as $js) {
            $scripts[] = '<script type="text/javascript" src="'.$js.'"></script>';
        }
    } else {
        $scripts[] = '<script type="text/javascript" src="'.$js.'"></script>';
    }
    
    $scripts[] = moodbile_i18n_process_script();
    $scripts[] = moodbile_process_template_script();
    
    $scripts = implode("\n", $scripts);
    
    return $scripts;
}

function moodbile_get_theme_css() {
    global $CFG, $Moodbile;
    
    $info = moodbile_get_theme_info($CFG['theme']);
    
    $css[] = moodbile_cache_gzip_file("misc/reset.css", 'css'); //provisional
    
    foreach($info['css'] as $themecss){
        $css[] = 'themes/'.$CFG['theme'].'/'.$themecss;
    }
    
    $Moodbile['css'] = $css;
    
    return $css;
}

function moodbile_render_css(){
    $css = moodbile_get_theme_css();
    
    if(is_array($css)) {
        foreach($css as $css) {
            $styles[] = "<link href=\"$css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\"/>";
        }
    } else {
        $styles[] = "<link href=\"$css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\"/>";
    }
    
    $styles = implode("\n", $styles);

    return $styles;
}


function moodbile_get_theme_info($theme = "moodbile") {
    global $CFG;
    
    if(file_exists($CFG['basepath'].'themes/'. $theme .'/'. $theme .'.info')){
        $file_info = file($CFG['basepath'].'themes/'. $theme .'/'. $theme .'.info'); //BASEPATH!  

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
    }
    
    return $info;
}

//funcion que incluye las plantillas al index.php
function moodbile_get_theme_template() {
    global $CFG;
    
    $theme = $CFG['theme'];
    if(file_exists('themes/'. $theme .'/page.tpl.php')){
        $template = 'themes/'. $theme .'/page.tpl.php';
    }
    
    return $template;
}

function moodbile_process_theme_variables() {
    global $CFG;
    
    //TODO: MEJORARLO -> DINAMIZARLO MAS CON EL SISTEMA;
    $title = $CFG['sitename'];
    $styles = moodbile_render_css();
    $scripts = moodbile_render_scripts();
    $manifest = moodbile_cache_create_manifest();
    $breadcrumb = moodbile_render_breadcrumb(); //Sera descartado, se encargara el JS de generar el breadcrumb
    $menu_items = moodbile_render_menu(); //Sera descartado, se encargara el JS de generar los menus
    
    $variables = array("title", "styles", "scripts", "manifest", "breadcrumb", "menu_items");
    $variables = compact($variables);
    
    return $variables;
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