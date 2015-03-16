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
 * Group Database Functions
 *
 * @package MoodbileServer
 * @subpackage Group
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

abstract class group_db_base {

    public static function moodbile_get_group_by_groupid($groupid) {
        global $MBL;

        return $MBL->DB->get_record('groups', array('id' => $groupid));
    }

    public static function moodbile_get_group_members_by_groupid($groupid, $startpage, $n) {
        global $MBL;

        $sql = "SELECT u.*
             FROM {user} u, {groups_members} gm
             WHERE u.id = gm.userid AND gm.groupid = :groupid
             ORDER BY lastname ASC";

        $sqlparams = array();
        $sqlparams['groupid'] = $groupid;

        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_group_members_by_groupingid($groupingid, $startpage, $n) {
        global $MBL;

        $sql = "SELECT u.*
              FROM {user} u
               INNER JOIN {groups_members} gm ON u.id = gm.userid
               INNER JOIN {groupings_groups} gg ON gm.groupid = gg.groupid
              WHERE  gg.groupingid = :groupingid
              ORDER BY lastname ASC";

        $sqlparams = array();
        $sqlparams['groupingid'] = $groupingid;

        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_groups_by_courseid($courseid, $startpage, $n, $userid=0) {
        global $MBL;

        $sqlparams = array();
        if (empty($userid)) {
           $userfrom  = "";
           $userwhere = "";
        }
        else {
            $userfrom  = ", {groups_members} gm";
            $userwhere = "AND g.id = gm.groupid AND gm.userid = :userid";
            $sqlparams['userid'] = $userid;
        }
        $sql ="SELECT g.*
                FROM {groups} g $userfrom
                WHERE g.courseid = :courseid $userwhere
                ORDER BY name ASC";

        $sqlparams['courseid'] = $courseid;
        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_groups_by_groupingid($groupingid, $startpage, $n, $userid=0) {
        global $MBL;

        $sqlparams = array();
        if (empty($userid)) {
           $userfrom  = "";
           $userwhere = "";
        }
        else {
            $userfrom  = ", {groups_members} gm";
            $userwhere = "AND g.id = gm.groupid AND gm.userid = :userid";
            $sqlparams['userid'] = $userid;
        }

        $sql = "SELECT *
              FROM {groups} g, {groupings_groups} gg $userfrom
              WHERE g.id = gg.groupid AND gg.groupingid = :groupingid $userwhere
              ORDER BY name ASC";

        $sqlparams['groupingid'] = $groupingid;
        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_groupings_by_courseid($courseid, $startpage, $n) {
        global $MBL;

        $sql = "SELECT *
               FROM {groupings}
               WHERE courseid = :courseid
               ORDER BY name ASC";

        $sqlparams = array();
        $sqlparams['courseid'] = $courseid;
        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_groupings_by_courseid_and_userid($courseid, $startpage, $n, $userid) {
        global $MBL;

        //@WARNING hand-made
        $sql = "SELECT gp.*
                FROM {groups} g
                 JOIN {groups_members} gm ON gm.groupid = g.id
                 LEFT JOIN {groupings_groups} gg ON gg.groupid = g.id
                 JOIN {groupings} gp ON gg.groupingid = gp.id
                WHERE gm.userid = :userid AND g.courseid = :courseid
                GROUP BY gg.groupingid";

        $sqlparams = array();
        $sqlparams['courseid'] = $courseid;
        $sqlparams['userid'] = $userid;
        $begin = $startpage*$n;
        return $MBL->DB->get_records_sql($sql, $sqlparams, $begin, $n);
    }

    public static function moodbile_get_course_by_groupid($groupid){
        global $MBL;

        //@WARNING hand-made
        $sql ="SELECT c.*
                FROM {groups} g
                 JOIN {course} c ON c.id = g.courseid
                WHERE g.id = :groupid";

        $sqlparams = array();
        $sqlparams['groupid'] = $groupid;

        return $MBL->DB->get_record_sql($sql, $sqlparams);
    }

    public static function moodbile_get_course_by_groupingid($groupingid) {
        global $MBL;

        //@WARNING hand-made
        $sql ="SELECT c.*
                FROM {groupings} g
                 JOIN {course} c ON c.id = g.courseid
                WHERE g.id = :groupingid";

        $sqlparams = array();
        $sqlparams['groupingid'] = $groupingid;

        return $MBL->DB->get_record_sql($sql, $sqlparams);
    }

}