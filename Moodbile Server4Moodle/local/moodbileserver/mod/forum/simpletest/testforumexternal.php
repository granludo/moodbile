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
 * Forum External API Test
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
require_once($MBL->mblroot.'/mod/forum/externallib.php');

Mock::generatePartial(get_class($MBL->DB), 'forumMockDB', array('get_record', 'get_record_sql', 'get_records_sql'));

class forumexternal_test extends UnitTestCase {

    public $realDB;

    function setUp() {
        global $MBL;

        $this->realDB = $MBL->DB;
        $MBL->DB = new forumMockDB();
    }

    function tearDown() {
        global $MBL;
        $MBL->DB = $this->realDB;
    }

    public function test_get_forum_by_id() {
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

        $MBL->DB->setReturnValueAt(0, 'get_record_sql', $forum);

        $params['forumid'] = 1;
        $result = moodbileserver_forum_external::get_forum_by_id($params);

        $this->assertEqual(sizeof($result), 1, "Correct number of results");

        $struct = Forum::get_class_structure();

        $this->assertEqual(sizeof($struct->keys),sizeof($result[0]), 'Same size');

        foreach ($struct->keys as $key => $value){
            $this->assertEqual($forum->$key, $result[0][$key], 'Same '.$key.' field');
        }
    }

    public function test_get_forums_by_courseid() {
        global $MBL;

        $forum = new StdClass();
        $forum->course = 2;
        $forum->type = 'news';
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

        $mockforums = array();
        for ($i = 0; $i <= 4; $i++) {
            $forum->id = $i;
            $forum->name = "Forum number ".$i;
            $mockforums[$i] = clone($forum);
        }

        $MBL->DB->setReturnValueAt(0, 'get_records_sql', $mockforums);

        $params = array();
        $params['courseid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 5;
        $forums = moodbileserver_forum_external::get_forums_by_courseid($params);

        $this->assertEqual(sizeof($forums), 5, "Same number of results: ".sizeof($forums));

        $struct = Forum::get_class_structure();

        for ($i = 0; $i <= 4; $i++) {
            $this->assertEqual(sizeof($struct->keys),sizeof($forums[$i]), 'Same size: '.sizeof($struct->keys).' - '.sizeof($forums[$i]).' ');

            foreach ($struct->keys as $key => $value){
                $this->assertEqual($mockforums[$i]->$key, $forums[$i][$key], 'Same '.$key.' field');
            }
        }

        $mockforums = array();
        for ($i = 0; $i <= 2; $i++) {
            $forum->id = $i;
            $forum->idnumber = "id".$i;
            $mockforums[$i] = clone($forum);
        }

        $MBL->DB->setReturnValueAt(1, 'get_records_sql', $mockforums);
        $forums = moodbileserver_forum_external::get_forums_by_courseid($params);

        $this->assertEqual(sizeof($forums), 3, "Same number of results");

        for ($i = 0; $i <= 2; $i++) {
            $this->assertEqual(sizeof($struct->keys),sizeof($forums[$i]), 'Same size');

            foreach ($struct->keys as $key => $value){
                $this->assertEqual($mockforums[$i]->$key, $forums[$i][$key], 'Same '.$key.' field');
            }
        }
    }


    public function test_get_forums_by_userid() {
        global $MBL;

        $forum = new StdClass();
        $forum->course = 2;
        $forum->type = 'news';
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

        $mockforums = array();
        for ($i = 0; $i <= 4; $i++) {
            $forum->id = $i;
            $forum->name = "Forum number ".$i;
            $mockforums[$i] = clone($forum);
        }

        $MBL->DB->setReturnValueAt(0, 'get_records_sql', $mockforums);

        $params = array();
        $params['userid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 5;
        $forums = moodbileserver_forum_external::get_forums_by_userid($params);

        $this->assertEqual(sizeof($forums), 5, "Same number of results: ".sizeof($forums));

        $struct = Forum::get_class_structure();

        for ($i = 0; $i <= 4; $i++) {
            $this->assertEqual(sizeof($struct->keys),sizeof($forums[$i]), 'Same size: '.sizeof($struct->keys).' - '.sizeof($forums[$i]).' ');

            foreach ($struct->keys as $key => $value){
                $this->assertEqual($mockforums[$i]->$key, $forums[$i][$key], 'Same '.$key.' field');
            }
        }

        $mockforums = array();
        for ($i = 0; $i <= 2; $i++) {
            $forum->id = $i;
            $forum->idnumber = "id".$i;
            $mockforums[$i] = clone($forum);
        }

        $MBL->DB->setReturnValueAt(1, 'get_records_sql', $mockforums);
        $forums = moodbileserver_forum_external::get_forums_by_userid($params);

        $this->assertEqual(sizeof($forums), 3, "Same number of results");

        for ($i = 0; $i <= 2; $i++) {
            $this->assertEqual(sizeof($struct->keys),sizeof($forums[$i]), 'Same size');

            foreach ($struct->keys as $key => $value){
                $this->assertEqual($mockforums[$i]->$key, $forums[$i][$key], 'Same '.$key.' field');
            }
        }
    }



    public function test_get_forum_discussions() {
        global $MBL;

        $discussion = new StdClass();
        $discussion->course = 2;
        $discussion->forum = 1;
        $discussion->firstpost = 1;
        $discussion->userid = 2;
        $discussion->groupid = 0;
        $discussion->assessed = 0;
        $discussion->timemodified = 2334455667;
        $discussion->usermodified = 0;
        $discussion->timestart = 0;
        $discussion->timeend = 0;

        $mockdiscussions = array();
        for ($i = 0; $i <= 4; $i++) {
            $discussion->id = $i;
            $discussion->name = "Discussion number ".$i;
            $mockdiscussions[$i] = clone($discussion);
        }

        $MBL->DB->setReturnValueAt(0, 'get_records_sql', $mockdiscussions);

        $params = array();
        $params['forumid'] = 1;
        $params['startpage'] = 0;
        $params['n'] = 5;
        $discussions = moodbileserver_forum_external::get_forum_discussions($params);

        $this->assertEqual(sizeof($discussions), 5, "Same number of results: ".sizeof($discussions));
    }


    public function test_get_discussion_by_id() {
        global $MBL;

        $forum = new StdClass();
        $forum->id = 1;
        $forum->name = "Forum number 1";
        $forum->course = 2;
        $forum->type = 'news';
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

        $discussion = new StdClass();
        $discussion->id = 1;
        $discussion->name = 'Disc name';
        $discussion->course = 2;
        $discussion->forum = 1;
        $discussion->firstpost = 1;
        $discussion->userid = 2;
        $discussion->groupid = 0;
        $discussion->assessed = 0;
        $discussion->timemodified = 2334455667;
        $discussion->usermodified = 0;
        $discussion->timestart = 0;
        $discussion->timeend = 0;

        $MBL->DB->setReturnValueAt(0, 'get_record', $discussion);
        $MBL->DB->setReturnValueAt(1, 'get_record', $forum);

        $MBL->DB->setReturnValueAt(0, 'get_record_sql', $discussion);

        $params['discid'] = 1;
        $result = moodbileserver_forum_external::get_discussion_by_id($params);

        $this->assertEqual(sizeof($result), 1, "Correct number of results");

        $struct = Discussion::get_class_structure();

        $this->assertEqual(sizeof($struct->keys),sizeof($result[0]), 'Same size');

        foreach ($struct->keys as $key => $value){
            $this->assertEqual($discussion->$key, $result[0][$key], 'Same '.$key.' field');
        }
    }
}
