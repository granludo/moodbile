<?php
// This file is part of Moodbile -- http://moodbile.org
//
// Moodbile is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodbile is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodbile.  If not, see <http://www.gnu.org/licenses/>.

/**
 * File Related External Functions
 *
 * @package MoodbileServer
 * @subpackage Files
 * @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
 * @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
 *
 * @author Jordi Piguillem
 * @author Nikolas Galanis
 * @author Oscar Martinez Llobet
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;
require_once(dirname(__FILE__).'/../config.php');
global $MBL;
global $CFG;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($CFG->dirroot.'/files/externallib.php');

class moodbileserver_files_external extends external_api {

//    /**
//     * Makes sure user may execute functions in this context.
//     * @param object $context
//     * @return void
//     */
//    protected static function validate_context($context) {
//        global $CFG;
//
//        if (empty($context)) {
//            throw new invalid_parameter_exception('Context does not exist');
//        }
////        if (empty(self::$contextrestriction)) {
////            self::$contextrestriction = get_context_instance(CONTEXT_SYSTEM);
////        }
//        $rcontext = get_context_instance(CONTEXT_SYSTEM);
//
//        if ($rcontext->contextlevel == $context->contextlevel) {
//            if ($rcontext->id != $context->id) {
//                throw new restricted_context_exception();
//            }
//        } else if ($rcontext->contextlevel > $context->contextlevel) {
//            throw new restricted_context_exception();
//        } else {
//            $parents = get_parent_contexts($context);
//            if (!in_array($rcontext->id, $parents)) {
//                throw new restricted_context_exception();
//            }
//        }
//    }

    public static function upload_file_parameters() {
        return new external_function_parameters(
            array(
                'filename' => new external_value(PARAM_FILE, 'Filename', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'filedata' => new external_value(PARAM_TEXT, 'Base64 encoded file data', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
            )
        );
    }

    public static function upload_file($filename, $filedata) {
        global $USER;

        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);

        //$params = self::validate_parameters(self::upload_file_parameters(), array('params' => $parameters));

        $user_context = get_context_instance(CONTEXT_USER, $USER->id);
        $contextid = $user_context->id;
        $component = 'user';
        $filearea = 'private';
        $itemid = 0;
        $filepath = '/';

        $dir = make_upload_directory('temp/wsupload');

        if (empty($filename)) {
            $filenamets = uniqid('wsupload').'_'.time().'.tmp';
        } else {
            $filenamets = $filename;
        }

        if (file_exists($dir.$filenamets)) {
            $savedfilepath = $dir.uniqid('m').$filenamets;
        } else {
            $savedfilepath = $dir.$filenamets;
        }

        file_put_contents($savedfilepath, base64_decode($filedata));
        unset($filedata);
        $browser = get_file_browser();

        // check existing file
        if ($file = $browser->get_file_info($user_context, $component, $filearea, $itemid, $filepath, $filenamets)) {
            throw new moodle_exception('fileexist');
        }

        // move file to filepool
        if ($dir = $browser->get_file_info($user_context, $component, $filearea, $itemid, $filepath, '.')) {
            $info = $dir->create_file_from_pathname($filenamets, $savedfilepath);
            $fs = get_file_storage();
            $file = $fs->get_file($contextid, $component, $filearea, $itemid, $filepath, $filenamets);
            $params = $info->get_params();
            unlink($savedfilepath);
            return array(
                'fileid'=>$file->get_id(),
                'filename'=>$filename,
                );

        } else {
            throw new moodle_exception('nofile');
        }
    }

    public static function upload_file_returns() {
        return new external_single_structure(
             array(
                 'fileid' => new external_value(PARAM_INT, 'File id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                 'filename' => new external_value(PARAM_FILE, 'Filename', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
             )
        );
    }

    public static function get_user_filesinfo_parameters() {
        return new external_function_parameters(
                array(
                    'startpage' => new external_value(PARAM_INT, 'Start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                    'n'         => new external_value(PARAM_INT, 'Page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
                )
        );
    }

    public static function get_user_filesinfo($startpage, $n) {
        global $USER;

        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);

        $fs = get_file_storage();
        $user_context = get_context_instance(CONTEXT_USER, $USER->id);
        $contextid = $user_context->id;
        $results = $fs->get_area_files($contextid, 'user', 'private', 0, "sortorder, itemid, filepath, filename" , false);
        if (empty($results)) {
           throw new moodle_exception('generalexceptionmessage','moodbile_files', '','No files found');
        }
        $ret = array();
        $i=0;
        $begin = $startpage*$n;
        foreach ($results as $file) {//TODO improve this loop
            if ($file->get_filename() !== '.') {
                if ($i >= $begin && $i < $begin+$n ) {
                    $ret[] = array( 'fileid' => $file->get_id(), 'filename' => $file->get_filename());
                }
                $i++;
                if ($i == $begin+$n ) break;
            }
        }
        if (empty($ret)) {
           throw new moodle_exception('generalexceptionmessage','moodbile_files', '','No files found');
        }
        return $ret;
    }

    public static function get_user_filesinfo_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'fileid' => new external_value(PARAM_INT, 'File id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                    'filename' => new external_value(PARAM_FILE, 'Filename', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                )
            )
        );
    }

