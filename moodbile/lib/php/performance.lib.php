<?php
function moodbile_performance($cache = "basic", $files = NULL, $filetype = NULL) {

    //moodbile_performance_check_last_mod();

    switch ($cache) {
        case "basic":
            $file = moodbile_performance_minify_files($files, $filetype);

            break;
        case "medium":
            $file = moodbile_performance_minify_files($files, $filetype);
            $file = moodbile_performance_gzip_file($file, $filetype);

            break;
        case "advanced":
            $file = moodbile_performance_minify_files($files, $filetype);
            $file = moodbile_performance_gzip_file($file, $filetype);
            moodbile_performance_make_htaccess();

            break;
    }

    return $file;
}

function moodbile_performance_check_last_mod() {
    global $CFG;

    $cachepath = $CFG['basepath'].'/misc/cache';
    $htaccess_file = "misc/cache/.htaccess";
    $actual_date = mktime();
    $expires = 60*60*24*7*$CFG['cacheTime'];

    if(file_exists($htaccess_file)) {
        $cache_file_last_mod = filemtime($htaccess_file);

        if(($cache_file_last_mod + $expires) >=  $actual_date) {
            if($cache_files = scandir($cachepath)) {
                $cache_files = array_diff($cache_files, array('.', '..'));

                foreach($cache_files as $file){
                    unlink("misc/cache/".$file);
                }
            }
        }
    }
}

function moodbile_performance_minify_files($files, $filetype) {
    global $CFG;

    $filename = "misc/cache/moodbile.min.$filetype";

    if (!is_array($files)) {
        $files = (array) $files;
    }

    if (!file_exists($filename)) {
        if(isset($files)){
            $content_to_merge = "";
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $file_content = file_get_contents("./".$file);
                    $content_to_merge .= $file_content."\n";
                    
                } else {
                    moodbile_add_alert("error", "file_not_exist");
                }
            }
            
            if($filetype == "js") { 
                $content_to_merge = moodbile_performance_minify_js($content_to_merge);
            } else {
                $content_to_merge = moodbile_performance_minify_css($content_to_merge);
                $content_to_merge = moodbile_performance_make_css_images_cacheable($content_to_merge);
            }

            file_put_contents($CFG['basepath'].$filename, $content_to_merge);
        }
    }

    return $filename;
}
function moodbile_performance_minify_js( $js ) {
    global $CFG;
    include($CFG['basepath'].'misc/jsmin.php');

    $js = JSMin::minify($js);

    return $js;
}

function moodbile_performance_minify_css( $css ) {
    $css = preg_replace( '#\s+#', ' ', $css );
    $css = preg_replace( '#/\*.*?\*/#s', '', $css );
    $css = str_replace( '; ', ';', $css );
    $css = str_replace( ': ', ':', $css );
    $css = str_replace( ' {', '{', $css );
    $css = str_replace( '{ ', '{', $css );
    $css = str_replace( ', ', ',', $css );
    $css = str_replace( '} ', '}', $css );
    $css = str_replace( ';}', '}', $css );

    return trim($css);
}

function moodbile_performance_make_css_images_cacheable($css_string) {
    global $CFG;

    //replace string, old css image path by new path in cache
    $image_path_in_css = '../images';
    $image_path_in_cache = 'files/images';

    $css_string = str_replace($image_path_in_css, $image_path_in_cache, $css_string);

    //make theme & css images cacheable
    $path_to_css_images = $CFG['basepath'].'themes/'.$CFG['theme'].'/images';
    $type = 'images';
    moodbile_performance_make_cacheable($path_to_css_images, $type);

    return $css_string;
}

//$input = dir or file
//$type = to create dir and save in it
function moodbile_performance_make_cacheable($source, $type = NULL) {
    global $CFG;

    $file_cache_path = $CFG['basepath'].'misc/cache/files';
    $file_cache_path_by_type = $CFG['basepath'].'misc/cache/files/'.$type.'/';
    
    if(!file_exists($file_cache_path)) mkdir($file_cache_path);
    if(!file_exists($file_cache_path_by_type)) mkdir($file_cache_path_by_type);
    
    if(is_file($source)) {
        $filename = explode('/', $source);
        $filename = $filename[count($filename)-1];
        
        copy($source, $file_cache_path.$filename);
    } else {
        if($files = scandir($source)) {
            $files = array_diff($files, array('.', '..'));
            
            foreach($files as $file){
                $filename = explode('/', $file);
                $filename = $filename[count($filename)-1];
                
                if(!copy($source.'/'.$file, 'misc/cache/files/'.$type.'/'.$filename)) {
                     moodbile_add_alert("error", "files_not_cached");
                }
            }
        }
    }
}

function moodbile_performance_gzip_file($file, $filetype) {
    global $CFG;

    $gzfilename = "misc/cache/moodbile.min.gz$filetype";
    
    if (!file_exists($gzfilename) && file_exists($file)) {
        $data = file_get_contents($CFG['basepath'].$file);
        $gzdata = gzencode($data, 9);

        file_put_contents($CFG['basepath'].$gzfilename, $gzdata);
    }
    
    return $gzfilename;
}

function moodbile_performance_make_htaccess() {
    global $CFG;
    $htaccess_template = "misc/htaccess.inc";
    $htaccess_file = "misc/cache/.htaccess";

    if(!file_exists($CFG['basepath'].$htaccess_file)) {
        $data = file_get_contents($CFG['basepath'].$htaccess_template);
        file_put_contents($CFG['basepath'].$htaccess_file, $data);
    }
}

//Es necesario colocar en globales los js
function moodbile_performance_create_manifest () {
    global $CFG, $Moodbile;
    
    $filename = "misc/cache/moodbile.wac";
    if($CFG['cache'] == "advanced") {
        if (!file_exists($filename)) {
            $cacheable_files = array_merge_recursive((array) $Moodbile['css'], (array) $Moodbile['js']);
            $manifest['CACHE MANIFEST'] = $cacheable_files;
            $manifest['NETWORK'] = (array) "testclient.php";
        
            if (is_array($manifest)) {
                $manifest_content = "";
                foreach($manifest as $key => $value) {
                    $manifest_content .= "$key:\n";
                    if (is_array($value)) {
                        $manifest_content .= implode("\n", $value);
                    }
                    $manifest_content .= "\n";
                }
            }
            
            $fp = fopen($CFG['basepath'].$filename, "w+");
            fwrite($fp, $manifest_content);
            fclose($fp);
        }
        return "manifest=$filename";
    } else {
        return "";
    }
}

function moodbile_performance_set_page_headers($content){  
    global $CFG;

    $etag = md5($content);
    $expires = 60*60*24*7*$CFG['cacheTime']; // seconds, minutes, hours, days
    header("Pragma: public");
    header("Cache-Control: maxage=".$expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
    header("Etag: $etag");
}

function moodbile_performance_create_page_cacheable($content) {
    //Crea si es necesario y devuelve ruta de la pagina a incluir o false para que cree pagina
    $filename = "misc/cache/page.tpl.html";

    if(!file_exists($filename)) {
        file_put_contents($filename, $content);
    }
}