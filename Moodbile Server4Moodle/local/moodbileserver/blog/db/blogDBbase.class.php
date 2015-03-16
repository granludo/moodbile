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
 * Blog DataBase Class
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

abstract class blog_db_base {

    public static function moodbile_get_blog_posts_by_userid($userid, $startpage, $n) {
        global $MBL;
        require_once($MBL->mdllibdir.'/accesslib.php');

        $sql = "SELECT p.id, c.contextlevel, c.instanceid
                FROM {context} c, {blog_association} ba, {post} p
                WHERE p.userid = :userid AND
                      p.id = ba.blogid AND
                      ba.contextid = c.id";

        $sqlparams = array();
        $sqlparams['userid']    = $userid;

        $contexts = $MBL->DB->get_records_sql($sql,$sqlparams);

        $associationtable = array();
        foreach ($contexts as $context) {
            if ($context->contextlevel == CONTEXT_COURSE) {
                $associationtable[$context->id] = 'course';
            } else if ($context->contextlevel == CONTEXT_MODULE) {
                $associationtable[$context->id] = 'course_modules';
            }
        }

        $begin = $startpage*$n;
        return $MBL->DB->get_records('post', array('userid' => $userid), '', '*', $begin, $n);
    }

    public static function moodbile_get_blog_posts_by_courseid($courseid, $viewhidden, $startpage, $n) {
        global $MBL;

        $course = $MBL->DB->get_record('course', array('id' => $courseid));

        if ((!$course->visible) && (!$viewhidden)) {
            return null;
        }

        $sql = "SELECT c.contextlevel, c.instanceid
                FROM {context} c, {blog_association} ba, {post} p
                WHERE p.userid = :courseid AND
                      p.id = ba.blogid AND
                      ba.contextid = c.id";

        $sqlparams = array();
        $sqlparams['courseid']    = $courseid;

        $context = $MBL->DB->get_record_sql($sql,$sqlparams);

        $begin = $startpage*$n;
        return $MBL->DB->get_records('post', array('courseid' => $courseid), '', '*', $begin, $n);
    }

    public static function moodbile_get_file_itemid($fileidarray) {
        global $MBL;

        // Only need to check for the first file since all itemids should be the same for files belonging
        // to the same post
        $file = $fileidarray[0];
        $fileid = $file['fileid'];
        $itemid = $MBL->DB->get_record('files', array('id' => $fileid), 'itemid', MUST_EXIST);

        return $itemid;
    }

    public static function moodbile_get_post_by_id($postid) {
        global $MBL;

        return $MBL->DB->get_record('post', array('id', $postid), '*', MUST_EXIST);
    }

    public static function moodbile_get_courseid_by_modid($modid) {
        global $MBL;

        return $MBL->DB->get_record('course_modules', array('id', $modid), 'course', MUST_EXIST);
    }
}
