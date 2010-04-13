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
    
    $files = scandir($basepath.'lib');
    $files = array_diff($files, array('.', '..', $exception.'.lib.php'));
    
    if(is_array($files)){
        foreach ($files as $file) {
            include('lib/'. $file);
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
        
            //_debug($file);
        
            if(!empty($file)) {
                $key = array_keys($file); //Mira el indice del array
            
                if(file_exists('modules/'. $module .'/'. $file[$key[0]])) {
                    include('modules/'. $module .'/'. $file[$key[0]]);
                }  
            }
        }
    }
}

function moodbile_load_globals(){
//TODO: funcion encargada de generar los globales necesarios para un inicio correcto.
}
function moodbile_start_client(){
//TODO: funcion encargada de llamar a todas las funciones necesarias para generar la pagina del cliente;
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