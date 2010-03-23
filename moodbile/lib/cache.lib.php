<?
function moodbile_cache($cache = "basic", $files = NULL, $filetype = NULL) {
    //Comprueba si hace falta actualizacion del fichero
    //Empieza logica
    switch ($cache) {
        case "basic":
            $file = moodbile_cache_minify_files($files, $filetype);
        
            break;
        case "medium":
            $file = moodbile_cache_minify_files($files, $filetype);
            $file = moodbile_cache_gzip_file($file, $filetype);
            
            break;
        case "advanced":
            $file = moodbile_cache_minify_files($files, $filetype);
            $file = moodbile_cache_gzip_file($file, $filetype);
            
            break;
    }
    
    return $file;
}

function moodbile_cache_minify_files($files, $filetype) {
    global $CFG;
    include($CFG['basepath'].'misc/jsmin.php');
    
    $filename = "misc/cache/moodbile.min.$filetype";
    
    if (!is_array($files)) {
        $files = (array) $files;
    }
    
    if (!file_exists($filename)) {
        if(isset($files)){
            $content_to_merge = "";
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $file_content = file_get_contents($CFG['basepath'].$file, 1);
                    $content_to_merge .= $file_content."\n";
                    
                } else {
                    echo 'no existe';
                }
            }
                
            $content_to_merge = JSMin::minify($content_to_merge);
        
            if (!$merged_file = fopen($filename, 'a+')) {
                echo "Cannot open file ($filename)";
                exit;
            }
                
            if (fwrite($merged_file, $content_to_merge) === FALSE) {
                echo "Cannot write to file ($filename)";
                exit;
            }

            fclose($merged_file);
        }
    }
    
    return $filename;
}

function moodbile_cache_gzip_file($file, $filetype) {
    global $CFG, $Moodbile;

    $gzfilename = "misc/cache/moodbile.min.gz$filetype";

    if(!file_exists($gzfilename) && file_exists($file)) {
        $data = file_get_contents($CFG['basepath'].$file);
            
        $gzdata = gzencode($data, 9);
        $fp = fopen($CFG['basepath'].$gzfilename, "w");
        fwrite($fp, $gzdata);
        fclose($fp);    
    }
    
    return $gzfilename;
}

//Es necesario colocar en globales los js
function moodbile_cache_create_manifest() {
    global $CFG, $Moodbile;
    
    $filename = "misc/cache/moodbile.wac";
    if($CFG['cache'] == "advanced") {
        if (!file_exists($filename)) {
            $cacheable_files = array_merge_recursive($Moodbile['css'], $Moodbile['js']);
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