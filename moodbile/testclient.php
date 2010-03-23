<?php
    include 'lib/core.lib.php';
    include 'dummie/data.dum.php';
    
    moodbile_load_libs();
    moodbile_include_modules();
    moodbile_i18n();
    
    $template = moodbile_get_theme_template();
    $variables = moodbile_process_theme_variables();
    moodbile_render_theme($template, $variables);
?>