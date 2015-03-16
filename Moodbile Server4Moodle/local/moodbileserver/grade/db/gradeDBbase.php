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
 * Grade DataBase Functions Class
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

abstract class grade_db_base {

    public static function moodbile_get_grade_items_by_userid($userid, $viewhidden, $startpage, $n) {
        global $MBL;

        $where = '';
        if (!$viewhidden) {
            $where = 'AND gi.hidden = 0';
        }

        $sql = "SELECT gi.*
                FROM {grade_items} gi, {user_enrolments} ue, {enrol} e
                WHERE ue.userid = :userid AND
                      ue.enrolid = e.id AND
                      ue.status = :active AND
                      e.status = :enabled AND
                      e.courseid = gi.courseid $where";

        $sqlparams = array();
        $sqlparams['userid']    = $userid;
        $sqlparams['active']    = ENROL_USER_ACTIVE;
        $sqlparams['enabled']   = ENROL_INSTANCE_ENABLED;

        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function get_courseid_by_gradeitemid($itemid) {
        global $MBL;

        $gradeitem = $MBL->DB->get_record('grade_items', array('id' => $itemid));

        return $gradeitem->courseid;
    }

    public static function moodbile_get_grades_by_itemid($itemid, $viewhidden, $startpage, $n) {
        global $MBL;

        $conditions = array();
        $conditions['itemid'] = $itemid;
        if (!$viewhidden) {
            $conditions['visible'] = 1;
        }

        $begin = $startpage*$n;
        $grades = $MBL->DB->get_records('grade_grades', $conditions, '', '*', $begin, $n);

        return $grades;
    }

    public static function moodbile_get_grade_items_by_courseid($courseid, $viewhiddencourses,
                                                     $viewhiddenactivities, $startpage, $n) {
        global $MBL;
        require_once($MBL->mblroot . '/course/db/courseDB.php');

        $course = course_db::moodbile_get_course_by_courseid($courseid);

        if (!($course->visible) && !$viewhiddencourses) {
            return null;
        }

        $where = '';
        if (!$viewhiddenactivities) {
            $where = 'AND hidden = 0';
        }

        $sql = "SELECT *
                FROM {grade_items}
                WHERE courseid = :courseid AND
                      categoryid IS NOT NULL AND
                      itemname IS NOT NULL $where";

        $sqlparams = array();
        $sqlparams['courseid'] = $courseid;

        $begin = $startpage*$n;
        $gradeitems = $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);

        return $gradeitems;
    }

    public static function moodbile_get_user_grade_by_itemid($userid, $itemid, $viewhidden) {
        global $MBL;

        $conditions = array();
        $conditions['userid'] = $userid;
        $conditions['itemid'] = $itemid;
        if (!$viewhidden) {
            $conditions['hidden'] = 0;
        }

        $grade = $MBL->DB->get_record('grade_grades', $conditions);
        return $grade;
    }

    public static function moodbile_get_user_grades_by_courseid($userid, $courseid, $viewhiddencourses,
                                                                $viewhiddenactivities, $startpage, $n) {
        global $MBL;

        $gradeitems = moodbile_get_grade_items_by_courseid($courseid,$viewhiddencourses, $viewhiddenactivities, 0, 0);

        $conditions = array();
        $conditions['userid'] = $userid;
        if (!$viewhiddenactivities) {
            $conditions['hidden'] = 0;
        }

        $begin = $startpage*$n;
        $remaining = $n;
        $return = array();
        foreach ($gradeitems as $item) {
            $conditions['itemid'] = $item->id;

            $grade = $MBL->DB->get_records('grade_grades', $conditions, '', '*', $begin, $remaining);
            $remaining = $remaining - count($grade);
            $return[] = $grade;
            if ($remaining <= 0) {
                break;
            }
        }

        return $return;

    }
}
