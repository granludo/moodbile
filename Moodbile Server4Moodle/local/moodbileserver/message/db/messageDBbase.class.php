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
// along with Foobar.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Message DataBase Functions
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

abstract class message_db_base {

    private function conversationsort($a, $b) {
        if ($a->timecreated == $b->timecreated) {
            return 0;
        }
        return ($a->timecreated > $b->timecreated) ? -1 : 1;
    }

    public static function moodbile_get_recent_messages($user, $unreadonly, $limitfrom, $limitto) {
       global $MBL;

       $userfields = user_picture::fields('u', array('lastaccess'));

       $params = array('userid1' => $user->id, 'userid2' => $user->id, 'userid3' => $user->id);

       if (!$unreadonly) {
           $sql = "SELECT $userfields, mr.id as mid, mr.useridto, mr.useridfrom, mr.subject, mr.smallmessage, mr.fullmessage, mr.timecreated, mc.id as contactlistid, mc.blocked
                  FROM {message_read} mr
                  JOIN (
                        SELECT messages.userid AS userid, MAX(messages.mid) AS mid
                          FROM (
                               SELECT mr1.useridto AS userid, MAX(mr1.id) AS mid
                                 FROM {message_read} mr1
                                WHERE mr1.useridfrom = :userid1
                                      AND mr1.notification = 0
                             GROUP BY mr1.useridto
                                      UNION
                               SELECT mr2.useridfrom AS userid, MAX(mr2.id) AS mid
                                 FROM {message_read} mr2
                                WHERE mr2.useridto = :userid2
                                      AND mr2.notification = 0
                             GROUP BY mr2.useridfrom
                               ) messages
                      GROUP BY messages.userid
                       ) messages2 ON mr.id = messages2.mid AND (mr.useridto = messages2.userid OR mr.useridfrom = messages2.userid)
                  JOIN {user} u ON u.id = messages2.userid
             LEFT JOIN {message_contacts} mc ON mc.userid = :userid3 AND mc.contactid = u.id
                 WHERE u.deleted = '0'
              ORDER BY mr.id DESC";
            $read =  $MBL->DB->get_records_sql($sql, $params, $limitfrom, $limitto);
       }

       $sql = "SELECT $userfields, m.id as mid, m.useridto, m.useridfrom, m.subject, m.smallmessage, m.fullmessage, m.timecreated, mc.id as contactlistid, mc.blocked
                  FROM {message} m
                  JOIN (
                        SELECT messages.userid AS userid, MAX(messages.mid) AS mid
                          FROM (
                               SELECT m1.useridto AS userid, MAX(m1.id) AS mid
                                 FROM {message} m1
                                WHERE m1.useridfrom = :userid1
                                      AND m1.notification = 0
                             GROUP BY m1.useridto
                                      UNION
                               SELECT m2.useridfrom AS userid, MAX(m2.id) AS mid
                                 FROM {message} m2
                                WHERE m2.useridto = :userid2
                                      AND m2.notification = 0
                             GROUP BY m2.useridfrom
                               ) messages
                      GROUP BY messages.userid
                       ) messages2 ON m.id = messages2.mid AND (m.useridto = messages2.userid OR m.useridfrom = messages2.userid)
                  JOIN {user} u ON u.id = messages2.userid
             LEFT JOIN {message_contacts} mc ON mc.userid = :userid3 AND mc.contactid = u.id
                 WHERE u.deleted = '0'
                 ORDER BY m.id DESC";
       $unread =  $MBL->DB->get_records_sql($sql, $params, $limitfrom, $limitto);

       $conversations = array();

       //Union the 2 result sets together looking for the message with the most recent timecreated for each other user
       //$conversation->id (the array key) is the other user's ID
       $conversation_arrays = array($unread, $read);
       foreach ($conversation_arrays as $conversation_array) {
           foreach ($conversation_array as $conversation) {
               if (empty($conversations[$conversation->id]) || $conversations[$conversation->id]->timecreated < $conversation->timecreated ) {
                   $conversations[$conversation->id] = $conversation;
               }
           }
       }

       //Sort the conversations. This is a bit complicated as we need to sort by $conversation->timecreated
       //and there may be multiple conversations with the same timecreated value.
       //The conversations array contains both read and unread messages (different tables) so sorting by ID won't work
       usort($conversations, "self::conversationsort");

       return $conversations;
    }
}