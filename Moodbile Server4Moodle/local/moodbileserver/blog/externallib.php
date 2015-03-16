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
 * Blog External Function Library
 *
 * @package MoodbileServer
 * @subpackage Blog
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
require_once($MBL->mblroot . '/blog/blogpost.class.php');
require_once($MBL->mblroot . '/blog/db/blogDB.php');

class moodbileserver_blog_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_blog_posts_by_userid_parameters() {
        return new external_function_parameters(
            array(
                'userid'    => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns an array of the blog posts of a user
     *
     * @param int userid
     * @param int startpage
     * @param int n
     *
     * @return array An array of arrays
     */
    public static function get_blog_posts_by_userid($userid, $startpage, $n) {
//        $params = self::validate_parameters(self::get_blog_posts_by_userid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

        $context = get_context_instance(CONTEXT_USER, $userid);
        require_capability('moodle/user:readuserblogs', $context);
        self::validate_context($context);

        $blogposts = blog_db::moodbile_get_blog_posts_by_userid($userid, $startpage, $n);

        $returnposts = array();
        foreach ($blogposts as $blogpost) {
            $blogpost = new BlogPost($blogpost);
            $returnposts[] = $blogpost->get_data();
        }

        return $returnposts;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_blog_posts_by_userid_returns() {
        return
            new external_multiple_structure(
                BlogPost::get_class_structure()
            );
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_blog_posts_by_courseid_parameters() {
        return new external_function_parameters(
            array(
                'courseid'  => new external_value(PARAM_INT, 'user ID', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'startpage' => new external_value(PARAM_INT, 'start page', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'n'         => new external_value(PARAM_INT, 'page number', VALUE_DEFAULT, 10, NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns an array of the blog posts of a user
     *
     * @param int courseid
     * @param int startpage
     * @param int n
     *
     * @return array An array of arrays
     */
    public static function get_blog_posts_by_courseid($courseid, $startpage, $n) {
//        $params = self::validate_parameters(self::get_blog_posts_by_courseid_parameters(), array('params' => $parameters));
//        $params = $params['params'];

        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        require_capability('moodle/user:readuserblogs', $context);
        self::validate_context($context);

        $viewhidden = false;
        if (has_capability('moodle/course:viewhiddencourses', $context)) {
            $viewhidden = true;
        }

        $blogposts = blog_db::moodbile_get_blog_posts_by_courseid($courseid, $viewhidden, $startpage, $n);

        $returnposts = array();
        foreach ($blogposts as $blogpost) {
            $blogpost = new BlogPost($blogpost);
            $returnposts[] = $blogpost->get_data();
        }

        return $returnposts;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_blog_posts_by_courseid_returns() {
        return
            new external_multiple_structure(
                BlogPost::get_class_structure()
            );
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function create_blog_post_parameters() {
        return new external_function_parameters(
            array(
                'subject'       => new external_value(PARAM_TEXT, 'blog post subject', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'summary'       => new external_value(PARAM_TEXT, 'blog post body', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'courseid'      => new external_value(PARAM_INT,  'course id number', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'moduleid'      => new external_value(PARAM_INT,  'module id number', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'contextid'     => new external_value(PARAM_INT,  'context module id number', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED), // TODO: see if contextid is needed
                'attachments'   => new external_multiple_structure(
                                        new external_single_structure(
                                            array(
                                                'fileid' => new external_value(PARAM_INT,  'file id', VALUE_OPTIONAL)
                                            )
                                        ), 'attached file ids', VALUE_OPTIONAL),
                'tags'          => new external_single_structure(
                                            array(
                                                'officialtags'  => new external_value(PARAM_TEXT, 'official tags', VALUE_OPTIONAL),
                                                'othertags'     => new external_value(PARAM_TEXT, 'custom, user-defined tags', VALUE_OPTIONAL)
                                            ), 'blog post tags', VALUE_OPTIONAL
                                   )
            )
        );
    }

    /**
     * Creates a blog post
     *
     * @param text subject
     * @param text summary
     * @param int courseid
     * @param int moduleid
     * @param int contextid
     * @param int array of attacments
     * @param text array of tags
     * @return BlogPost
     */
    public static function create_blog_post($subject, $summary, $courseid, $moduleid, $contextid, $attachments=null, $tags=null) {
        global $MBL, $CFG;
        require_once($CFG->dirroot . '/blog/lib.php');
        require_once($CFG->dirroot . '/blog/locallib.php');
        require_once($CFG->dirroot . '/blog/edit_form.php');

        $context = get_context_instance(CONTEXT_SYSTEM);
        require_capability('moodle/blog:create', $context); // This capability can only be checked in CONTEXT_SYSTEM
        self::validate_context($context);

        // Load values for all necessary parameters
        $summaryoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>true, 'context'=>$context);
        $attachmentoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes);
        $entry  = new stdClass();
        $entry->id = null;
        $courseid = $courseid;
        $modid = $moduleid;
        $sitecontext = $context;

        $blogeditform = new blog_edit_form(null, compact('entry', 'summaryoptions', 'attachmentoptions', 'sitecontext', 'courseid', 'modid'));

        $entry = file_prepare_standard_editor($entry, 'summary', $summaryoptions, $sitecontext, 'blog', 'post', $entry->id);
        $entry = file_prepare_standard_filemanager($entry, 'attachment', $attachmentoptions, $sitecontext, 'blog', 'attachment', $entry->id);
        $entry->summary_editor['text'] = $summary;
        $entry->action = 'add';

        $data  = new stdClass();
        $data->subject = $subject;
        $data->summary_editor = $entry->summary_editor;
        if ($attachments != null) {
            $fileitemid = blog_db::moodbile_get_file_itemid($attachments);
            $data->attachment_filemanager = $fileitemid->itemid;
        }
        $data->publishstate = 'site';
        $data->tags = explode(', ', $tags['othertags']);
        $data->submitbutton = 'Save changes';
        $data->action = 'add';
        $data->entryid = 0;
        $data->modid = $modid;
        $data->courseid = $courseid;

        // Add correct associations
        if (($courseid != 0) && ($modid == 0)) {
            $context = get_context_instance(CONTEXT_COURSE, $courseid);
            $data->courseassoc = $context->id;
        }

        if ($modid != 0) {
            // Redundant $courseid reloading done to make sure that the courseid provided
            // corresponds to the correct mod
            $courseid = blog_db::moodbile_get_courseid_by_modid($modid);
            $coursecontext = get_context_instance(CONTEXT_MODULE, $courseid);
            $data->courseassoc = $coursecontext->id;

            $modcontext = get_context_instance(CONTEXT_MODULE, $modid);
            $data->modassoc = $modcontext->id;
        }

        $blogeditform->set_data($entry);

        $blogentry = new blog_entry(null, $data, $blogeditform);
        $blogentry->add();
        $blogentry->edit($data, $blogeditform, $summaryoptions, $attachmentoptions);

        return $blogentry;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function create_blog_post_returns() {
        return BlogPost::get_class_structure();
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function edit_blog_post_parameters() {
        return new external_function_parameters(
            array(
                'post' => BlogPost::get_class_structure()
            )
        );
    }

    /**
     * Creates a blog post
     *
     * @param int post id
     * @param text subject
     * @param text summary
     * @param int array of attacments
     * @param text array of tags
     * @return BlogPost
     */
    public static function edit_blog_post($parameters) {
        global $MBL, $CFG, $USER;
        require_once($CFG->dirroot . '/blog/lib.php');
        require_once($CFG->dirroot . '/blog/locallib.php');
        require_once($CFG->dirroot . '/blog/edit_form.php');

        $params = self::validate_parameters(self::edit_blog_post_parameters(), array('post' => $parameters));
        $params = $params['post'];

        $context = get_context_instance(CONTEXT_USER, $USER->id);
        require_capability('moodle/blog:create', $context);
        require_capability('moodle/blog:manageentries', $context);
        self::validate_context($context);

        if (!$entry = new blog_entry($params['postid'])) {
            print_error('wrongentryid', 'blog');
        }

        if (!blog_user_can_edit_entry($entry)) {
            print_error('notallowedtoedit', 'blog');
        }
        $userid = $entry->userid;
        $entry->subject      = clean_text($entry->subject);
        $entry->summary      = clean_text($entry->summary, $entry->format);

        if ($CFG->useblogassociations && ($blogassociations = $DB->get_records('blog_association', array('blogid' => $entry->id)))) {

            foreach ($blogassociations as $assocrec) {
                $contextrec = $DB->get_record('context', array('id' => $assocrec->contextid));

                switch ($contextrec->contextlevel) {
                    case CONTEXT_COURSE:
                        $entry->courseassoc = $assocrec->contextid;
                        break;
                    case CONTEXT_MODULE:
                        $entry->modassoc = $assocrec->contextid;
                        break;
                }
            }
        }

        $sitecontext = $context;

        $summaryoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>true, 'context'=>$sitecontext);
        $attachmentoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes);

        $blogeditform = new blog_edit_form(null, compact('entry', 'summaryoptions', 'attachmentoptions', 'sitecontext', 'courseid', 'modid'));

        $entry = file_prepare_standard_editor($entry, 'summary', $summaryoptions, $sitecontext, 'blog', 'post', $entry->id);
        $entry = file_prepare_standard_filemanager($entry, 'attachment', $attachmentoptions, $sitecontext, 'blog', 'attachment', $entry->id);

        if (!empty($CFG->usetags)) {
            include_once($CFG->dirroot.'/tag/lib.php');
            $entry->tags = tag_get_tags_array('post', $entry->id);
        }

        $blogeditform->set_data($entry);
        $data = $blogeditform->get_data();

        $entry->edit($data, $blogeditform, $summaryoptions, $attachmentoptions);

        return $entry;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function edit_blog_post_returns() {
        return BlogPost::get_class_structure();
    }
}
