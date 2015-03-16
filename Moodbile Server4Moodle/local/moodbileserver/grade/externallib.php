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
 * Grade External Functions
 *
 * @package MoodbileServer
 * @subpackage Grade
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
require_once($MBL->mblroot . '/grade/grade.class.php');
require_once($MBL->mblroot . '/grade/gradeitem.class.php');
require_once($MBL->mblroot . '/grade/db/gradeDB.php');


class moodbileserver_grade_external extends external_api {

    /**
     * Makes sure user may execute functions in this context.
     * @param object $context
     * @return void
     */
    protected static function validate_context($context) {
        global $CFG;

        if (empty($context)) {
            throw new invalid_parameter_exception('Context does not exist');
        }

        $rcontext = get_context_instance(CONTEXT_SYSTEM);

        if ($rcontext->contextlevel == $context->contextlevel) {
            if ($rcontext->id != $context->id) {
                throw new restricted_context_exception();
            }
        } else if ($rcontext->contextlevel > $context->contextlevel) {
            throw new restricted_context_exception();
        } else {
            $parents = get_parent_contexts($context);
            if (!in_array($rcontext->id, $parents)) {
                throw new restricted_context_exception();
            }
        }

        if ($context->contextlevel >= CONTEXT_COURSE) {
            list($context, $course, $cm) = get_context_info_array($context->id);
        }
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_grade_items_by_userid_parameters() {
        return new external_function_parameters (
            array(
                'userid'    => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns a list of n grade items starting from page startpage
     *
     * @param int userid
     * @param int startpage
     * @param int n
     *
     * @return array of grade items
     */
    public static function get_grade_items_by_userid($userid, $startpage, $n) {
//        $params = self::validate_parameters(self::get_grade_items_by_userid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

        $context = get_context_instance(CONTEXT_USER, $userid);
        self::validate_context($context);

        $viewhidden = false;
        if (has_capability('moodle/course:viewhiddenactivities', $context)) {
            $viewhidden = true;
        }

        $gradeitems = grade_db::moodbile_get_grade_items_by_userid($userid, $viewhidden, $startpage, $n);

        $returngradeitems = array();
        foreach ($gradeitems as $gradeitem) {
            $newgradeitem = new Gradeitem($gradeitem);
            $returngradeitems[] = $newgradeitem->get_data();
        }
        return $returngradeitems;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_grade_items_by_userid_returns() {
        return
            new external_multiple_structure(
                Gradeitem::get_class_structure()
            );
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_grades_by_itemid_parameters() {
        return new external_function_parameters (
            array(
                'itemid'    => new external_value(PARAM_INT, 'grade item ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns all grades coresponding to a specific grade item
     *
     * @param int itemid
     *
     * @return array of grade
     */
    public static function get_grades_by_itemid($itemid, $startpage, $n) {
//        $params = self::validate_parameters(self::get_grades_by_itemid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

        $courseid = grade_db::get_courseid_by_gradeitemid($itemid);

        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        self::validate_context($context);

        $viewhidden = false;
        if (has_capability('moodle/course:viewhiddenactivities', $context)) {
            $viewhidden = true;
        }

        $grades = grade_db::moodbile_get_grades_by_itemid($itemid, $viewhidden, $startpage, $n);

        $returngrades = array();
        foreach ($grades as $grade) {
            $newgrade = new Grade($grade);
            $returngrades[] = $newgrade->get_data();
        }

        return $returngrades;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_grades_by_itemid_returns() {
        return
            new external_multiple_structure(
                Grade::get_class_structure()
            );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_user_grade_by_itemid_parameters() {
        return new external_function_parameters (
            array(
                'userid'    => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'itemid'    => new external_value(PARAM_INT, 'grade item ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    /**
     * Returns a user's grade corresponding to a specific grade item
     *
     * @param int itemid
     *
     * @return grade
     */
    public static function get_user_grade_by_itemid($userid, $itemid) {
//        $params = self::validate_parameters(self::get_user_grade_by_itemid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

        $courseid = grade_db::get_courseid_by_gradeitemid($itemid);

        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        self::validate_context($context);

        $viewhidden = false;
        if (has_capability('moodle/course:viewhiddenactivities', $context)) {
            $viewhidden = true;
        }

        if (!$grade = grade_db::moodbile_get_user_grade_by_itemid($userid, $itemid, $viewhidden)) {
                        throw new moodle_exception('generalexceptionmessage','moodbile_grade', '','No grade is set for the particular user');
        }

        $return = new Grade($grade);
        $return = $return->get_data();

        return $return;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_grade_by_itemid_returns() {
        return Grade::get_class_structure();
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_grade_items_by_courseid_parameters() {
        return new external_function_parameters (
            array(
                'courseid'  => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns a list of n grade items starting from page startpage
     *
     * @param int userid
     * @param int startpage
     * @param int n
     *
     * @return array of grade items
     */
    public static function get_grade_items_by_courseid($courseid, $startpage, $n) {
//        $params = self::validate_parameters(self::get_grade_items_by_courseid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        self::validate_context($context);

        $viewhiddencourses = false;
        if (has_capability('moodle/course:viewhiddencourses', $context)) {
            $viewhiddencourses = true;
        }

        $viewhiddenactivities = false;
        if (has_capability('moodle/course:viewhiddenactivities', $context)) {
            $viewhiddenactivities = true;
        }

        $gradeitems = grade_db::moodbile_get_grade_items_by_courseid($courseid, $viewhiddencourses,
                                                     $viewhiddenactivities, $startpage, $n);

        $returngradeitems = array();
        foreach ($gradeitems as $gradeitem) {
            $newgradeitem = new Gradeitem($gradeitem);
            $returngradeitems[] = $newgradeitem->get_data();
        }

        return $returngradeitems;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_grade_items_by_courseid_returns() {
        return
            new external_multiple_structure(
                Gradeitem::get_class_structure()
            );
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_user_grades_by_courseid_parameters() {
        return new external_function_parameters (
            array(
                'userid'    => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'courseid'  => new external_value(PARAM_INT, 'grade item ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    /**
     * Returns a user's grade corresponding to a specific grade item
     *
     * @param int itemid
     *
     * @return grade
     */
    public static function get_user_grades_by_courseid($userid, $courseid) {
//        $params = self::validate_parameters(self::get_user_grades_by_courseid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

       $context = get_context_instance(CONTEXT_COURSE, $courseid);

        self::validate_context($context);

        $viewhiddenactivities = false;
        if (has_capability('moodle/course:viewhiddenactivities', $context)) {
            $viewhiddenactivities = true;
        }

        $viewhiddencourses = false;
        if (has_capability('moodle/course:viewhiddencourses', $context)) {
            $viewhiddencourses = true;
        }

        $grades = grade_db::moodbile_get_user_grades_by_courseid($userid, $courseid, $viewhiddencourses, $viewhiddenactivities);

        $returngrades = array();
        foreach ($grades as $grade) {
            $newgrade = new Grade($grade);
            $returngrades[] = $grade->get_data();
        }

        return $returngrades;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_grades_by_courseid_returns() {
        return
            new external_multiple_structure(
                Grade::get_class_structure()
        );
    }

}
