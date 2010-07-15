<?php

//Ponerlo mejor
global $CFG, $Moodbile;

/**  
 * Include all php libs
 *
 *  @param $exception,string: lib name you don't need to include
 *
 */
function moodbile_load_libs($exception = 'core'){
    global $CFG;
    
    $basepath = $CFG['basepath'];
    
    $files = scandir($basepath.'lib/php');
    $files = array_diff($files, array('.', '..', $exception.'.lib.php'));
    
    if(is_array($files)){
        foreach ($files as $file) {
            include('lib/php/'. $file);
        }
    }
}

/**
 * Include all php files in modules
 * 
 * @param $exception,string: lib name you don't need to include
 *
 */
function moodbile_include_modules(){
    global $CFG;
    
    //TODO: Mejorarlo
    if(moodbile_is_loged()) {
        $basepath = $CFG['basepath'];
        $active_modules = $CFG['active_modules'];
    
        foreach($active_modules as $module) {
            //Comprueba si existe el modulo activado (PHP), si es asi, incluye los archivos con necesarios
            //TODO: Mejorarlo.
                $module_files = moodbile_get_module($module);
                $file = array_intersect($module_files, array($module .'.mod.php'));
        
            if(!empty($file)) {
                $key = array_keys($file); //Mira el indice del array
            
                if(file_exists('modules/'. $module .'/'. $file[$key[0]])) {
                    include('modules/'. $module .'/'. $file[$key[0]]);
                }  
            }
        }
    }
}

/**
 * Get all JS libs
 *
 * @return array with Moodbile libraries
 */
function moodbile_get_client_scripts() {
    //Se encargara de unificar los js tanto de los modulos como del sistema
    global $CFG, $Moodbile;

    $basepath = $CFG['basepath'];
    $active_modules = $CFG['active_modules'];

    //client scripts
    $js[] = 'misc/jquery/jquery.js';
    $js[] = 'misc/jquery/jquery.cooquery.min.js';
    $js[] = 'misc/jquery/jquery.json.min.js';
    $js[] = 'misc/jquery/jquery.md5.js';
    
    //client libs
    $js[] = 'lib/js/core.lib.js';
    $js[] = 'lib/js/authentication.lib.js';
    $js[] = 'lib/js/ajax.lib.js';
    $js[] = 'lib/js/alert.lib.js';
    $js[] = 'lib/js/notifications.lib.js';
    $js[] = 'lib/js/webdb.lib.js';
    $js[] = 'lib/js/templates.lib.js';
    $js[] = 'lib/js/fx.lib.js';
    $js[] = 'lib/js/infoviewer.lib.js';
    $js[] = 'lib/js/filter.lib.js';
    $js[] = 'lib/js/toolbar.lib.js';
    $js[] = 'lib/js/breadcrumb.lib.js';
    $js[] = 'lib/js/footer.lib.js';
    
    //lang str library
    $js[] = moodbile_i18n();
    
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
    //moodbile_process_client_templates_script();

    return $js;
}

/**
 * Call the functions necessary to start the client
 */
function moodbile_start_client(){
    global $CFG;
    
    moodbile_load_libs();
    moodbile_include_modules();
    moodbile_render_theme();
}

/**
 * Show formated value
 *
 * @param $value: array/object to display
 */
function _debug($value){    
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}