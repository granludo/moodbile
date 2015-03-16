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
 * Group External Functions Library
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
require_once(dirname(__FILE__).'/../config.php');
global $MBL;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot . '/group/group.class.php');
require_once($MBL->mblroot . '/group/grouping.class.php');
require_once($MBL->mblroot . '/group/db/groupDB.php');
require_once($MBL->mblroot . '/course/db/courseDB.php');
require_once($MBL->mblroot . '/user/user.class.php');

define('MOODBILESERVER_GROUP_ACCESS_SOME_GROUPS', 2);

class moodbileserver_group_external extends external_api {

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

    public static function get_group_by_groupid_parameters() {
        return new external_function_parameters(
            array(
                'groupid' => new external_value(PARAM_INT, 'group id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    public static function get_group_by_groupid($groupid) {
        $system_context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($system_context);

        //$params = self::validate_parameters(self::get_group_by_groupid_parameters(), array('groupid'=>$parameters));

        if (!(self::get_group_by_groupid_permissions($groupid)))
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");

        $group = group_db::moodbile_get_group_by_groupid($groupid);

        $group = new Group($group);
        return $group->get_data();
    }

    private static function get_group_by_groupid_permissions($groupid) {
        $course = self::get_course_by_groupid($groupid);
        if ($course == null || $course == false) {
            return false;
        }
        $course_context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
        self::validate_context($course_context);
        if (has_capability('moodle/site:accessallgroups', $course_context)) {
            return true;
        }
        if (groups_is_member($groupid)) {
            return true;
        }
        //if it is not a group member but is enrolled in the course and the coursemode
        //is visible groups, the user can also see the group
        if (is_enrolled($course_context)) {
            $groupmode = groups_get_course_groupmode($course);
            if ($groupmode == VISIBLEGROUPS ) {
                return true;
            }
        }
        return false;
    }

    private function get_course_by_groupid($groupid) {
        return group_db::moodbile_get_course_by_groupid($groupid);
    }

    public static function get_group_by_groupid_returns() {
        return Group::get_class_structure();
    }

    public static function get_group_members_by_groupid_parameters() {
        return new external_function_parameters(
            array(
                'groupid'   => new external_value(PARAM_INT, 'group id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_group_members_by_groupid($groupid, $startpage, $n){
        global $MBL;
        require_once($MBL->mblroot."/lib.php");

        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_group_members_by_groupid_parameters(), array('params' => $parameters));

        if (!self::get_group_members_by_groupid_permissions($groupid)) {
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");
        }
        $users = group_db::moodbile_get_group_members_by_groupid($groupid, $startpage, $n);

        $returnusers = array();
        foreach ($users as $user) {
            $user->avatar = get_link(new user_picture($user));
            $user = new User($user);
            $returnusers[] = $user->get_data();
        }

        return $returnusers;
    }

    private static function get_group_members_by_groupid_permissions($groupid){
        $course = self::get_course_by_groupid($groupid);
        if ($course == null || $course == false) {
            return false;
        }
        $course_context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
        self::validate_context($course_context);
        if (has_capability('moodle/course:viewparticipants', $course_context)) {
            return true;//@warning too much info?
        }
        if (has_capability('moodle/course:managegroups', $course_context)) {
            return true;
        }
        //if it doesn't have this capabilities, it's better not to return anything
        //because the user object contains too much user details for a another
        //student for example to see, so I'm not even sure if returning true
        //for the capability view participants is too much cause if the client
        //receives all the user info even if not displayed it could be intercepted.
        //but, if it has the 'moodle/user:viewalldetails' its ok
        global $USER;
        $user_context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($user_context);
        if (has_capability('moodle/user:viewalldetails', $user_context))
            return true;
        return false;
    }

    public static function get_group_members_by_groupid_returns() {
        return new external_multiple_structure(
            User::get_class_structure()
        );
    }

    public static function get_group_members_by_groupingid_parameters() {
        return new external_function_parameters(
            array(
                'groupingid'=> new external_value(PARAM_INT, 'grouping id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_group_members_by_groupingid($groupingid, $startpage, $n){
        global $MBL;
        require_once($MBL->mblroot."/lib.php");
        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_group_members_by_groupingid_parameters(),array('params' => $parameters));

        if (!self::get_group_members_by_groupingid_permissions($groupingid))
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");

        $users = group_db::moodbile_get_group_members_by_groupingid($groupingid, $startpage, $n);

        $returnusers = array();
        foreach ($users as $user) {
            $user->avatar = get_link(new user_picture($user));
            $user = new User($user);
            $returnusers[] = $user->get_data();
        }

        return $returnusers;
    }

    private function get_group_members_by_groupingid_permissions($groupingid) {
        $system_context = get_context_instance(CONTEXT_SYSTEM);
        //should I grab first the course and check this capa with course context? the more specific context better permission control right?
        if (has_capability('moodle/site:viewparticipants', $system_context)) {
            return true;
        }
        $grouping = groups_get_grouping($groupingid, 'courseid', MUST_EXIST);
        $course = course_db::moodbile_get_course_by_courseid($grouping->courseid);
        if ($course == null || $course == false) {
            return false;
        }
        $course_context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
        self::validate_context($course_context);
        if (has_capability('moodle/course:viewparticipants', $course_context)) {
            return true;
        }
        //if it doesn't have this capabilities, it's better not to return anything
        //because the user object contains too much user details for a another
        //student for example to see, so I'm not even sure if returning true
        //for the capability course view participants is too much cause if the client
        //receives all the user info even if not displayed it could be intercepted.
        global $USER;
        $user_context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($user_context);
        if (has_capability('moodle/user:viewalldetails', $user_context))
            return true;
        return false;
    }

    public static function get_group_members_by_groupingid_returns() {
        return new external_multiple_structure(
            User::get_class_structure()
        );
    }

    public static function get_groups_by_courseid_parameters() {
        return new external_function_parameters(
            array(
                'courseid'  => new external_value(PARAM_INT, 'course id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_groups_by_courseid($courseid, $startpage, $n) {
        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_groups_by_courseid_parameters(), array('courseid'=>$parameters));

        $permission = self::get_groups_by_courseid_permissions($courseid);

        if ($permission === true) {
            $groups = group_db::moodbile_get_groups_by_courseid($courseid, $startpage, $n);
        }
        elseif ($permission === MOODBILESERVER_GROUP_ACCESS_SOME_GROUPS) {
            global $USER;
            $groups = group_db::moodbile_get_groups_by_courseid($courseid, $startpage, $n, $USER->id);
        }
        else {
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");
        }

        $returngroups = array();
        foreach ($groups as $group) {
            $group = new Group($group);
            $returngroups[] = $group->get_data();
        }

        return $returngroups;
    }

    private static function get_groups_by_courseid_permissions($courseid) {
        $course = course_db::moodbile_get_course_by_courseid($courseid);
        return self::get_groups_permission($course);
    }

    public static function get_groups_by_courseid_returns() {
        return new external_multiple_structure(
            Group::get_class_structure()
        );
    }

    public static function get_groups_by_groupingid_parameters() {
        return new external_function_parameters(
            array(
                'groupingid'=> new external_value(PARAM_INT, 'grouping id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_groups_by_groupingid($groupingid, $startpage, $n) {
        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_groups_by_groupingid_parameters(), array('groupingid'=>$parameters));

        $permission = self::get_groups_by_groupingid_permissions($groupingid);
        if ($permission === true) {
            $groups = group_db::moodbile_get_groups_by_groupingid($groupingid, $startpage, $n);
        }
        elseif ($permission === MOODBILESERVER_GROUP_ACCESS_SOME_GROUPS) {
            global $USER;
            $groups = group_db::moodbile_get_groups_by_groupingid($groupingid, $startpage, $n, $USER->id);
        }
        else {
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");
        }

        $returngroups = array();
        foreach ($groups as $group) {
            $group = new Group($group);
            $returngroups[] = $group->get_data();
        }

        return $returngroups;
    }

    private static function get_groups_by_groupingid_permissions($groupingid) {
        $course = group_db::moodbile_get_course_by_groupingid($groupingid);
        return self::get_groups_permission($course);
    }

    private static function get_groups_permission($course) {
        if ($course == null || $course == false) {
            return false;
        }
        $course_context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
        self::validate_context($course_context);
        //if the user can access all groups, then it's ok
        if (has_capability('moodle/site:accessallgroups', $course_context)) {
            return true;
        }

        /* if the user is enrolled in the course and, depending
         * on the group mode, we return all or just some groups because if the
         * course mode is separate groups, the user cannot see all groups
         * justs the groups where s/he is.
         */
        if (is_enrolled($course_context)) {
            $groupmode = groups_get_course_groupmode($course);//$course->groupmode
            if ($groupmode == VISIBLEGROUPS ) {
                return true;
            }
            else if ($groupmode == SEPARATEGROUPS) {
                return MOODBILESERVER_GROUP_ACCESS_SOME_GROUPS; //return some groups
            }
            else { //mode NOGROUPS
                return false;
            }
        }
        return false;
    }

    public static function get_groups_by_groupingid_returns() {
        return new external_multiple_structure(
            Group::get_class_structure()
        );
    }

    public static function get_groups_by_courseid_and_userid_parameters() {
        return new external_function_parameters(
            array(
                'courseid'  => new external_value(PARAM_INT, 'course id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'userid'    => new external_value(PARAM_INT, 'user id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_groups_by_courseid_and_userid($courseid, $userid, $startpage, $n) {
        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_groups_by_courseid_and_userid_parameters(), array('params' => $parameters));

        $permission = self::get_groups_by_courseid_and_userid_permissions($courseid,$userid);
        if ($permission === false) {
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");
        }
        else if ($permission === true) {
            //so we use the same function as get_groups_by_courseid passing the user param.
            $groups = group_db::moodbile_get_groups_by_courseid($courseid, $startpage, $n, $userid);
        }

        $returngroups = array();
        foreach ($groups as $group) {
            $group = new Group($group);
            $returngroups[] = $group->get_data();
        }

        return $returngroups;
    }

    private static function get_groups_by_courseid_and_userid_permissions($courseid, $userid){
        $course = course_db::moodbile_get_course_by_courseid($courseid);
        if ($course == null || $course == false) {
            return false;
        }
        $course_context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
        self::validate_context($course_context);
        if (has_capability('moodle/site:accessallgroups', $course_context)) {
            return true;
        }

        //If, for instance, a student user wants to know his groups, just use
        //the get_groups_by_courseid function or maybe we can add a check here
        //like global $USER; if ($userid == $USER->id) { return self::get_groups_by_courseid_permission($courseid);}
        return false;
    }

    public static function get_groups_by_courseid_and_userid_returns() {
        return new external_multiple_structure(
            Group::get_class_structure()
        );
    }

    public static function get_groupings_by_courseid_parameters() {
        return new external_function_parameters(
            array(
                'courseid'  => new external_value(PARAM_INT, 'course id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_groupings_by_courseid($courseid, $startpage, $n) {
        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_groupings_by_courseid_parameters(), array('courseid'=>$parameters));

        if (!self::get_groupings_by_courseid_permissions($courseid))
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");

        $groupings = group_db::moodbile_get_groupings_by_courseid($courseid, $startpage, $n);

        $returngroupings = array();
        foreach ($groupings as $grouping) {
            $grouping = new Grouping($grouping);
            $returngroupings[] = $grouping->get_data();
        }

        return $returngroupings;
    }

    private static function get_groupings_by_courseid_permissions($courseid) {
        /* És a partirs dels groupings que s'obtindrien els grups els quals sí
         * que ja controlem els permisos en get_groups_by_groupid
         * pero per obtenir els grups hauria de fer get_groups_by_groupingid
         */
        $course = course_db::moodbile_get_course_by_courseid($courseid);
        if ($course == null || $course == false) {
            return false;
        }
        $course_context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
        self::validate_context($course_context);
        //if can access all groups, also valid for groupings
        if (has_capability('moodle/site:accessallgroups', $course_context)) {
            return true;
        }
        //if he can create groups, s/he'd be able to put them in a grouping via web,
        //so s/he can view the groupings
        if (has_capability('moodle/course:managegroups', $course_context)) {
            return true;
        }
        return false;
    }

    public static function get_groupings_by_courseid_returns() {
        return new external_multiple_structure(
            Grouping::get_class_structure()
        );
    }

    public static function get_groupings_by_courseid_and_userid_parameters() {
        return new external_function_parameters(
            array(
                'courseid'  => new external_value(PARAM_INT, 'course id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'userid'    => new external_value(PARAM_INT, 'user id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    public static function get_groupings_by_courseid_and_userid($courseid, $userid, $startpage, $n) {
        $context = get_context_instance(CONTEXT_SYSTEM);

        self::validate_context($context);

        //$params = self::validate_parameters(self::get_groupings_by_courseid_and_userid_parameters(), array('params' => $parameters));

        if (!self::get_groupings_by_courseid_and_userid_permissions($courseid, $userid))
            throw new moodle_exception('nopermissions','moodbile_group', '',"Permission denied");

        $groupings = group_db::moodbile_get_groupings_by_courseid_and_userid($courseid, $startpage, $n, $userid);

        $returngroupings = array();
        foreach ($groupings as $grouping) {
            $grouping = new Grouping($grouping);
            $returngroupings[] = $grouping->get_data();
        }

        return $returngroupings;
    }

    private static function get_groupings_by_courseid_and_userid_permissions($courseid, $userid) {
        if (self::get_groupings_by_courseid_permissions($courseid))
            return true;
        global $USER;
        //if a user asks for his own groupings in a course return them
        //not sure about this, maybe its information disclosure as
        //I'm not sure if there's a place where a user can see own groupings
        if ($userid == $USER->id) {
            return true;
        }
        return false;
    }

    public static function get_groupings_by_courseid_and_userid_returns() {
        return new external_multiple_structure(
            Grouping::get_class_structure()
        );
    }
}