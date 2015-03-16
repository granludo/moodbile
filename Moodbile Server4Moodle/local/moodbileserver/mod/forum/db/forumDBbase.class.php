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
 * Forum DataBase base Class
 *
 * @package MoodbileServer
 * @subpackage Forum
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

class forum_db_base {

    /**
     * Given an object containing all the necessary data,
     * create a new discussion and return the id
     *
     * @global object
     * @global object
     * @global object
     * @param object $post
     * @param mixed $mform
     * @param string $message
     * @param int $userid
     * @return object
     */
    public static function moodbile_forum_add_discussion($discussion, $mform=null, &$message=null, $userid=null) {
        global $USER, $CFG, $MBL;

        $timenow = time();

        if (is_null($userid)) {
            $userid = $USER->id;
        }

        // The first post is stored as a real post, and linked
        // to from the discuss entry.

        $forum = $MBL->DB->get_record('forum', array('id'=>$discussion->forum));
        $cm    = get_coursemodule_from_instance('forum', $forum->id);

        $post = new stdClass();
        $post->discussion    = 0;
        $post->parent        = 0;
        $post->userid        = $userid;
        $post->created       = $timenow;
        $post->modified      = $timenow;
        $post->mailed        = 0;
        $post->subject       = $discussion->name;
        $post->message       = $discussion->message;
        $post->messageformat = $discussion->messageformat;
        $post->messagetrust  = $discussion->messagetrust;
        $post->attachments   = isset($discussion->attachments) ? $discussion->attachments : null;
        $post->forum         = $forum->id;     // speedup
        $post->course        = $forum->course; // speedup
        $post->mailnow       = $discussion->mailnow;

        $post->id = $MBL->DB->insert_record("forum_posts", $post);

        // TODO: Fix the calling code so that there always is a $cm when this function is called
        if (!empty($cm->id) && !empty($discussion->itemid)) {   // In "single simple discussions" this may not exist yet
            $context = get_context_instance(CONTEXT_MODULE, $cm->id);
            $text = file_save_draft_area_files($discussion->itemid, $context->id, 'mod_forum', 'post', $post->id, array('subdirs'=>true), $post->message);
            $MBL->DB->set_field('forum_posts', 'message', $text, array('id'=>$post->id));
        }

        // Now do the main entry for the discussion, linking to this first post

        $discussion->firstpost    = $post->id;
        $discussion->timemodified = $timenow;
        $discussion->usermodified = $post->userid;
        $discussion->userid       = $userid;

        $post->discussion = $MBL->DB->insert_record("forum_discussions", $discussion);

        // Finally, set the pointer on the post.
        $MBL->DB->set_field("forum_posts", "discussion", $post->discussion, array("id"=>$post->id));

        if (!empty($cm->id)) {
            forum_add_attachment($post, $forum, $cm, $mform, $message);
        }

        if (forum_tp_can_track_forums($forum) && forum_tp_is_tracked($forum)) {
            forum_tp_mark_post_read($post->userid, $post, $post->forum);
        }

        $returns = array();
        $returns['discid'] = $post->discussion;
        $returns['postid'] = $discussion->firstpost;

        return $returns;
    }


    /**
     * Returns a forum using its id
     *
     * @param int $forumid
     * @param object $context
     * @throws Exception
     *
     * @return a forum
     */
    public static function moodbile_get_forum_by_id($forumid, $canviewhidden) {
        global $MBL;

        $viewhidden = '';
        if(!$canviewhidden) {
            $viewhidden = 'AND cm.visible = 1';
        }

        if(!$cm = get_coursemodule_from_instance('forum', $forumid)) {
            throw new moodle_exception('generalexceptionmessage','moodbile_forum', '','Forum not found');
        }

        $params = array();
        $params['forumid'] = $forumid;
        $params['modulename'] = 'forum';

        $sql = "SELECT f.*
                        FROM {forum} f, {course_modules} cm, {modules} md
                        WHERE f.id = :forumid AND
                              cm.instance = f.id AND
                              md.name = :modulename AND
                              cm.module = md.id $viewhidden";

        return $MBL->DB->get_record_sql($sql, $params);
    }

