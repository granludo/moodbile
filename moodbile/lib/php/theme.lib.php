<?php
function moodbile_process_client_templates_script() {
    //Cargamos en un array, los templates que son por defecto
    //Comprobamos si existen templates en el directorio del tema, si es si, substituimos el default por el del tema. Si es no, nada.
    global $CFG, $Moodbile;

    $theme = $CFG['theme'];
    $templatepath = $CFG['basepath'].'/misc/templates';

    if($templates_files = scandir($templatepath)) {

        $templates_files = array_diff($templates_files, array('.', '..'));
        
        //Comprobamos si los templates existen en el directorio del tema y a su vez, formamos la url donde estan los templates
        foreach($templates_files as $key => $value) {
            $template_name = explode(".", $value);
            $template_name = $template_name[0];

            if(file_exists('themes/'. $theme .'/templates/'. $value)) {
                $templates[$template_name] = 'themes/'. $theme .'/'.$value;
            } else {
                $templates[$template_name] = 'misc/templates/'.$value;
            }

            $templates_lastmod[$template_name] = filemtime($templates[$template_name]);
        }
    } else {
        moodbile_add_alert("error", "No_templates");
    }

    //Procesamos el array para ser imprimido.
    $Moodbile['djs']['templatesUrl'] =  $templates;
    $Moodbile['djs']['templatesLastMod'] =  $templates_lastmod;
}

function moodbile_get_theme_scripts() {
    global $CFG;

    $info = moodbile_get_theme_info($CFG['theme']);
    
    if(isset($info['js'])) {
        $js = $info['js'];
        
        return $js;
    }
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

    $js[] = 'lib/js/core.js';
    $js[] = 'lib/js/authentication.js';
    $js[] = 'lib/js/ajax.js';
    $js[] = 'lib/js/alert.js';
    $js[] = 'lib/js/webdb.js';
    $js[] = 'lib/js/templates.js';
    $js[] = 'lib/js/fx.js';
    $js[] = 'lib/js/toolbar.js';
    $js[] = 'lib/js/breadcrumb.js';
    $js[] = 'lib/js/footer.js';
    
    //module scripts        
    foreach($active_modules as $module) {
        $module_files = moodbile_get_module($module);
        $file = array_intersect($module_files, array($module .'.mod.js'));
        $key = array_keys($file);
        $js[] = 'modules/'. $module .'/'.$file[$key[0]];
    }
    
    //Solucionar problemas a la hora de abrir la directorios y meterlo en cache
    if($themejs = moodbile_get_theme_scripts()) {
        foreach ($themejs as $themejs){
            $js[] = 'themes/'.$CFG['theme'].'/'.$themejs;
        }
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
            $scripts[] = '<script src="'.$js.'"></script>';
        }
    } else {
        $scripts[] = '<script src="'.$js.'"></script>';
    }
    
    $scripts[] = moodbile_render_dinamic_scripts();
    
    $scripts = implode("\n", $scripts);

    return $scripts;
}

function moodbile_render_dinamic_scripts() {
    global $Moodbile;
    
    if (is_array($Moodbile['djs'])) {
        $js_string = '<script>';
        foreach($Moodbile['djs'] as $varname => $array) {
            
            $js_var_name = "Moodbile.$varname";
            
            foreach ($array as $varname1 => $value) {
                if(is_array($value)) {
                    foreach($value as $varname2 => $value) {
                        $js_string .= $js_var_name."['$varname1'][$varname2] = '$value';";
                    }
                } else {
                    $js_string .= $js_var_name."['$varname1'] = '$value';";
                }
            }
        }
        $js_string .= '</script>';
    }
    
    return $js_string;
}

function moodbile_get_theme_css() {
    global $CFG, $Moodbile;
    
    $info = moodbile_get_theme_info($CFG['theme']);
    $device = moodbile_device();
    
    $css[] = "misc/reset.css";
    
    foreach($info['css'] as $themecss){
        $css[] = 'themes/'.$CFG['theme'].'/'.$themecss;
        $css_device = explode(".", $themecss);
        $css_device = 'themes/'.$CFG['theme'].'/'.$css_device[0].'.'.strtolower($device).'.'.$css_device[1];
        if(file_exists($css_device)) {
            $css[] = $css_device;
        }
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
    
    $variables = array("title", "styles", "scripts", "manifest", "breadcrumb", "menu_items");
    $variables = compact($variables);
    
    return $variables;
}

//funcion que renderizan diferentes variables con informacion, como podria ser $script
function moodbile_render_theme() {
    global $CFG;
    
    $filename = "misc/cache/page.tpl.html";
    
    //moodbile_performance_check_last_mod();
    
    if($CFG['cache'] != false && file_exists($filename)) {
        $template = $filename;
    } else {
        $template = moodbile_get_theme_template();
        $variables = moodbile_process_theme_variables();
        extract($variables, EXTR_SKIP);
    }
    
    ob_start('ob_gzhandler');
        header('Content-Type: text/html; charset=utf-8');
        
        include "$template";
        $content = ob_get_contents();
        
        if($CFG['cache'] != false) {
            moodbile_performance_create_page_cacheable($content);
            moodbile_performance_set_page_headers($content);
        }
    ob_end_flush();

    //print $content;
}