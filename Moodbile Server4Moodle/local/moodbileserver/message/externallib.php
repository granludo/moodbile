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
 * Message External Functions Library
 *
 * @package MoodbileServer
 * @subpackage Message
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

require_once($CFG->dirroot.'/message/lib.php');

require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot . '/message/message.class.php');
require_once($MBL->mblroot . '/message/db/messageDB.php');
require_once($MBL->mblroot . '/user/db/userDB.php');

class moodbileserver_message_external extends external_api {

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

    public static function get_messages_parameters() {
    	return new external_function_parameters (
    		array(
    			'useridto'   => new external_value(PARAM_INT, 'user id to', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'unreadonly' => new external_value(PARAM_BOOL, 'unread only', VALUE_DEFAULT, false, NULL_NOT_ALLOWED),
                'startpage'  => new external_value(PARAM_INT, 'start page (no effect if useridto is set)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'limit'      => new external_value(PARAM_INT, 'number of messages limit', VALUE_DEFAULT, 15, NULL_NOT_ALLOWED),
    		)
    	);
    }

    public static function get_messages($useridto, $unreadonly, $startpage, $limit) {
        global $USER;
        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);
        if (empty($useridto)) {
            $messages = message_db::moodbile_get_recent_messages($USER, $unreadonly, $startpage*$limit, $limit);
        }
        else {
            $user = user_db::moodbile_get_user_by_id($useridto);
            if ($user == null || $user == false) {
                throw new Exception('User does not exist');
            }
            $messages = message_get_history($USER->id, $useridto, $limit, false);
            //$messages = message_db::moodbile_get_message_history($USER->id, $params['useridto'],$params['limit']);
        }
        $returnmessages = array();
        foreach ($messages as $message) {
            if (isset($message->mid)) {//HACK
                $message->id = $message->mid;
            }
            $message->useridfrom = $USER->id;
            $message = new Message($message);
            $returnmessages[] = $message->get_data();
        }

        return $returnmessages;
    }

    public static function get_messages_returns() {
        return new external_multiple_structure(
            Message::get_class_structure()
        );
    }

    public static function send_message_parameters() {
        return new external_function_parameters (
            array(
                'useridto' => new external_value(PARAM_INT, 'user id to', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            	'message'  => new external_value(PARAM_TEXT, 'text message', VALUE_REQUIRED,0, NULL_ALLOWED),
            )
        );
    }

    public static function send_message($useridto, $message) {
        global $USER;
        global $CFG;

        require_once("$CFG->dirroot/message/lib.php");
        $system_context = get_context_instance(CONTEXT_SYSTEM);
        self::validate_context($system_context);

        require_capability('moodle/site:sendmessage', $system_context);

        $userto = user_db::moodbile_get_user_by_id($useridto);

        if ($contact = message_get_contact($useridto)) {
           if ($contact and $contact->blocked) {
                return false;
           }
        }
        $userpreferences = get_user_preferences(NULL, NULL, $useridto);

        if (!empty($userpreferences['message_blocknoncontacts'])) {  // User is blocking non-contacts
           if (empty($contact)) {   // We are not a contact!
               return false;
           }
        }
        return array ('messageid' => message_post_message($USER, $userto, $message, FORMAT_PLAIN));
    }


    public static function send_message_returns() {
        return new external_single_structure(
            array(
                'messageid' => new external_value(PARAM_INT, 'Id of the new message', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            )
        );
    }
}

//get_contacts
//block contact