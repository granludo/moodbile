<?
function moodbile_templates_load_html() {
    //Cargamos en un array, los templates que son por defecto
    //Comprobamos si existen templates en el directorio del tema, si es si, substituimos el default por el del tema. Si es no, nada.
    global $CFG, $Moodbile;

    $theme = $CFG['theme'];
    $templatepath = $CFG['basepath'].'misc/templates';
    $theme_templatepath = $CFG['basepath'].'themes/'.$theme.'/templates';

    if($templates_files = scandir($templatepath)) {

        $templates_files = array_diff($templates_files, array('.', '..'));
        //_debug($templates_files);
        //Comprobamos si los templates existen en el directorio del tema y a su vez, formamos la url donde estan los templates
        $templates_html = '<div id="templates">';
            foreach($templates_files as $template_filename) {
                if(!file_exists($theme_templatepath.'/'.$value)) {
                    $templates_html .= file_get_contents($templatepath.'/'.$template_filename);
                } else {
                  //  $templates_html .= file_get_contents($theme_templatepath.'/'.$template_filename);
                }
            }
        $templates_html .= '</div>';
        
        return  trim($templates_html);
    } else {
        moodbile_add_alert("error", "No_templates");
        
        return FALSE;
    }
}