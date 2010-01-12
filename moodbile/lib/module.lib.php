<?
/*  Get Module, include selectec module
 *
 *  @param $module,string: Module name
 *  @param $type,string: Module type, core or modules
 *
 *  return: array with module files
 */
function moodbile_get_module($module="courses") {
    global $CFG;
    
    $basepath = $CFG['basepath'];
    
    if($module_files = scandir($basepath .'/modules/'. $module)) {
        
        $module_files = array_diff($module_files, array('.', '..'));
        
        return $module_files;
    } else {
        return FALSE;
    }
}