    /**
     * Returns a list of n forums belonging to
     * the course with id = courseid starting from page n
     *
     * @param int $courseid
     * @param object $context
     * @param int $startpage
     * @param int $n
     *
     * @return a list of forum
     */
    public static function moodbile_get_forums_by_courseid($courseid, $canviewhidden, $context, $startpage, $n) {
        global $MBL;

        $viewhidden = '';
        if(!$canviewhidden) {
            $viewhidden = 'AND cm.visible = 1';
        }

        $params = array();
        $params['courseid'] = $courseid;
        $params['modulename'] = 'forum';

        $sql = "SELECT f.*
                        FROM {course_modules} cm, {modules} md, {forum} f
                        WHERE cm.course = :courseid AND
                              cm.instance = f.id AND
                              md.name = :modulename AND
                              cm.module = md.id $viewhidden";

        $begin = $startpage * $n;
        return $MBL->DB->get_records_sql($sql, $params, $begin, $n);
    }

    /**
     * Returns a list of n forums belonging to
     * the user with id = userid starting from page n
     *
     * @param int $userid
     * @param object $context
     * @param int $startpage
     * @param int $n
     *
     * @return a list of forum
     */
//    public static function moodbile_get_user_forums($userid, $canviewhidden, $canviewhiddencourses, $context, $startpage, $n) {
//        global $MBL;
//
//        $viewhiddenactivities = '';
//        if($canviewhidden) {
//            $viewhiddenactivities = 'WHERE cm.visible = 1';
//        }
//
//        $viewhiddencourses = '';
//        if($canviewhiddencourses) {
//            $viewhiddencourses = 'WHERE c.visible = 1';
//        }
//
//        $sqlparams = array();
//        $sqlparams['active'] = ENROL_USER_ACTIVE;
//        $sqlparams['enabled'] = ENROL_INSTANCE_ENABLED;
//
//        $forumsql =    "SELECT *
//                        FROM {forum} f
//                        JOIN (SELECT c.id
//                                FROM {course} c
//                                JOIN (SELECT DISTINCT e.courseid
//                                    FROM {enrol} e
//                                    JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = $userid)
//                                    WHERE ue.status = :active AND e.status = :enabled
//                                ) en ON (en.courseid = c.id) $viewhiddencourses
//                            ) c ON (f.course = c.id)
//                            JOIN {course_modules} cm ON (cm.course = c.id)
//                            AND (cm.module = ( SELECT m.id
//                                FROM mdl_modules m
//                                WHERE m.name = 'forum')
//                        ) $viewhiddenactivities";
//
//        $begin = $startpage * $n;
//
//        return $MBL->DB->get_records_sql($forumsql, $sqlparams, $begin, $n);
//    }

    /**
     * Returns a list of n discussions belonging to
     * a forum with id = forumid starting from page n
     *
     * @param int $forumid
     * @param object $context
     * @param int $startpage
     * @param int $n
     *
     * @return a list of discussions
     */
     public static function moodbile_get_forum_discussions($forumid, $canviewhidden, $sort="d.timemodified DESC", $startpage, $n) {
        global $MBL;

        $viewhidden = '';
        if(!$canviewhidden) {
            $viewhidden = 'AND cm.visible = 1';
        }

        $params = array();
        $params['forumid'] = $forumid;
        $params['modulename'] = 'forum';

        $sql = "SELECT d.*
                FROM {forum_discussions} d, {forum} f, {course_modules} cm, {modules} md
                WHERE f.id = :forumid AND
                      d.forum = f.id AND
                      cm.instance = d.forum AND
                      md.name = :modulename AND
                      cm.module = md.id $viewhidden
                ORDER BY $sort";

        $begin = $startpage * $n;
        return $MBL->DB->get_records_sql($sql, $params, $begin, $n);
     }

