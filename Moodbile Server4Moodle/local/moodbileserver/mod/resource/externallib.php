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
 * Resource External API Library
 *
 * @package MoodbileServer
 * @subpackage Resource
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

require_once(dirname(__FILE__).'/../../config.php');
global $MBL;
require_once("$MBL->mdllibdir/externallib.php");
require_once("$MBL->mblroot/mod/resource/resource.class.php");

class moodbileserver_resource_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_resource_parameters() {
        return new external_function_parameters (
            array(
                'resourceid' => new external_value(PARAM_INT,  'Resource identifier', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns desired resource
     *
     * @param int resourceid
     *
     * @return resource
     */
    public static function get_resource($resourceid) {
        global $DB;


        if (!$resource = $DB->get_record('resource', array('id'=>$resourceid))) {
            throw new moodle_exception('generalexceptionmessage','moodbile_resource', '','Resource not found');
        }
        $cm = get_coursemodule_from_instance('resource', $resource->id, $resource->course, false, MUST_EXIST);

        $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
        require_capability('mod/resource:view', $context);

        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'mod_resource', 'content', 0, 'sortorder DESC, id ASC', false); // TODO: this is not very efficient!!
        if (count($files) < 1) {
            throw new moodle_exception('generalexceptionmessage','moodbile_resource', '','File not found');
        } else {
            $file = reset($files);
            unset($files);

            $resource->fileid = $file->get_id();
            $resource->filemimetype = $file->get_mimetype();
            $resource->filesize = $file->get_filesize();
            $return = new Resource($resource);
            $return = $return->get_data();

            return $return;
        }

        throw new moodle_exception('generalexceptionmessage','moodbile_resource', '','Error');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_resource_returns() {
        return Resource::get_class_structure();
    }
}
