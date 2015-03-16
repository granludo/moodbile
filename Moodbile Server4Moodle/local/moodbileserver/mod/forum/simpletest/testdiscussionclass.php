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
 * Forum Discussion Test Library
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
require_once($MBL->mblroot.'/mod/forum/discussion.class.php');

class discussionclass_test extends UnitTestCase {

    public function test_discussion_class() {
        global $MBL;

        $discussion = new StdClass();
        $discussion->id = 1;
        $discussion->course = 2;
        $discussion->forum = 1;
        $discussion->name = 'test discussion';
        $discussion->firstpost = 0;
        $discussion->userid = 2;
        $discussion->groupid = 0;
        $discussion->assessed = 1;
        $discussion->timemodified = 1306943096;
        $discussion->usermodified = 0;
        $discussion->timestart = 1306943098;
        $discussion->timeend = 1306943099;

        $newdiscussion = new Discussion($discussion);
        $data = $newdiscussion->get_data();
        $struct = Discussion::get_class_structure();

        $this->assertEqual(sizeof($struct->keys),sizeof($data), 'Same size');

        foreach ($struct->keys as $key => $value){
            $this->assertEqual($discussion->$key, $data[$key], 'Same '.$key.' field');
        }

    }

    public function test_forum_class_exception() {
        global $MBL;

        $discussion = new StdClass();
        $discussion->id = 1;
        $discussion->course = 2;
        $discussion->forum = 1;
        $discussion->name = 'test discussion';
        $discussion->firstpost = 0;
        $discussion->userid = 2;
        $discussion->groupid = 0;
        $discussion->assessed = 1;
        $discussion->timemodified = 1306943096;
        $discussion->usermodified = 0;
        $discussion->timestart = 1306943098;
        $discussion->timeend = 1306943099;

        unset($discussion->id); // Incomplete record

        $this->expectException('Exception');
        $newdiscussion = new Discussion($discussion);
    }

}
