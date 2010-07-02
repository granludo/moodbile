<?php

//Ponerlo mejor
global $CFG, $Moodbile;

/*  Include all libs
 *
 *  @param $exception,string: lib name you don't need to include
 *
 */
function moodbile_load_libs($exception = 'core'){
    global $CFG;
    
    $basepath = $CFG['basepath'];
    //$basepath = "./";
    
    $files = scandir($basepath.'lib/php');
    $files = array_diff($files, array('.', '..', $exception.'.lib.php'));
    
    if(is_array($files)){
        foreach ($files as $file) {
            include('lib/php/'. $file);
        }
    }
    
    //OK
    //_debug(dirname(__FILE__));
}

/*  Include all php files in modules
 *
 *  @param $exception,string: lib name you don't need to include
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
    $js[] = 'lib/js/core.js';
    $js[] = 'lib/js/authentication.js';
    $js[] = 'lib/js/ajax.js';
    $js[] = 'lib/js/alert.js';
    $js[] = 'lib/js/notifications.js';
    $js[] = 'lib/js/webdb.js';
    $js[] = 'lib/js/templates.js';
    $js[] = 'lib/js/fx.js';
    $js[] = 'lib/js/infoviewer.js';
    $js[] = 'lib/js/filter.js';
    $js[] = 'lib/js/toolbar.js';
    $js[] = 'lib/js/breadcrumb.js';
    $js[] = 'lib/js/footer.js';
    
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

function moodbile_load_globals(){
//TODO: funcion encargada de generar los globales necesarios para un inicio correcto.
}

function moodbile_start_client(){
    global $CFG;
    
    moodbile_load_libs();
    moodbile_include_modules();
    
    
    //moodbile_i18n();
    moodbile_render_theme();
}

/*  Show formated value
 *
 *  @param $value: array/object to display
 *
 */
function _debug($value){    
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}