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
 * External user API
 *
 * @package     MoodbileServer
 * @subpackage  User
 * @copyright   2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis
 * @copyright   2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
 *
 * @author      Jordi Piguillem
 * @author      Nikolas Galanis
 * @author      Oscar Martinez Llobet
 *
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
require_once(dirname(__FILE__).'/../config.php');
global $MBL;
require_once("$MBL->mdllibdir/externallib.php");
require_once("$MBL->mblroot/user/user.class.php");
require_once("$MBL->mblroot/user/db/userDB.php");
require_once("$MBL->mblroot/lib.php");

class moodbileserver_user_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_user_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     *
     * Returns the logged user
     *
     * @return array of user
     */
    public static function get_user() {
        global $USER, $MBL;
        require_once($MBL->mblroot."/lib.php");

        $context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($context);

        $user = user_db::moodbile_get_user_by_id($USER->id);

        if (empty($user->deleted)) {
            $user->avatar = get_link(new user_picture($user));
        }

        $user = new User($user);
        $user = $user->get_data();

        return $user;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_returns() {
        return User::get_class_structure();
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_user_by_userid_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
                 )
        );
    }

    /**
     *
     * Returns a user
     *
     * @param array $userids - An array of user ids used to recover details for the various users
     *
     * @return array of user
     */
    public static function get_user_by_userid($userid) {
        global $CFG, $MBL, $USER;
        require_once($CFG->dirroot."/user/lib.php");
        require_once($MBL->mblroot."/lib.php");

        $context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($context);

        $user_context = get_context_instance(CONTEXT_USER, $USER->id);

        $params = self::validate_parameters(self::get_user_by_userid_parameters(), array('userid' => $userid));

        $user = user_db::moodbile_get_user_by_id($userid);

        if (empty($user->deleted)) {
            $user->avatar = get_link(new user_picture($user));

        }

        $user = new User($user);
        $user = $user->get_data();
        return $user;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_by_userid_returns() {
        return User::get_class_structure();
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_user_by_username_parameters() {
        return new external_function_parameters(
            array(
                'username' 	=> new external_value(PARAM_TEXT, 'user name', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
            )
        );
    }

    /**
     *
     * Function to get user details
     *
     * @param text $username - A username
     *
     * @return user details
     */
    public static function get_user_by_username($username) {
        global $CFG, $MBL;
        require_once($CFG->dirroot."/user/lib.php");
        require_once($MBL->mblroot."/lib.php");

        $context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($context);

        $params = self::validate_parameters(self::get_user_by_username_parameters(), array('username'=>$username));

        $user = user_db::moodbile_get_user_by_username($username);

        if (empty($user->deleted)) {
            $user->avatar = get_link(new user_picture($user));
        }

        $user = new User($user);
        $user = $user->get_data();
        return $user;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_by_username_returns() {
        return User::get_class_structure();
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_users_by_courseid_parameters() {
        return new external_function_parameters(
            array(
                'courseid'  => new external_value(PARAM_INT, 'course ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     *
     * Function to get list of users enrolled in a course
     *
     * @param text $username - A username
     *
     * @return user details
     */
    public static function get_users_by_courseid($courseid, $startpage, $n) {
        global $MBL;
        require_once($MBL->mblroot."/lib.php");

        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        self::validate_context($context);

        $users = user_db::moodbile_get_users_by_courseid($courseid, $startpage, $n);
        $returns = array();
        foreach ($users as $user) {
            $user->avatar = get_link(new user_picture($user));
            $user = new User($user);
            $returns[] = $user->get_data();
        }
        return $returns;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_users_by_courseid_returns() {
        return new external_multiple_structure(
            User::get_class_structure()
        );
    }
}