//    public static function download_file_parameters() {
//        return new external_function_parameters(
//            array(
//                'fileid' => new external_value(PARAM_INT, 'File id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
//            )
//        );
//    }
//
//    public static function download_file($fileid) {//HACK
//        global $CFG;
//        $system_context = get_context_instance(CONTEXT_SYSTEM);
//        self::validate_context($system_context);
//
//        //$params = self::validate_parameters(self::download_file_parameters(), array('params' => $parameters));
//
//        $fs = get_file_storage();
//        $browser = get_file_browser();
//        $f = $fs->get_file_by_id($fileid);
//        if (!$f) {
//           throw new moodle_exception('fileexist');
//        }
//        $file = $browser->get_file_info(get_context_instance_by_id($f->get_contextid()), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename());
//        if (!$file) {
//            throw new moodle_exception('nopermissions','moodbile_files', '',"No permission");
//        }
//        $url = $file->get_url();
//
//        $names = array(
//                'MOODLEID_'.$CFG->sessioncookie,
//                'MoodleSession'.$CFG->sessioncookie,
//                'MoodleSessionTest'.$CFG->sessioncookie
//        );
//        $ret='';
//        foreach ($names as $name) {
//            if (isset($_COOKIE[$name])) {
//               $ret .= $name.'='.$_COOKIE[$name].'; ';
//            }
//        }
//        $ret = substr($ret, 0, -1);
//
//        session_write_close();//TODO warning, not sure of consequences, used to unlock session or curl will hang the server
//
//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_COOKIE, $ret);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        $response = curl_exec ($ch);
//        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//
//        if ($http_status != 200) {
//            throw new moodle_exception('generalexceptionmessage','moodbile_files', '','Could not download file');
//        }
//
//        return array('filesize' => $file->get_filesize(), 'filedata' => base64_encode($response));
//    }
//
//    public static function download_file_returns() {
//        return new external_single_structure(
//            array(
//                'filesize' => new external_value(PARAM_INT, 'Filesize once decoded', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
//                'filedata' => new external_value(PARAM_TEXT, 'File data base64 encoded', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
//            )
//        );
//    }

    public static function get_file_url_parameters() {
        return new external_function_parameters(
            array(
                'fileid' => new external_value(PARAM_INT, 'File id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    public static function get_file_url($fileid) {
        global $CFG, $DB, $USER;
        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);

        $fs = get_file_storage();
        $f = $fs->get_file_by_id($fileid);
        if (!$f) {
           throw new moodle_exception('nofile');
        }
        if ($f->get_filesize() == 0) {
            throw new moodle_exception('invalidfile');
        }

        $url = "{$CFG->wwwroot}/local/moodbileserver/files/pluginfile.php/{$f->get_contextid()}/{$f->get_component()}/{$f->get_filearea()}";
        $filename = $f->get_filename();
        $url = $url.$f->get_filepath().$f->get_itemid().'/'.$filename;

        //token generation
        // make sure the token doesn't exist (even if it should be almost impossible with the random generation)
        $numtries = 0;
        do {
            $numtries ++;
            $generatedtoken = md5(uniqid(rand(),1));
            if ($numtries > 5){
                throw new moodle_exception('tokengenerationfailed');
            }
        } while ($DB->record_exists('mbl_tokens', array('token'=>$generatedtoken)));

        $url = $url . '/' . $generatedtoken . '/' . $fileid;

        //save to database
        $record = new stdClass();
        $record->token = $generatedtoken;
        $record->userid = $USER->id;
        $record->fid = $fileid;
        $record->timedue = time() + (3*60);
        $DB->insert_record('mbl_tokens', $record);

        //return
        return array(
                'filename' =>$filename,
                'filesize' => $f->get_filesize(),
                'mime' => $f->get_mimetype(),
                'url' => $url,
                'timedue' => $record->timedue
        );
    }

    public static function get_file_url_returns() {
        return new external_single_structure(
            array(
                'filename' => new external_value(PARAM_TEXT, 'Filename', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'filesize' => new external_value(PARAM_INT, 'Filesize', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'mime' => new external_value(PARAM_TEXT, 'File MIME', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'url' => new external_value(PARAM_URL, 'File url', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'timedue' => new external_value(PARAM_INT, 'Time when access expires', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            )
        );
    }

}