    /**
     * Returns a forum
     *
     * @param int $discid
     *
     * @return a forum
     */
    public static function moodbile_get_forum_by_discussion_id($discid) {
        global $MBL;

        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $discid), '*', MUST_EXIST);
        $forum      = $MBL->DB->get_record('forum', array('id' => $discussion->forum), '*', MUST_EXIST);

       return $forum;
    }

    /**
     * Returns a forum
     *
     * @param int $postid
     *
     * @return a forum
     */
    public static function moodbile_get_forum_by_postid($postid) {
        global $MBL;

        $post = $MBL->DB->get_record('forum_posts', array('id' => $postid), '*', MUST_EXIST);
        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $post->discussion), '*', MUST_EXIST);
        $forum      = $MBL->DB->get_record('forum', array('id' => $discussion->forum), '*', MUST_EXIST);

       return $forum;
    }

    /**
     * Returns an array of forums belonging to the specified user
     *
     * @param int $userid
     * @param array $viewhidden an array containing all the visible forums by the user
     * @param int $startpage
     * @param int $n
     *
     * @return an array of forum
     */
    public static function moodbile_get_forums_by_userid($userid, $viewable, $startpage, $n) {
        global $MBL;

        $params = array();
        $idlist = implode(",", $viewable);

        $sql = "SELECT f.*
                FROM {forum} f
                WHERE f.id IN (".$idlist.")
                ORDER BY f.course ASC";

        $begin = $startpage * $n;
        $forums = $MBL->DB->get_records_sql($sql, null, $begin, $n);
        return $forums;
    }


    /**
     * Returns a discussion
     *
     * @param int $postid
     *
     * @return a discussion
     */
    public static function moodbile_get_discussion_by_post_id($postid) {
        global $MBL;

        $post = $MBL->DB->get_record('forum_posts', array('id' => $postid), '*', MUST_EXIST);
        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $post->discussion), '*', MUST_EXIST);

       return $discussion;
    }

    /**
     * Returns a discussion
     *
     * @param int $discid
     *
     * @return a discussion
     */
    public static function moodbile_get_discussion_by_id($discid) {
        global $MBL;

        $sql = "SELECT d.*
                FROM {forum_discussions} d
                WHERE d.id = $discid";

        return $MBL->DB->get_record_sql($sql);
    }

    /**
     * Creates a discussion in a forum
     *
     * @param Discussion $discussion
     *
     * @return int discussion id
     * @return int first post id
     */
    public static function moodbile_create_discussion($discussion, $canviewhidden) {
        global $MBL;

        $return = -1;
        $forum = $MBL->DB->get_record('forum', array('id' => $discussion['forumid']), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('forum', $forum->id);
        if ($discussion['groupid'] == -1 ) {
            $groupid = null;
        } else {
            $groupid = $discussion['groupid'];
        }

        if(forum_user_can_post_discussion((object)$forum, $groupid, -1, $cm, NULL)) {
            if($cm->visible or(!$cm->visible and $canviewhidden)) {
                $newdiscussion = new StdClass();
                $newdiscussion->forum = $forum->id;
                $newdiscussion->course = $forum->course;
                $newdiscussion->name = $discussion['name'];
                $newdiscussion->groupid = $discussion['groupid'];
                $newdiscussion->message = clean_text($discussion['intro']);
                $newdiscussion->messageformat = $discussion['format'];
                $newdiscussion->attachments = $discussion['attachments'];
                $newdiscussion->mailnow = $discussion['mailnow'];
                $newdiscussion->messagetrust = 0;

                // Create discussion
                $return = self::moodbile_forum_add_discussion($newdiscussion);
            }
        }

        if ($return == -1){
            throw new moodle_exception('nopermissions','moodbile_forum', '',"Permission denied");
        }
        return $return;
    }

    /**
     * Delete discussion with id = discid
     *
     * @param int $discid
     *
     * @return bool success
     */
    public static function moodbile_delete_discussion($discid) {
        global $MBL,$CFG;
        require_once("$CFG->dirroot/mod/forum/lib.php");

        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $discid), '*', MUST_EXIST);
        $forum = $MBL->DB->get_record('forum', array('id' => $discussion->forum), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('forum', $forum->id);

        $course = $MBL->DB->get_record('course', array('id' => $forum->course));
        $result = forum_delete_discussion($discussion, false, $course, $cm, $forum);

        return $result;
    }

    /**
     * Returns n posts of a discussion starting from page startpage
     *
     * @param int $discid
     * @param int $startpage
     * @param int $n
     *
     * @return array of posts
     */
    public static function moodbile_get_posts_by_discussion_id($discid, $forum, $context, $startpage, $n) {
        global $MBL,$CFG;
        require_once("$CFG->dirroot/mod/forum/lib.php");

        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $discid), '*', MUST_EXIST);
        if (forum_user_can_see_discussion((object)$forum, (object)$discussion, $context)) {
            $sql = "SELECT *
                    FROM {forum_posts} fp
                    WHERE fp.discussion = $discussion->id";

            $begin = $startpage * $n;
            $posts = $MBL->DB->get_records_sql($sql, null, $begin, $n);
        }

        return $posts;
    }

    /**
     * Creates a post
     *
     * @param object $post
     * @throws Exception
     *
     * @return int postid
     */
    public static function moodbile_create_post($post, $forum) {
        global $MBL,$CFG;
        require_once("$CFG->dirroot/mod/forum/lib.php");

        $MBL->DB->get_record('forum_posts', array('id' => $post->parent), '*', MUST_EXIST);

        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $post->discussion), '*', MUST_EXIST);

        $cm = get_coursemodule_from_instance('forum', $forum->id);
        $course = $MBL->DB->get_record('course', array('id' => $forum->course));
        if(!forum_user_can_post((object)$forum, (object)$discussion, NULL, $cm, $course, NULL)) {
            throw new moodle_exception('User has no permission to post!');
        }

        $postid = forum_add_new_post($post, $mform = null, $message = null);

        return $postid;
    }

    /**
     * Updates an existing post
     *
     * @param int $postid
     * @param text $subject
     * @param text $message
     * @throws Exception
     *
     * @return bool success
     */
    public static function moodbile_update_post($postid, $subject, $message, $cm, $caneditpost) {
        global $MBL, $USER, $CFG;
        require_once("$CFG->dirroot/mod/forum/lib.php");

        $post = $MBL->DB->get_record('forum_posts', array('id' => $postid), '*', MUST_EXIST);
        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $post->discussion), '*', MUST_EXIST);

        if ($caneditpost) {
            $post->id = $postid;
            $post->subject = $subject;
            $post->message = clean_text($message);
            $post->itemid = file_get_submitted_draft_itemid('message');
            $post->timestart = $discussion->timestart;
            $post->timeend = $discussion->timeend;

            $result = forum_update_post($post, null, $cm);

        } else {
            throw new moodle_exception('nopermissions','moodbile_forum', '','Insufficient edit permissions');
        }

        return $result;
    }

    /**
     * Delete a POST
     *
     * @throws Exception
     */
    public static function moodbile_delete_post($postid, $candeleteownpost, $candeleteallposts) {
        global $MBL, $USER, $CFG;
        require_once("$CFG->dirroot/mod/forum/lib.php");

        $post = $MBL->DB->get_record('forum_posts', array('id' => $postid), '*', MUST_EXIST);
        $discussion = $MBL->DB->get_record('forum_discussions', array('id' => $post->discussion), '*', MUST_EXIST);
        $forum = $MBL->DB->get_record('forum', array('id' => $discussion->forum), '*', MUST_EXIST);
        $course = $MBL->DB->get_record('course', array('id' => $forum->course));
        $cm = get_coursemodule_from_instance('forum', $forum->id);

        if($candeleteallposts or ($candeleteownpost and $USER->id == $post->userid)) {
            //Passing all these parameters are necessary for recursion efficiency
            //TODO: Ask if delete all children too
            if(!$post->parent) { // post is a discussion topic as well, so delete discussion
                if($forum->type == 'single') {
                    throw new moodle_exception('nopermissions','moodbile_forum', '',"Forum cannot be deleted");
                }
                $result = forum_delete_discussion($discussion, false, $course, $cm, $forum);

                add_to_log($discussion->course, "forum", "delete discussion", "view.php?id=$cm->id", "$forum->id", $cm->id);
            } else {
                $result = forum_delete_post($post, true, $course, $cm, $forum, false);
            }

        } else {
            throw new moodle_exception('nopermissions','moodbile_forum', '',"Permission denied");
        }

        return $result;
    }

}