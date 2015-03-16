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
 * System External API
 *
 * @package MoodbileServer
 * @subpackage System
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
require_once($MBL->mdllibdir.'/externallib.php');

class moodbileserver_system_external extends external_api {


    public static function get_server_timezone_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_server_timezone() {
        global $CFG;

        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);

        $timezone = $CFG->timezone;
        if ($timezone === false or $timezone === 99) {
            $seconds = date_offset_get(new DateTime);
            return array ('timezoneoffset' => $seconds / 3600);
        }
        return array ('timezoneoffset' => $timezone);
    }

    public static function get_server_timezone_returns() {
        return new external_single_structure(
            array(
                'timezoneoffset' => new external_value(PARAM_FLOAT, 'The offset you have to add to a time to have it in moodle time.', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_capabilities_parameters() {
        return new external_function_parameters(
            array(
                'context' => new external_value(PARAM_TEXT,'Context: SYSTEM, USER, COURSECAT, COURSE, MODULE, BLOCK', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'id' => new external_value(PARAM_INT, 'Id for the context, i.e. course id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'modulename' => new external_value(PARAM_ALPHA, 'If context == module, module name which the id applies to', VALUE_OPTIONAL, '', NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_capabilities($context, $id, $modulename) {
        global $CFG, $USER;
        require_once($CFG->dirroot.'/admin/roles/lib.php');

        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);

        $allowed_context = array('system', 'user', 'coursecat', 'course', 'module', 'block');
        $context = strtolower($context);
        
        if(!in_array($context, $allowed_context)) {
            throw new moodle_exception('generalexceptionmessage','moodbile_system', '','Invalid context');
        }

        if ($context == 'system') {
            $context = get_context_instance(CONTEXT_SYSTEM);
        } elseif ($context == 'user') {
            if (has_capability('moodle/role:assign', $system_context, $USER->id)) {
                $context = get_context_instance(CONTEXT_USER, $id);
            } else {
                $context = get_context_instance(CONTEXT_USER, $USER->id);
            }
        } elseif ($context == 'coursecat') {
            $context = get_context_instance(CONTEXT_COURSECAT, $id);
        } elseif ($context == 'course') {
            $context = get_context_instance(CONTEXT_COURSE, $id);
        } elseif ($context == 'module') {
        	$cm = get_coursemodule_from_instance($modulename, $id);
        	if ($cm === false) {
        		throw new moodle_exception('generalexceptionmessage', 'moodbile_system','','modulename not found');
        	}
            $context = get_context_instance(CONTEXT_MODULE, $cm->id);
        } elseif ($context == 'block') {
            $context = get_context_instance(CONTEXT_BLOCK, $id);
        }
        
        $capabilities = fetch_context_capabilities($context);

        $ret = array();
        foreach ($capabilities as $capability) {
            if (has_capability($capability->name, $context, $USER->id)) {
                $ret[] = array('name' => $capability->name, 'type' => $capability->captype , 'component' => $capability->component);
            }
        }
        return $ret;

    }

    public static function get_capabilities_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                      'name' =>new external_value(PARAM_TEXT,'This is the internal name used for this this capability', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                      'type' => new external_value(PARAM_TEXT,"Should be either 'read' or 'write'. 'read' is for capabilities that just let you view things. 'write' for capabilities that let you change things.", VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                       //'contextlevel' => new external_value(PARAM_INT, 'Context Level', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                      'component' =>new external_value(PARAM_TEXT, 'The component this capability applies to.', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                )
            )
        );
    }
/*public 'id' => string '92' (length=2)
      public 'name' => string 'moodle/user:editprofile' (length=23)
      public 'captype' => string 'write' (length=5)
      public 'contextlevel' => string '30' (length=2)
      public 'component' => string 'moodle' (length=6)
      public 'riskbitmask' => string '24' (length=2)*/
}