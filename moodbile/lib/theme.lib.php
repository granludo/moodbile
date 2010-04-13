<?php
function moodbile_process_client_templates_script() {
    //Cargamos en un array, los templates que son por defecto
    //Comprobamos si existen templates en el directorio del tema, si es si, substituimos el default por el del tema. Si es no, nada.
    global $CFG, $Moodbile;

    $theme = $CFG['theme'];
    $templatepath = $CFG['basepath'].'/misc/templates';
    
    if($templates_files = scandir($templatepath)) {

        $templates_files = array_diff($templates_files, array('.', '..'));
        
    }
    
    //Comprobamos si los templates existen en el directorio del tema y a su vez, formamos la url donde estan los templates
    foreach($templates_files as $key => $value) {
        $template_name = explode(".", $value);
        $template_name = $template_name[0];
        
        if(file_exists('themes/'. $theme .'/templates/'. $value)) {
            $templates[$template_name] = 'themes/'. $theme .'/'.$value;
        } else {
            $templates[$template_name] = 'misc/templates/'.$value;
        }
    }
    
    //Procesamos el array para ser imprimido.
    $Moodbile['djs']['templatesUrl'] =  $templates;
}

function moodbile_get_theme_scripts() {
    global $CFG;

    $info = moodbile_get_theme_info($CFG['theme']);

    $js = $info['js'];
    
    return $js;
}

//ESTO PASARLO A LA LIBRERIA MODULE
function moodbile_get_client_scripts() {
    //Se encargara de unificar los js tanto de los modulos como del sistema
    global $CFG, $Moodbile;
    
    
    $basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];
    
    //client scripts
    $js[] = 'misc/jquery/jquery.js';
    $js[] = 'misc/jquery/jquery.cooquery.min.js';
    $js[] = 'misc/jquery/jquery.json.min.js';
    $js[] = 'misc/moodbile.js';
    $js[] = 'misc/authentication.js';
    $js[] = 'misc/ajax.js';
    $js[] = 'misc/webdb.js';
    $js[] = 'misc/templates.js';
    $js[] = 'misc/toolbar.js';
    $js[] = 'misc/breadcrumb.js';
    $js[] = 'misc/footer.js';
    
    //module scripts        
    foreach($active_modules as $module) {
        $module_files = moodbile_get_module($module);
        $file = array_intersect($module_files, array($module .'.mod.js'));
        $key = array_keys($file);
        $js[] = 'modules/'. $module .'/'.$file[$key[0]];
    }
    
    //Solucionar problemas a la hora de abrir la directorios y meterlo en cache
    $themejs = moodbile_get_theme_scripts();
    foreach ($themejs as $themejs){
        $js[] = 'themes/'.$CFG['theme'].'/'.$themejs;
    }
    
    if($CFG['cache'] !== FALSE) {
        $js = moodbile_performance($CFG['cache'], $js, 'js');
    }
    
    $Moodbile['js'] = $js;
    
    //Ejecutar funciones que cargan arrays asociativos a globales
    moodbile_process_client_templates_script();
    
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
    
    $scripts[] = moodbile_render_dinamic_scripts();
    
    $scripts = implode("\n", $scripts);

    return $scripts;
}

function moodbile_render_dinamic_scripts() {
    global $Moodbile;
    
    if (is_array($Moodbile['djs'])) {
        $js_string = '<script type="text/javascript">';
        foreach($Moodbile['djs'] as $varname => $array) {
            
            $js_var_name = "Moodbile.$varname";
            
            foreach ($array as $key => $value) {
                $js_string .= $js_var_name."['$key'] = '$value';";
            }
        }
        $js_string .= '</script>';
    }
    
    return $js_string;
}

function moodbile_get_theme_css() {
    global $CFG, $Moodbile;
    
    $info = moodbile_get_theme_info($CFG['theme']);
    
    $css[] = "misc/reset.css";
    
    foreach($info['css'] as $themecss){
        $css[] = 'themes/'.$CFG['theme'].'/'.$themecss;
    }
    
    if($CFG['cache'] !== FALSE) {
        $css = moodbile_performance($CFG['cache'], $css, 'css');
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

//TODO: REVISAR ESTA PARTE DE CODIGO!!!!!!
function moodbile_get_theme_info() {
    global $CFG;
    
    $theme = $CFG['theme'];
    if(file_exists($CFG['basepath'].'themes/'. $theme .'/'. $theme .'.info')){
        $file_info = file($CFG['basepath'].'themes/'. $theme .'/'. $theme .'.info');

        foreach($file_info as $file_info) {
            $file_info = explode(" = ", $file_info);
            
            //_debug($file_info);

            if(!empty($file_info[1])) {
                if($file_info[0] == "css"){
                    $info['css'][] = trim($file_info[1]);
                }
                
                if($file_info[0] == "js"){
                    $info['js'][] = trim($file_info[1]);
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
    $manifest = moodbile_performance_create_manifest();
    $breadcrumb = moodbile_render_breadcrumb(); //Sera descartado, se encargara el JS de generar el breadcrumb
    $menu_items = moodbile_render_menu(); //Sera descartado, se encargara el JS de generar los menus
    
    $variables = array("title", "styles", "scripts", "manifest", "breadcrumb", "menu_items");
    $variables = compact($variables);
    
    return $variables;
}

//funcion que renderizan diferentes variables con informacion, como podria ser $script
function moodbile_render_theme($template, $variables) {
    extract($variables, EXTR_SKIP);
    
    ob_start('ob_gzhandler');
        header('Content-Type: text/html; charset=utf-8');
        
        include "$template";
        $content = ob_get_contents();
        moodbile_performance_set_page_headers($content);
    ob_end_flush();

    //print $content;
}