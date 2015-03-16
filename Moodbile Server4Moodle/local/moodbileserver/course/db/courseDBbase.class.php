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
 * Course Database Library Class
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

require_once($MBL->mdllibdir.'/enrollib.php');

abstract class course_db_base {

    /**
     * Returns n courses the user is registered to starting from page startpage
     *
     * @param int $userid
     * @param context $context
     * @param int $startpage
     * @param int $n
     *
     * @return array of course
     */
    public static function moodbile_get_courses_by_userid($userid, $viewhidden, $startpage, $n) {
        global $MBL;

        $where = '';
        if (!$viewhidden) {
            $where = 'WHERE c.visible = 1';
        }

        $sql = "SELECT c.*
              FROM {course} c
              JOIN (SELECT DISTINCT e.courseid
                      FROM {enrol} e
                      JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = :userid)
                     WHERE ue.status = :active AND e.status = :enabled
                   ) en ON (en.courseid = c.id) $where
              UNION SELECT c.*
                        FROM {course} c
                        JOIN {enrol} e ON (e.courseid = c.id)
                        WHERE e.enrol = :guest AND
                              e.status = 0";

        $sqlparams = array();
        $sqlparams['userid']    = $userid;
        $sqlparams['active']    = ENROL_USER_ACTIVE;
        $sqlparams['enabled']   = ENROL_INSTANCE_ENABLED;
        $sqlparams['guest']     = 'guest';

        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_course_by_courseid($courseid) {
        global $MBL;

        return $MBL->DB->get_record('course', array('id' => $courseid));
    }

    /**
     * Just gets a raw list of all modules in a course
     *
     * @global object
     * @param int $courseid The id of the course as found in the 'course' table
     * @param bool $viewhidden User can view hidden modules
     * @param int $startpage The starting page - for paging results
     * @param int $n Results per page
     *
     * @return array
     */
    public static function get_course_mods($courseid, $viewhidden, $startpage, $n) {
        global $MBL;

        if (empty($courseid)) {
            return false; // avoid warnings
        }

        $visible = '';
        if (!$viewhidden) {
            $visible = 'AND m.visible = 1';
        }

        $sqlparams = array();
        $sqlparams['courseid'] = $courseid;

        $sql = "SELECT cm.*, m.name as modname
                FROM {modules} m, {course_modules} cm
                WHERE cm.course = :courseid AND cm.module = m.id $visible";

        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql,$sqlparams, $begin, $n); // no disabled mods
    }

}