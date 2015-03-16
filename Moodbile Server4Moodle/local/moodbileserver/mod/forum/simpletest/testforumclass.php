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
 * Forum Test Library
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

require_once(dirname(__FILE__).'/../../../config.php');
global $MBL;
require_once($MBL->mblroot.'/mod/forum/forum.class.php');

class forumclass_test extends UnitTestCase {

    public function test_forum_class() {
        global $MBL;

        $forum = new StdClass();
        $forum->id = 1;
        $forum->course = 2;
        $forum->type = 'news';
        $forum->name = 'Forum name';
        $forum->intro = 'Forum intro';
        $forum->introformat = 0;
        $forum->assessed = 0;
        $forum->assesstimestart = 0;
        $forum->assesstimefinish = 0;
        $forum->scale = 0;
        $forum->maxbytes = 0;
        $forum->maxattachments = 1;
        $forum->forcesubscribe = 1;
        $forum->trackingtype = 1;
        $forum->rsstype = 0;
        $forum->rssarticles = 0;
        $forum->timemodified = 1306943096;
        $forum->warnafter = 0;
        $forum->blockafter = 0;
        $forum->blockperiod = 0;
        $forum->completiondiscussions = 0;
        $forum->completionreplies = 0;
        $forum->completionposts = 0;

        $newforum = new Forum($forum);
        $data = $newforum->get_data();
        $struct = Forum::get_class_structure();

        $this->assertEqual(sizeof($struct->keys),sizeof($data), 'Same size');

        foreach ($struct->keys as $key => $value){
            $this->assertEqual($forum->$key, $data[$key], 'Same '.$key.' field');
        }

    }

    public function test_forum_class_exception() {
        global $MBL;

        $forum = new StdClass();
        $forum->id = 1;
        $forum->course = 2;
        $forum->type = 'news';
        $forum->name = 'Forum name';
        $forum->intro = 'Forum intro';
        $forum->introformat = 0;
        $forum->assessed = 0;
        $forum->assesstimestart = 0;
        $forum->assesstimefinish = 0;
        $forum->scale = 0;
        $forum->maxbytes = 0;
        $forum->maxattachments = 1;
        $forum->forcesubscribe = 1;
        $forum->trackingtype = 1;
        $forum->rsstype = 0;
        $forum->rssarticles = 0;
        $forum->timemodified = 1306943096;
        $forum->warnafter = 0;
        $forum->blockafter = 0;
        $forum->blockperiod = 0;
        $forum->completiondiscussions = 0;
        $forum->completionreplies = 0;
        $forum->completionposts = 0;

        unset($forum->id); // Incomplete record

        $this->expectException('Exception');
        $newforum = new Forum($forum);
    }

}
