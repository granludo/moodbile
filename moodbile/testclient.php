<?php
    require_once 'dummie/data.dum.php';
    require_once 'lib/core.lib.php';
    
    global $CFG;
    
    moodbile_load_libs();
    moodbile_include_modules();
    moodbile_i18n();
    
    $template = moodbile_get_template();
    $variables = moodbile_process_theme_variables();
    moodbile_render_theme($template, $variables);
?>