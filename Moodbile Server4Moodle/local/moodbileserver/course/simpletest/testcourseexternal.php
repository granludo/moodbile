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
 * Course External Function Tests
 *
 * @package MoodbileServer
 * @subpackage Course
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

require_once(dirname(__FILE__).'/../../config.php');
global $MBL;
require_once($MBL->mblroot.'/course/externallib.php');

Mock::generatePartial(get_class($MBL->DB), 'courseMockDB', array('get_records_sql'));

class courseexternal_test extends UnitTestCase {

    public $realDB;

    function setUp() {
        global $MBL;

        $this->realDB = $MBL->DB;
        $MBL->DB = new courseMockDB();
    }

    function tearDown() {
        global $MBL;
        $MBL->DB = $this->realDB;
    }

    public function test_get_courses_by_userid_exception() {
        $params = array();
        $params['uid'] = 2; // Wrong parameter
        $params['startpage'] = 0;
        $params['n'] = 5;
        $this->expectException('Exception');
        $courses = moodbileserver_course_external::get_courses_by_userid($params);
    }

    public function test_get_courses_by_userid() {
        global $MBL;

        $course = new StdClass();
        $course->category = 1;
        $course->sortorder = 10001;
        $course->fullname = "fullname";
        $course->shortname = "shortname";
        $course->idnumber = "";
        $course->summary = "summary";
        $course->summaryformat = 1;
        $course->format = "weeks";
        $course->showgrades = 1;
        $course->startdate = 1295910000;
        $course->numsections = 10;
        $course->marker = 0;
        $course->maxbytes = 33554432;
        $course->legacyfiles = 0;
        $course->showreports = 0;
        $course->visible = 1;
        $course->groupmode = 0;
        $course->groupmodeforce = 0;
        $course->defaultgroupingid = 0;
        $course->lang = "";
        $course->timecreated = 1295867118;
        $course->timemodified = 1295867118;

        $mockcourses = array();
        for ($i = 0; $i <= 4; $i++) {
            $course->id = $i;
            $course->idnumber = "id".$i;
            $mockcourses[$i] = clone($course);
        }

        $MBL->DB->setReturnValueAt(0, 'get_records_sql', $mockcourses);

        $params = array();
        $params['userid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 5;
        $courses = moodbileserver_course_external::get_courses_by_userid($params);

        $this->assertEqual(sizeof($courses), 5, "Same number of results");

        $struct = Course::get_class_structure();

        for ($i = 0; $i <= 4; $i++) {
            $this->assertEqual(sizeof($struct->keys),sizeof($courses[$i]), 'Same size');

            foreach ($struct->keys as $key => $value){
                $this->assertEqual($mockcourses[$i]->$key, $courses[$i][$key], 'Same '.$key.' field');
            }
        }

        $mockcourses = array();
        for ($i = 0; $i <= 2; $i++) {
            $course->id = $i;
            $course->idnumber = "id".$i;
            $mockcourses[$i] = clone($course);
        }

        $MBL->DB->setReturnValueAt(1, 'get_records_sql', $mockcourses);
        $courses = moodbileserver_course_external::get_courses_by_userid($params);

        $this->assertEqual(sizeof($courses), 3, "Same number of results");

        for ($i = 0; $i <= 2; $i++) {
            $this->assertEqual(sizeof($struct->keys),sizeof($courses[$i]), 'Same size');

            foreach ($struct->keys as $key => $value){
                $this->assertEqual($mockcourses[$i]->$key, $courses[$i][$key], 'Same '.$key.' field');
            }
        }
    }
}
