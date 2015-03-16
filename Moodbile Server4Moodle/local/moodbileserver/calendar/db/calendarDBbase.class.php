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
 * Calendar DataBase Library
 *
 * @package MoodbileServer
 * @subpackage Calendar
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

abstract class calendar_db_base {

    public static function moodbile_get_events($tstart, $tend, $users, $groups, $courses, $withduration=true, $ignorehidden=true) {
        global $MBL;

        $whereclause = '';
        // Quick test
        if(is_bool($users) && is_bool($groups) && is_bool($courses)) {
            return array();
        }

        if(is_array($users) && !empty($users)) {
            // Events from a number of users
            if(!empty($whereclause)) $whereclause .= ' OR';
            $whereclause .= ' (userid IN ('.implode(',', $users).') AND courseid = 0 AND groupid = 0)';
        } else if(is_numeric($users)) {
            // Events from one user
            if(!empty($whereclause)) $whereclause .= ' OR';
            $whereclause .= ' (userid = '.$users.' AND courseid = 0 AND groupid = 0)';
        } else if($users === true) {
            // Events from ALL users
            if(!empty($whereclause)) $whereclause .= ' OR';
            $whereclause .= ' (userid != 0 AND courseid = 0 AND groupid = 0)';
        } else if($users === false) {
            // No user at all, do nothing
        }

        if(is_array($groups) && !empty($groups)) {
            // Events from a number of groups
            if(!empty($whereclause)) $whereclause .= ' OR';
            $whereclause .= ' groupid IN ('.implode(',', $groups).')';
        } else if(is_numeric($groups)) {
            // Events from one group
            if(!empty($whereclause)) $whereclause .= ' OR ';
            $whereclause .= ' groupid = '.$groups;
        } else if($groups === true) {
            // Events from ALL groups
            if(!empty($whereclause)) $whereclause .= ' OR ';
            $whereclause .= ' groupid != 0';
        }
        // boolean false (no groups at all): we don't need to do anything

        if(is_array($courses) && !empty($courses)) {
            if(!empty($whereclause)) {
                $whereclause .= ' OR';
            }
            $whereclause .= ' (groupid = 0 AND courseid IN ('.implode(',', $courses).'))';
        } else if(is_numeric($courses)) {
            // One course
            if(!empty($whereclause)) $whereclause .= ' OR';
            $whereclause .= ' (groupid = 0 AND courseid = '.$courses.')';
        } else if ($courses === true) {
            // Events from ALL courses
            if(!empty($whereclause)) $whereclause .= ' OR';
            $whereclause .= ' (groupid = 0 AND courseid != 0)';
        }

        // Security check: if, by now, we have NOTHING in $whereclause, then it means
        // that NO event-selecting clauses were defined. Thus, we won't be returning ANY
        // events no matter what. Allowing the code to proceed might return a completely
        // valid query with only time constraints, thus selecting ALL events in that time frame!
        if(empty($whereclause)) {
            return array();
        }

        if($withduration) {
            $timeclause = '(timestart >= '.$tstart.' OR timestart + timeduration > '.$tstart.') AND timestart <= '.$tend;
        }
        else {
            $timeclause = 'timestart >= '.$tstart.' AND timestart <= '.$tend;
        }
        if(!empty($whereclause)) {
            // We have additional constraints
            $whereclause = $timeclause.' AND ('.$whereclause.')';
        }
        else {
            // Just basic time filtering
            $whereclause = $timeclause;
        }

        if ($ignorehidden) {
            $whereclause .= ' AND visible = 1';
        }

        $sql = "SELECT *
             FROM {event}
             WHERE $whereclause
             ORDER BY timestart";
        $events = $MBL->DB->get_records_sql($sql);

        if ($events === false) {
            $events = array();
        }
        return $events;
    }

    public static function moodbile_get_event_by_id($id) {
        global $MBL;

        return $MBL->DB->get_record('event', array('id' => $id));
    }

    public static function moodbile_get_events_by_repeatid($repeatid) {
        global $MBL;

        return $MBL->DB->get_records('event', array('repeatid' => $repeatid));
    }

    public static function moodbile_delete_event_by_id($id) {
        global $MBL;

        return $MBL->DB->delete_records('event', array('id'=> $id));
    }

    public static function moodbile_set_field_event($newfield, $newvalue, array $conditions=null) {
        global $MBL;

        return $MBL->DB->set_field('event', $newfield, $newvalue, $conditions);
    }

    public static function moodbile_insert_event_record($params) {
        global $MBL;

        return $MBL->DB->insert_record('event', $params);
    }

    public static function moodbile_update_event_record($params) {
        global $MBL;

        return $MBL->DB->update_record('event', $params);
    }

    public static function moodbile_update_event($params, $event) {
        global $MBL;
        if ($params->timestart != $event->timestart) {
              $timestartoffset = $params->timestart - $event->timestart;
              $sql = "UPDATE {event}
                         SET name = ?,
                             description = ?,
                             timestart = timestart + ?,
                             timeduration = ?,
                             timemodified = ?
                       WHERE repeatid = ?";
              $sqlparams = array($params->name, $params->description, $timestartoffset, $params->timeduration, time(), $event->repeatid);
        } else {
              $sql = "UPDATE {event} SET name = ?, description = ?, timeduration = ?, timemodified = ? WHERE repeatid = ?";
              $sqlparams = array($params->name, $params->description, $params->timeduration, time(), $event->repeatid);
        }
        return $MBL->DB->execute($sql, $sqlparams);
    }

}