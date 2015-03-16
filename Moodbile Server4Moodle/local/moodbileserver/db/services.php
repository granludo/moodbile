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
 * External Function List
 *
 * @package MoodbileServer
 * @subpackage Database
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

$functions = array(

    // === course related functions ===

    'mbl_course_get_courses_by_userid' => array(
        'classname'   => 'moodbileserver_course_external',
        'methodname'  => 'get_courses_by_userid',
        'classpath'   => 'local/moodbileserver/course/externallib.php',
        'description' => 'Get courses by user ID',
        'type'        => 'read',
        'capabilities'=> 'moodle/course:view',
    ),

    'mbl_course_get_course_modules' => array(
        'classname'   => 'moodbileserver_course_external',
        'methodname'  => 'get_course_modules',
        'classpath'   => 'local/moodbileserver/course/externallib.php',
        'description' => 'Get all modules of a course',
        'type'        => 'read',
        'capabilities'=> 'moodle/course:view, moodle/course:viewhiddenactivities',
    ),

    // === forum related functions ===

    'mbl_forum_get_forum_by_id' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_forum_by_id',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns a forum usingn its id',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_forum_get_forums_by_courseid' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_forums_by_courseid',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns an array of forums belonging to a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_forum_get_forums_by_userid' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_forums_by_userid',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns a list of forums the user has access to',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_forum_get_forum_discussions' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_forum_discussions',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns a list of discussions belonging to a forum',
        'type'        => 'read',
        'capabilities'=> 'mod/forum:viewdiscussion',
    ),

    'mbl_forum_get_discussion_by_id' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_discussion_by_id',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns a discussion using its id',
        'type'        => 'read',
        'capabilities'=> 'mod/forum:viewdiscussion',
    ),

    'mbl_forum_get_forum_by_discussionid' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_forum_by_discussion_id',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns a forum using the id of one of its discussions',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_forum_get_forum_by_postid' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_forum_by_postid',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns a forum using the id of one of its posts',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_forum_create_discussion' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'create_discussion',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns ',
        'type'        => 'write',
        'capabilities'=> 'mod/forum:startdiscussion',
    ),

    'mbl_forum_delete_discussion' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'delete_discussion',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Deletes a discussion ',
        'type'        => 'write',
        'capabilities'=> 'mod/forum:deleteanypost',
    ),

    'mbl_forum_get_posts_by_discussionid' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'get_posts_by_discussion_id',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Returns all posts belonging to the discussion',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_forum_create_post' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'create_post',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Creates a post ',
        'type'        => 'write',
        'capabilities'=> '',
    ),

    'mbl_forum_update_post' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'update_post',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Updates a post. Only updates the "subject" and "message" fields, while ignoring all other parameters passed',
        'type'        => 'write',
        'capabilities'=> '',
    ),

    'mbl_forum_delete_post' => array(
        'classname'   => 'moodbileserver_forum_external',
        'methodname'  => 'delete_post',
        'classpath'   => 'local/moodbileserver/mod/forum/externallib.php',
        'description' => 'Deletes a post ',
        'type'        => 'write',
        'capabilities'=> '',
    ),

    //== lang related function ==

    'mbl_lang_get_texts' => array(
        'classname'   => 'moodbileserver_lang_external',
        'methodname'  => 'get_texts',
        'classpath'   => 'local/moodbileserver/lang/externallib.php',
        'description' => 'Get texts by id',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_lang_get_all_texts' => array(
        'classname'   => 'moodbileserver_lang_external',
        'methodname'  => 'get_all_texts',
        'classpath'   => 'local/moodbileserver/lang/externallib.php',
        'description' => 'Get all texts of a module',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === user related functions ===

    'mbl_user_get_user' => array(
        'classname'   => 'moodbileserver_user_external',
        'methodname'  => 'get_user',
        'classpath'   => 'local/moodbileserver/user/externallib.php',
        'description' => 'Returns the details of the logged user',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_user_get_user_by_id' => array(
        'classname'   => 'moodbileserver_user_external',
        'methodname'  => 'get_user_by_userid',
        'classpath'   => 'local/moodbileserver/user/externallib.php',
        'description' => 'Returns the details of a user',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_user_get_user_by_username'=> array(
        'classname'   => 'moodbileserver_user_external',
        'methodname'  => 'get_user_by_username',
        'classpath'   => 'local/moodbileserver/user/externallib.php',
        'description' => 'Returns the details of a user',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_user_get_users_by_courseid'=> array(
        'classname'   => 'moodbileserver_user_external',
        'methodname'  => 'get_users_by_courseid',
        'classpath'   => 'local/moodbileserver/user/externallib.php',
        'description' => 'Returns the details of all users of a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === group related functions ===

    'mbl_group_get_group_by_id'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_group_by_groupid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns a group',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_users_by_groupid'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_group_members_by_groupid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the group members',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_users_by_groupingid'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_group_members_by_groupingid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the group members',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_groups_by_courseid'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_groups_by_courseid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the groups of a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_groups_by_groupingid'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_groups_by_groupingid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the groups of a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_user_course_groups'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_groups_by_courseid_and_userid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the groups of a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_groupings_by_courseid'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_groupings_by_courseid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the groupings of a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_group_get_user_course_groupings'=> array(
        'classname'   => 'moodbileserver_group_external',
        'methodname'  => 'get_groupings_by_courseid_and_userid',
        'classpath'   => 'local/moodbileserver/group/externallib.php',
        'description' => 'Returns the groupings of a course',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === grade related functions ===

    'mbl_grade_get_grade_items_by_userid' =>array(
        'classname'   => 'moodbileserver_grade_external',
        'methodname'  => 'get_grade_items_by_userid',
        'classpath'   => 'local/moodbileserver/grade/externallib.php',
        'description' => 'Returns the grade items of a user',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_grade_get_grade_items_by_courseid' =>array(
        'classname'   => 'moodbileserver_grade_external',
        'methodname'  => 'get_grade_items_by_courseid',
        'classpath'   => 'local/moodbileserver/grade/externallib.php',
        'description' => 'Returns the grade items of a user',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_grade_get_grades_by_itemid' =>array(
        'classname'   => 'moodbileserver_grade_external',
        'methodname'  => 'get_grades_by_itemid',
        'classpath'   => 'local/moodbileserver/grade/externallib.php',
        'description' => 'Returns the grades corresponding to a particular grade item',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_grade_get_user_grade_by_itemid' =>array(
        'classname'   => 'moodbileserver_grade_external',
        'methodname'  => 'get_user_grade_by_itemid',
        'classpath'   => 'local/moodbileserver/grade/externallib.php',
        'description' => 'Returns the grades corresponding to a particular grade item',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === calendar related functions ===

    'mbl_calendar_get_events' => array(
        'classname'   => 'moodbileserver_calendar_external',
        'methodname'  => 'get_events',
        'classpath'   => 'local/moodbileserver/calendar/externallib.php',
        'description' => 'Returns the events',
        'type'        => 'read',
        'capabilities'=> 'moodle/calendar:manageownentries',
    ),

    'mbl_calendar_create_event' => array(
        'classname'   => 'moodbileserver_calendar_external',
        'methodname'  => 'create_event',
        'classpath'   => 'local/moodbileserver/calendar/externallib.php',
        'description' => 'Creates an event',
        'type'        => 'write',
        'capabilities'=> 'moodle/calendar:manageownentries',
    ),

    'mbl_calendar_delete_event' => array(
        'classname'   => 'moodbileserver_calendar_external',
        'methodname'  => 'delete_event',
        'classpath'   => 'local/moodbileserver/calendar/externallib.php',
        'description' => 'Deletes an event',
        'type'        => 'write',
        'capabilities'=> 'moodle/calendar:manageownentries',
    ),

    'mbl_calendar_update_event' => array(
        'classname'   => 'moodbileserver_calendar_external',
        'methodname'  => 'update_event',
        'classpath'   => 'local/moodbileserver/calendar/externallib.php',
        'description' => 'Updates an event',
        'type'        => 'write',
        'capabilities'=> 'moodle/calendar:manageownentries',
    ),

    'mbl_calendar_export_events' => array(
        'classname'   => 'moodbileserver_calendar_external',
        'methodname'  => 'export_events_to_ical',
        'classpath'   => 'local/moodbileserver/calendar/externallib.php',
        'description' => 'Exports events to ical file',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === Assignment related functions ===

    'mbl_assign_get_assignments_by_courseid' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'get_assignments_by_courseid',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Gets course assignments',
        'type'        => 'read',
        'capabilities'=> 'mod/assignment:view',
    ),

    'mbl_assign_get_assignment_by_id' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'get_assignment_by_assigid',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Gets an assignment by its id',
        'type'        => 'read',
        'capabilities'=> 'mod/assignment:view',
    ),

    'mbl_assign_get_submission_by_assignid' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'get_submission_by_assigid',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Gets a submission',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_assign_get_submission_files' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'get_submission_files',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Gets submission files',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_assign_submit_online' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'submit_online_assignment',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Submits an online assignment',
        'type'        => 'write',
        'capabilities'=> 'mod/assignment:submit',
    ),

    'mbl_assign_submit_singleupload' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'submit_singleupload_assignment',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Submits a singleupload assignment',
        'type'        => 'write',
        'capabilities'=> 'mod/assignment:submit',
    ),

    'mbl_assign_submit_upload' => array(
        'classname'   => 'moodbileserver_assignment_external',
        'methodname'  => 'submit_upload_assignment',
        'classpath'   => 'local/moodbileserver/mod/assignment/externallib.php',
        'description' => 'Submits an upload assignment',
        'type'        => 'write',
        'capabilities'=> 'mod/assignment:submit',
    ),

    //=== System related functions ===

    'mbl_system_get_moodle_timezone' => array(
        'classname'   => 'moodbileserver_system_external',
        'methodname'  => 'get_server_timezone',
        'classpath'   => 'local/moodbileserver/system/externallib.php',
        'description' => 'Gets Moodle timezone offset in hours. Returns a float, for example, -1.5',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_system_get_capabilities' => array(
        'classname'   => 'moodbileserver_system_external',
        'methodname'  => 'get_capabilities',
        'classpath'   => 'local/moodbileserver/system/externallib.php',
        'description' => 'Gets capabilities in a specific context.',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === Blog related functions ===

    'mbl_blog_get_posts_by_userid' => array(
        'classname'   => 'moodbileserver_blog_external',
        'methodname'  => 'get_blog_posts_by_userid',
        'classpath'   => 'local/moodbileserver/blog/externallib.php',
        'description' => 'Returns the blog posts of a particular user',
        'type'        => 'read',
        'capabilities'=> 'moodle/user:readuserblogs',
    ),

    'mbl_blog_get_posts_by_courseid' => array(
        'classname'   => 'moodbileserver_blog_external',
        'methodname'  => 'get_blog_posts_by_courseid',
        'classpath'   => 'local/moodbileserver/blog/externallib.php',
        'description' => 'Returns the blog posts associated with a specific course',
        'type'        => 'read',
        'capabilities'=> 'moodle/user:readuserblogs, moodle/course:viewhiddencourses',
    ),

    'mbl_blog_create_post' => array(
        'classname'   => 'moodbileserver_blog_external',
        'methodname'  => 'create_blog_post',
        'classpath'   => 'local/moodbileserver/blog/externallib.php',
        'description' => 'Creates a new blog post',
        'type'        => 'write',
        'capabilities'=> 'moodle/blog:create',
    ),

    'mbl_blog_edit_post' => array(
        'classname'   => 'moodbileserver_blog_external',
        'methodname'  => 'edit_blog_post',
        'classpath'   => 'local/moodbileserver/blog/externallib.php',
        'description' => 'Edits an existing post',
        'type'        => 'write',
        'capabilities'=> 'moodle/blog:manageentries, moodle/blog:create',
    ),

    // === Files related functions ===

    'mbl_files_upload' => array(
        'classname'   => 'moodbileserver_files_external',
        'methodname'  => 'upload_file',
        'classpath'   => 'local/moodbileserver/files/externallib.php',
        'description' => 'Uploads a file',
        'type'        => 'write',
        'capabilities'=> '',
    ),

    'mbl_files_get_file_url' => array(
        'classname'   => 'moodbileserver_files_external',
        'methodname'  => 'get_file_url',
        'classpath'   => 'local/moodbileserver/files/externallib.php',
        'description' => 'Returns the URL of a file',
        'type'        => 'read',
        'capabilities'=> '',
    ),

//    'mbl_files_download' => array(
//        'classname'   => 'moodbileserver_files_external',
//        'methodname'  => 'download_file',
//        'classpath'   => 'local/moodbileserver/files/externallib.php',
//        'description' => 'Downloads a file',
//        'type'        => 'read',
//        'capabilities'=> '',
//    ),


    'mbl_files_get_user_filesinfo' => array(
        'classname'   => 'moodbileserver_files_external',
        'methodname'  => 'get_user_filesinfo',
        'classpath'   => 'local/moodbileserver/files/externallib.php',
        'description' => 'Gets name and id of user files',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // === Resource related functions ===

    'mbl_resource_get_resource' => array(
        'classname'   => 'moodbileserver_resource_external',
        'methodname'  => 'get_resource',
        'classpath'   => 'local/moodbileserver/mod/resource/externallib.php',
        'description' => 'Gets a resource (File resource type)',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    // ==== Message related function ===

    'mbl_message_get_messages' => array(
        'classname'   => 'moodbileserver_message_external',
        'methodname'  => 'get_messages',
        'classpath'   => 'local/moodbileserver/message/externallib.php',
        'description' => 'Gets messages',
        'type'        => 'read',
        'capabilities'=> '',
    ),

//    'mbl_message_send_message' => array(
//        'classname'   => 'moodbileserver_message_external',
//        'methodname'  => 'send_message',
//        'classpath'   => 'local/moodbileserver/message/externallib.php',
//        'description' => 'Sends a message',
//        'type'        => 'write',
//        'capabilities'=> '',
//     ),

     // ==== Quiz related function ===

     'mbl_quiz_export_quiz_to_xml' => array(
        'classname'   => 'moodbileserver_quiz_external',
        'methodname'  => 'export_quiz_to_xml',
        'classpath'   => 'local/moodbileserver/mod/quiz/externallib.php',
        'description' => 'Exports questions belonging to a quiz to XML',
        'type'        => 'read',
        'capabilities'=> '',
    ),

    'mbl_quiz_export_quiz_to_qti' => array(
        'classname'   => 'moodbileserver_quiz_external',
        'methodname'  => 'export_quiz_to_qti',
        'classpath'   => 'local/moodbileserver/mod/quiz/externallib.php',
        'description' => 'Exports questions belonging to a quiz to IMS QTI 2.0',
        'type'        => 'read',
        'capabilities'=> '',
     ),

     'mbl_quiz_answer_quiz' => array(
        'classname'   => 'moodbileserver_quiz_external',
        'methodname'  => 'answer_quiz',
        'classpath'   => 'local/moodbileserver/mod/quiz/externallib.php',
        'description' => 'Provides the answers to the questions of a quiz',
        'type'        => 'write',
        'capabilities'=> '',
     ),
);

$functionlist = array();
foreach ($functions as $key=>$value) {
    $functionlist[] = $key;
}

$services = array(
   'Moodbile web service'  => array(
        'functions' => $functionlist,
        'enabled' => 0,
        'restrictedusers' => 0,
    ),
);
