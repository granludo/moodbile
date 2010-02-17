<?
    require_once 'dummie/data.dum.php';
    require_once 'lib/core.lib.php';
    
    global $CFG;
    
    moodbile_load_libs();
    moodbile_i18n();
    $module_list = moodbile_get_module();
    
    
    //_debug($CFG);
    //_debug($COURSES);
    //_debug($module_list);
    
    moodbile_include_modules();
    //_debug(moodbile_get_theme_info($CFG['theme']));
    //moodbile_get_css(FALSE);
    moodbile_get_menu_items();
    
    
    $template = moodbile_get_template();
    $variables = moodbile_process_theme_variables();
    moodbile_render_theme($template, $variables);