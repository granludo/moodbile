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
 * Calendar External Library
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
require_once(dirname(__FILE__).'/../config.php');
global $MBL;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot . '/calendar/event.class.php');
require_once($MBL->mblroot . '/calendar/db/calendarDB.php');
require_once($MBL->mblroot . '/user/db/userDB.php');
require_once($MBL->mblroot . '/course/db/courseDB.php');
require_once($MBL->mblroot . '/group/db/groupDB.php');
//require_once($MBL->mdllibdir.'/bennu/bennu.inc.php');//ical

class moodbileserver_calendar_external extends external_api {

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
//        if (empty(self::$contextrestriction)) {
//            self::$contextrestriction = get_context_instance(CONTEXT_SYSTEM);
//        }
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
    }

    public static function get_events_parameters() {
        $tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $defaulttimestart = date('U', strtotime('-1 week', time()));//default now-1week
        $defaulttimeend = date('U', strtotime('+1 month', time()));//default now+1week
        date_default_timezone_set($tz);
        return new external_function_parameters(
            array(
                'userid'    => new external_value(PARAM_INT, 'The user the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'courseid'  => new external_value(PARAM_INT, 'The course the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'groupid'   => new external_value(PARAM_INT, 'The group the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'timestart' => new external_value(PARAM_INT, 'Timestamp in UTC of the date when event starts', VALUE_DEFAULT, $defaulttimestart, NULL_NOT_ALLOWED),//default now-1week
                'timeend'   => new external_value(PARAM_INT, 'Timestamp in UTC of the date when event ends', VALUE_DEFAULT, $defaulttimeend, NULL_NOT_ALLOWED),//default now+1month
            )
        );
    }

    //by default only returns the events within one month of today
    //to change that set timestart and timeend
    public static function get_events($userid, $courseid, $groupid, $timestart, $timeend) {
        $system_context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($system_context);

        //$params = self::validate_parameters(self::get_events_parameters(), array('params' => $parameters));

        $userparam = empty($userid) ? false : $userid;
        if ($userparam and user_db::moodbile_get_user_by_id($userparam) === false) {
           throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','User not found');
        }
        $courseparam = empty($courseid) ? false : $courseid;
        if ($courseparam and course_db::moodbile_get_course_by_courseid($courseparam) === false) {
            throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','Course not found');
        }
        $groupparam = empty($groupid) ? false : $groupid;
        if ($groupparam and group_db::moodbile_get_group_by_groupid($groupparam) === false) {
            throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','Group not found');
        }

        if (!self::get_events_permission($userparam, $groupparam, $courseparam)) {
            throw new moodle_exception('nopermissions','moodbile_calendar', '',"Permission denied");
        }
        $events = calendar_db::moodbile_get_events($timestart, $timeend, $userparam, $groupparam, $courseparam);

        $returnevents = array();
        foreach ($events as $event) {
            $event = new Event($event);
            $returnevents[] = $event->get_data();
        }

        return $returnevents;
    }

    private static function get_events_permission($userparam, $groupparam, $courseparam) {
        global $USER;
        $system_context = get_context_instance(CONTEXT_SYSTEM);
	      self::validate_context($system_context);
        if (has_capability('moodle/calendar:manageentries', $system_context)) {
          return true;
        }
        if ($groupparam !== false ) {//group event
            $course = course_db :: moodbile_get_course_by_courseid($courseparam);
            if ($course == null || $course == false) {
               return false;
            }
            $course_context = get_context_instance(CONTEXT_COURSE, $course->id);
            self::validate_context($course_context);
            if (has_capability('moodle/calendar:manageentries', $course_context)) {
               return true;
            }
            if (has_capability('moodle/calendar:managegroupentries', $course_context)) {
               return true;
            }
            return groups_is_member($groupparam);
        }
        else if ($courseparam !== false) {//course event
            $course = course_db :: moodbile_get_course_by_courseid($courseparam);
            if ($course == null || $course == false) {
                return false;
            }
            $course_context = get_context_instance(CONTEXT_COURSE, $course->id);
            if(has_capability('moodle/calendar:manageentries', $course_context)) {
                return true;
            }
            return is_enrolled($course_context);
        }
        else if ($userparam !== false) {//user event
            //$user_context = get_context_instance(CONTEXT_USER, $USER->id);
            // TODO: CHECK THIS
            if (has_capability('moodle/calendar:manageownentries', $system_context)) {
                return true;
            }
            if ($userparam == $USER->id) {
                return true;
            }
        }
        return false;
    }

    public static function get_events_returns() {
        return new external_multiple_structure(
            Event::get_class_structure()
        );
    }

    public static function create_event_parameters() {
        return new external_function_parameters(
            array(
                'name'          => new external_value(PARAM_TEXT,       'The name of the event', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'description'   => new external_value(PARAM_RAW,        'The description of the event', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'courseid'      => new external_value(PARAM_INT,        'The course the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'groupid'       => new external_value(PARAM_INT,        'The group the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'userid'        => new external_value(PARAM_INT,        'The user the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'numrepetitions'=> new external_value(PARAM_INT,        'Number of repetitions including itself', VALUE_DEFAULT, 1, NULL_NOT_ALLOWED),
                'eventtype'     => new external_value(PARAM_ALPHANUMEXT,'Type of event. Can be:course, user or site', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'timestart'     => new external_value(PARAM_INT,        'Timestamp in UTC when event starts', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'timeduration'  => new external_value(PARAM_INT,        'The duration of the event in seconds', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    public static function create_event($name, $description, $courseid, $groupid, $userid, $numrepetitions,  $eventtype, $timestart, $timeduration) {
        global $USER;

        //$params = self::validate_parameters(self::create_event_parameters(), array('params' => $parameters));

        $timemodified = time();

        switch ($eventtype) {
            case 'user':
                $context = get_context_instance(CONTEXT_USER, $USER->id);
                $courseid = 0;
                $groupid = 0;
                $userid = $USER->id;
                break;
            case 'site':
                $context = get_context_instance(CONTEXT_SYSTEM);
                $courseid = SITEID;
                $groupid = 0;
                $userid = $USER->id;
                break;
            case 'course':
                $context = get_context_instance(CONTEXT_COURSE, $courseid);
                $groupid = 0;
                $userid = $USER->id;
                break;
            case 'group':
                $context = get_context_instance(CONTEXT_COURSE, $courseid);
                $userid = $USER->id;
                break;
            default:
                throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','Eventtype not found');
                break;
        }
        //Check permissions
        $event = array('name' => $name, 'description' => clean_text($description), 'courseid' => $courseid, 'groupid' => $groupid, 'userid' => $userid,
            'eventtype' => $eventtype, 'timestart' =>$timestart, 'timedurarion' => $timeduration, 'timemodified' => $timemodified, 'context'=> $context);
        if (!self::create_event_permissions((object) $event)) {
          throw new moodle_exception('nopermissions','moodbile_calendar', '',"Permission denied");
        }
        unset($event['context']);
        $event['format'] = 1;//HTML
        $event['modulename'] = '0';
        $event['instance'] = 0;

        $return = calendar_db :: moodbile_insert_event_record($event);
        add_to_log($event['courseid'], 'calendar', 'add', 'event.php?action=edit&amp;id='.$return, $event['name']);
        $event['id'] = $return;

        $returnrep = true;
        //handle repetitions
        if ($numrepetitions > 1) {
            $returnrep = self::create_event_repetitions((object) $event, $numrepetitions);
        }
        return array('eventid' => $return);
    }

    private static function create_event_repetitions($event, $numrepetitions) {
        //first thing, update the current event
        $event->repeatid = $event->id;

        calendar_db :: moodbile_set_field_event('repeatid', $event->repeatid, array('id'=> $event->id));

        $eventcopy = clone($event);
        unset($eventcopy->id);
        $ret = true;
        for($i = 1; $i < $numrepetitions; $i++) {
            $eventcopy->timestart = ($eventcopy->timestart+WEEKSECS) + dst_offset_on($eventcopy->timestart) - dst_offset_on($eventcopy->timestart+WEEKSECS);
            $ret = calendar_db :: moodbile_insert_event_record($eventcopy);
        }
        add_to_log($eventcopy->courseid, 'calendar', 'add', 'event.php?action=edit&amp;id='.$ret, $eventcopy->name);
        return $ret;
    }

    private static function create_event_permissions($event) {
        global $USER;

        $sitecontext = get_context_instance(CONTEXT_SYSTEM);
        // if user has manageentries at site level, always return true
        if (has_capability('moodle/calendar:manageentries', $sitecontext)) {
            return true;
        }

        switch ($event->eventtype) {
            case 'course':
                return has_capability('moodle/calendar:manageentries', $event->context);

            case 'group':
                // Allow users to add/edit group events if:
                // 1) They have manageentries (= entries for whole course)
                // 2) They have managegroupentries AND are in the group
                $group = group_db :: moodbile_get_group_by_groupid($event->groupid);
                return $group && (
                    has_capability('moodle/calendar:manageentries', $event->context) ||
                    (has_capability('moodle/calendar:managegroupentries', $event->context)
                        && groups_is_member($event->groupid)));

            case 'user':
                if ($event->userid == $USER->id) {
                    return (has_capability('moodle/calendar:manageownentries', $event->context));
                }
                //there is no 'break;' intentionally

            case 'site':
                return has_capability('moodle/calendar:manageentries', $event->context);

            default:
                return has_capability('moodle/calendar:manageentries', $event->context);
          }
  }

  public static function create_event_returns() {
        return new external_single_structure(
            array(
                'eventid' => new external_value(PARAM_INT, 'The id of the event just created', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            )
        );
  }

  public static function delete_event_parameters() {
      return new external_function_parameters(
            array(
                'id'            => new external_value(PARAM_INT,  'The id within the event table', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'deleterepeated'=> new external_value(PARAM_BOOL, 'True to delete repeated events', VALUE_DEFAULT, true, NULL_NOT_ALLOWED),
            )
      );
  }

  public static function delete_event($id, $deleterepeated) {
      $system_context = get_context_instance(CONTEXT_SYSTEM);

      self::validate_context($system_context);

      //$params = self::validate_parameters(self::delete_event_parameters(), array('params' => $parameters));

      $event = calendar_db:: moodbile_get_event_by_id($id);
      //permissions
      if ($event == null || $event == false) {
        throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','Event not found');
      }
      if (!self::delete_edit_event_permission($event)) {
        throw new moodle_exception('nopermissions','moodbile_calendar', '',"Permission denied");
      }

      //delete the event
      $return = calendar_db :: moodbile_delete_event_by_id($event->id);

      $ret = true;
      // If we need to delete repeated events then we will fetch them all and delete one by one
      if ($deleterepeated && !empty($event->repeatid) && $event->repeatid > 0) {
          // Get all records where the repeatid is the same as the event being removed
          $events = calendar_db :: moodbile_get_events_by_repeatid($event->repeatid);
          // For each of the returned events populate a calendar_event object and call delete
          // make sure the arg passed is false as we are already deleting all repeats
          foreach ($events as $event) {
              $deleparams = array();
              $deleparams['id'] = $event->id;
              $deleparams['deleterepeated'] = false;
              $ret = $ret && self::delete_event($deleparams);
          }
      }
      return array('success' => $return);
  }

  private static function delete_edit_event_permission($event) {
      global $USER;

      $sitecontext = get_context_instance(CONTEXT_SYSTEM);
      // if user has manageentries at site level, return true
      if (has_capability('moodle/calendar:manageentries', $sitecontext)) {
          return true;
      }

      // if groupid is set, it's definitely a group event
      if (!empty($event->groupid)) {
          // Allow users to add/edit group events if:
          // 1) They have manageentries (= entries for whole course)
          // 2) They have managegroupentries AND are in the group
          $group = group_db :: moodbile_get_group_by_groupid($event->groupid);
          return $group && (
             has_capability('moodle/calendar:manageentries', get_context_instance(CONTEXT_COURSE, $group->courseid)) ||
              (has_capability('moodle/calendar:managegroupentries', get_context_instance(CONTEXT_COURSE, $group->courseid))
                  && groups_is_member($event->groupid)));
      } else if (!empty($event->courseid)) {
      // if groupid is not set, but course is set,
      // it's definiely a course event
         return has_capability('moodle/calendar:manageentries', get_context_instance(CONTEXT_COURSE, $event->courseid));
      } else if (!empty($event->userid) && $event->userid == $USER->id) {
        // if course is not set, but userid id set, it's a user event
        return (has_capability('moodle/calendar:manageownentries', get_context_instance(CONTEXT_USER, $event->userid)));
      } else if (!empty($event->userid)) {
        return (has_capability('moodle/calendar:manageentries', get_context_instance(CONTEXT_USER, $event->userid)));
      }
      return false;
  }

  public static function delete_event_returns() {
      return new external_single_structure(
          array(
              'success' => new external_value(PARAM_BOOL, "Returns true if the event was deleted", VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
          )
      );
  }

  public static function export_events_to_ical_parameters() {
      return new external_function_parameters(
            array(
                'scope'  => new external_value(PARAM_ALPHA, "Scope of what to export, can be 'all' or 'courses'", VALUE_DEFAULT, "all", NULL_NOT_ALLOWED),
                'time'  => new external_value(PARAM_ALPHA,  "Time interval to export can be 'weeknow', 'weeknext', 'monthnow', 'monthnext' or 'recentupcoming'", VALUE_DEFAULT, "weeknow", NULL_NOT_ALLOWED),
            )
      );
  }

  public static function export_events_to_ical($scope, $time) {
      global $USER;
      global $CFG;

      require_once($CFG->dirroot.'/calendar/lib.php');

      $system_context = get_context_instance(CONTEXT_SYSTEM);

      self::validate_context($system_context);

      //$params = self::validate_parameters(self::export_events_to_ical_parameters(), array('params' => $parameters));

      $allowed_what = array('all', 'courses');
      $allowed_time = array('weeknow', 'weeknext', 'monthnow', 'monthnext', 'recentupcoming');

      $what = $scope;

      if(!in_array($what, $allowed_what) || !in_array($time, $allowed_time)) {
        throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','Bad parameters');
      }

      $authtoken = sha1($USER->username . $USER->password . $CFG->calendar_exportsalt);
      $baseurl = $CFG->wwwroot;
      $url = $baseurl . '/calendar/export_execute.php?preset_what=' . $what . '&preset_time='. $time . '&username=' . $USER->username . '&authtoken=' . $authtoken;

      return array('url' => $url);
  }

  public static function export_events_to_ical_returns() {
     return new external_single_structure(
         array(
             'url' => new external_value(PARAM_URL, 'The calendar URL', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
         )
     );
  }

  public static function update_event_parameters() {
    return new external_function_parameters(
        array(
            'event' => Event::get_class_structure(),
            'repeateditall' => new external_value(PARAM_BOOL, 'True to edit all repeated events', VALUE_DEFAULT, true, NULL_NOT_ALLOWED),
        )
    );
  }

  public static function update_event($event, $repeateditall) {
      $system_context = get_context_instance(CONTEXT_SYSTEM);

      self::validate_context($system_context);

      //$params = self::validate_parameters(self::update_event_parameters(), array('params' => $parameters));
      $event = (object) $event;
      $event_orig = calendar_db:: moodbile_get_event_by_id($event->id);
      if ($event_orig == null || $event_orig == false) {
          throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '','Event not found');
      }
      if (!self::delete_edit_event_permission($event_orig)) {
          throw new moodle_exception('nopermissions','moodbile_calendar', '',"Permission denied");
      }

      if ($event->repeatid != $event_orig->repeatid && !$repeateditall) {
          throw new moodle_exception('generalexceptionmessage','moodbile_calendar', '',"You're orphaning events!");
      }

      $updaterepeated = (!empty($event->repeatid) && !empty($repeateditall));

      if ($updaterepeated) {
          // Update all
          $event->description = clean_text($event->description);
          return array('success' => calendar_db::moodbile_update_event($event, $event_orig));
      } else {
          $event_orig->name = $event->name;
          $event_orig->description = clean_text($event->description);
          $event_orig->timestart = $event->timestart;
          $event_orig->timeduration = $event->timeduration;
          $event_orig->timemodified = time();
          return array('success' => calendar_db::moodbile_update_event_record($event_orig));
      }
  }

  public static function update_event_returns() {
     return new external_single_structure(
         array(
             'success' => new external_value(PARAM_BOOL, 'Returns true on success', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
         )
     );
  }
}