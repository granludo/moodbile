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
 * Course External Library
 *
 * @package MoodbileServer
 * @subpackage Course
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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
require_once(dirname(__FILE__).'/../config.php');
global $MBL;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot . '/course/course.class.php');
require_once($MBL->mblroot . '/course/db/courseDB.php');


class moodbileserver_course_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_courses_by_userid_parameters() {
        return new external_function_parameters(
            array(
                'userid'    => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns an array of the courses a user is enrolled
     *
     * @params array of userids
     * @return array An array of arrays
     */
    public static function get_courses_by_userid($userid, $startpage, $n) {

        $context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($context);

        $viewhidden = false;
        if (has_capability('moodle/course:viewhiddencourses', $context)) {
            $viewhidden = true;
        }

        $courses = course_db::moodbile_get_courses_by_userid($userid, $viewhidden, $startpage, $n);

        $returncourses = array();
        foreach ($courses as $course) {
            $course = new Course($course);
            $returncourses[] = $course->get_data();
        }

        return $returncourses;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_courses_by_userid_returns() {
        return
            new external_multiple_structure(
                Course::get_class_structure()
            );
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_course_modules_parameters() {
        return new external_function_parameters(
            array(
                'courseid'    => new external_value(PARAM_INT, 'the id number of the course', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     *
     * Function to get course contents
     *
     * @param text $courseid - Course ID
     *
     * @return list of course contents
     */
    public static function get_course_modules($courseid, $startpage, $n) {
        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        self::validate_context($context);

        $viewhidden = false;
        if (has_capability('moodle/course:viewhiddencourses', $context)) {
            $viewhidden = true;
        }

        $modules = course_db::get_course_mods($courseid, $viewhidden, $startpage, $n);
        $modarray = array();
        foreach ($modules as $mod) {
            $modinfo = array();
            if (($mod->visible) || (has_capability('moodle/course:viewhiddenactivities', $context))) {
                // get the module name and then store it in a new array
                if ($module = get_coursemodule_from_instance($mod->modname, $mod->instance, $courseid)) {
                    $modinfo["id"] = $mod->id;
                    $modinfo["modulename"] = $mod->modname;
                    $modinfo["instanceid"] = $mod->instance;
                    $modinfo["instancename"] = $module->name;
                    $modinfo["timeadded"] = $mod->added;
                    $modarray[] = $modinfo;
                }
            }
        }

        return $modarray;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_course_modules_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                        array(
                            'id'            => new external_value(PARAM_INT,  'Module id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                            'modulename'    => new external_value(PARAM_TEXT, 'Name of the module', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                            'instanceid'    => new external_value(PARAM_INT,  'Instance id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                            'instancename'  => new external_value(PARAM_TEXT, 'Name of the module instance', VALUE_REQUIRED),
                            'timeadded'     => new external_value(PARAM_INT,  'Time the module was added in the course', VALUE_REQUIRED)
                        )
                )
        );
    }

}
