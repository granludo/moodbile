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
 * Group External Funtions' Tests
 *
 * @package MoodbileServer
 * @subpackage Group
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

if(!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); ///  It must be included from a Moodle page
}

require_once(dirname(__FILE__) . '/../../config.php');

global $MBL;

require_once($MBL->mblroot . '/group/grouping.class.php');
require_once($MBL->mblroot . '/group/group.class.php');
require_once($MBL->mblroot . '/user/user.class.php');
require_once($MBL->mblroot . '/group/externallib.php');

Mock :: generatePartial(get_class($MBL->DB), 'groupMockDB', array('get_record', 'get_records_sql' , 'get_record_sql'));

global $DB;
Mock :: generatePartial(get_class($DB), 'groupMockDBglob', array('get_record'));

class groupsexternal_test extends UnitTestCase {

    public $realDB;

    function setUp() {
        global $MBL;

        $this->realDB = $MBL->DB;
        $MBL->DB = new groupMockDB();

        global $DB;
        $this->realDB = $DB;
        $DB = new groupMockDBglob();
    }

    function tearDown() {
        global $MBL;
        $MBL->DB = $this->realDB;

        global $DB;
        $DB = $this->realDB;
    }

    public function test_get_group_by_groupid_exception() {
        $params = array('fail', 2);
        $this->expectException();
        $group = moodbileserver_group_external :: get_group_by_groupid($params);
    }

    public function test_get_group_by_groupid() {
        global $MBL;
        global $DB;

        $group_t = new StdClass();
        $group_t = (object) array('id'=>1,'courseid'=>2,'name'=>'aaa','description'=>'test aaa',
                                 'descriptionformat'=>1,'enrolmentkey'=>'','picture'=>0,'hidepicture'=>0,
                                 'timecreated'=>1307100305,'timemodified'=>1307533377);

        $course = new stdClass();
        $course = (object) array('id'=>2,'category'=>1,'sortorder'=>10002,'fullname'=>'course test',
                                'shortname'=>'DOCS','idnumber'=>'','summary'=>'testcourse','summaryformat'=>1,
                                'format'=>'weeks','showgrades'=>1,'modinfo'=>'a:1:{i:1;O:8:\"stdClass\":10:{s:2:'.
                                '\"id\";s:1:\"1\";s:2:\"cm\";s:1:\"1\";s:3:\"mod\";s:5:\"forum\";s:7:\"section\";'.
                                's:1:\"0\";s:9:\"sectionid\";s:1:\"1\";s:6:\"module\";s:1:\"7\";s:5:\"added\";'.
                                's:10:\"1306259166\";s:7:\"visible\";s:1:\"1\";s:10:\"visibleold\";s:1:\"1\";s:4:'.
                                '\"name\";s:10:\"News forum\";}}','newsitems'=>5,'startdate'=>1306274400,
                                'numsections'=>10,'marker'=>0,'maxbytes'=>20971520,'legacyfiles'=>0,'showreports'=>0,
                                'visible'=>1,'visibleold'=>1,'hiddensections'=>0,'groupmode'=>2,'groupmodeforce'=>0,
                                'defaultgroupingid'=>0,'lang'=>'','theme'=>'','timecreated'=>1306259151,
                                'timemodified'=>1307624411,'requested'=>0,'restrictmodules'=>0,'enablecompletion'=>0,
                                'completionstartonenrol'=>0,'completionnotify'=>0);
        //There seems to be a lot of problems if we set id = 0
        $mockgroups = array();
        for($i = 0; $i <= 4; $i++) {
            $group_t->id = $i+1;
            $mockgroups[$i] = clone($group_t);
        }

        $mockcourses = array();
        for($i = 0; $i <= 4; $i++) {
            $course->id = 1;
            $mockcourses[$i] = clone($course);
        }

        for($i = 0; $i < 8; $i++) {
            $MBL->DB->setReturnValueAt($i, 'get_record_sql', $mockcourses[$i % 5]);
        }

        $struct = Group :: get_class_structure();

        for($i = 0; $i < 8; $i++) {
            $DB->setReturnValueAt($i, 'get_record', $mockcourses[$i % 5]);
            $MBL->DB->setReturnValueAt($i, 'get_record', $mockgroups[$i % 5]);
        }

        for($i = 0; $i <= 4; $i++) {
            $group = moodbileserver_group_external :: get_group_by_groupid( $i+1 );
            $this->assertEqual(sizeof($struct->keys), sizeof((array) $group), 'Same size');

            foreach($struct->keys as $key => $value) {
                $this->assertEqual($mockgroups[$i]-> $key, $group[$key], 'Same ' . $key . ' field');
            }
        }

        $mockgroups = array();
        for($i = 0; $i <= 2; $i++) {
            $group_t->id = $i+1;
            $mockgroups[$i] = clone($group_t);
        }

        for($i = 0; $i <= 2; $i++) {
            $group = moodbileserver_group_external :: get_group_by_groupid( $i+1 );
            $this->assertEqual(sizeof($struct->keys), sizeof((array) $group), 'Same size');

            foreach($struct->keys as $key => $value) {
                $this->assertEqual($mockgroups[$i]-> $key, $group[$key], 'Same ' . $key . ' field');
            }
        }
    }

    /*public function test_get_group_members_by_groupid_exception() {
        $params = array('11group', 2);
        $this->expectException();
        $group = moodbileserver_group_external :: get_group_members_by_groupid($params);
    }*/

    public function test_get_group_members_by_groupid() {
        $functionname = 'get_group_members_by_groupid';
        $array_to_object = array('id' => 3, 'auth' => 'webservice', 'confirmed' => 1, 'policyagreed' => 0,
                                'deleted' => 0, 'suspended' => 0, 'mnethostid' => 1, 'username' => 'webservice',
                                'password' => '4d186321c1a7f0f354b297e8914ab240', 'idnumber' => '',
                                'firstname' => 'webservice', 'lastname' => 'webservice', 'email' => 'test@test.com',
                                'emailstop' => 0, 'icq' => '', 'skype' => '', 'yahoo' => '', 'aim' => '',
                                'msn' => '', 'phone1' => '', 'phone2' => '', 'institution' => '',
                                'department' => '', 'address' => '', 'city' => 'Barcelona', 'country' => 'ES',
                                'lang' => 'ca', 'theme' => '', 'timezone' => 99, 'firstaccess' => 0,
                                'lastaccess' => 0, 'lastlogin' => 0, 'currentlogin' => 0, 'lastip' => '',
                                'secret' => '', 'picture' => 0, 'url' => '', 'description' => '',
                                'descriptionformat' => 1, 'mailformat' => 1, 'maildigest' => 0,
                                'maildisplay' => 2, 'htmleditor' => 1, 'ajax' => 0, 'autosubscribe' => 1,
                                'trackforums' => 0, 'timecreated' => 1306422059, 'timemodified' => 1306422059,
                                'trustbitmask' => 0, 'imagealt' => '', 'screenreader' => 0);
        $params = array();
        $params['groupid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 5;
        $class = 'User';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    /*public function test_get_group_members_by_groupingid_exception() {
        $params = array('fail', 2);
        $params['aa'] = 'asd';
        $params['ae'] = 'eeed';
        $this->expectException();
        call_user_func_array(array('moodbileserver_group_external', 'get_group_members_by_groupingid'), $params);
    }*/

    public function test_get_group_members_by_groupingid() {
        $functionname = 'get_group_members_by_groupingid';
        $array_to_object = array('id' => 3, 'auth' => 'webservice', 'confirmed' => 1, 'policyagreed' => 0,
                                'deleted' => 0, 'suspended' => 0, 'mnethostid' => 1, 'username' => 'webservice',
                                'password' => '4d186321c1a7f0f354b297e8914ab240', 'idnumber' => '',
                                'firstname' => 'webservice', 'lastname' => 'webservice', 'email' => 'test@test.com',
                                'emailstop' => 0, 'icq' => '', 'skype' => '', 'yahoo' => '', 'aim' => '',
                                'msn' => '', 'phone1' => '', 'phone2' => '', 'institution' => '',
                                'department' => '', 'address' => '', 'city' => 'Barcelona', 'country' => 'ES',
                                'lang' => 'ca', 'theme' => '', 'timezone' => 99, 'firstaccess' => 0,
                                'lastaccess' => 0, 'lastlogin' => 0, 'currentlogin' => 0, 'lastip' => '',
                                'secret' => '', 'picture' => 0, 'url' => '', 'description' => '',
                                'descriptionformat' => 1, 'mailformat' => 1, 'maildigest' => 0,
                                'maildisplay' => 2, 'htmleditor' => 1, 'ajax' => 0, 'autosubscribe' => 1,
                                'trackforums' => 0, 'timecreated' => 1306422059, 'timemodified' => 1306422059,
                                'trustbitmask' => 0, 'imagealt' => '', 'screenreader' => 0);
        $params = array();
        $params['groupingid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 5;
        $class = 'User';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    /*public function test_get_groups_by_courseid_exception() {
        $params = array('fail', 2);
        $this->expectException();
        $group = moodbileserver_group_external :: get_groups_by_courseid($params);
    }*/

    public function test_get_groups_by_courseid() {
        $functionname = 'get_groups_by_courseid';
        $array_to_object = array('id' => 1, 'courseid' => 2, 'name' => 'aaa', 'description' => 'test aaa',
                                'descriptionformat' => 1, 'enrolmentkey' => '', 'picture' => 0,
                                'hidepicture' => 0, 'timecreated' => 1307100305, 'timemodified' => 1307100387);
        $params = array('courseid' =>2, 'startpage' => 0, 'n' => 10);
        $class = 'Group';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    /*public function test_get_groups_by_courseid_and_userid_exception() {
        $params = array('fail', 2);
        $params['userid'] = 3;
        $this->expectException();
        $group = moodbileserver_group_external :: get_groups_by_courseid_and_userid($params);
    }*/

    public function test_get_groups_by_courseid_and_userid() {
        $functionname = 'get_groups_by_courseid_and_userid';
        $array_to_object = array('id' => 1, 'courseid' => 2, 'name' => 'aaa', 'description' => 'test aaa',
                            'descriptionformat' => 1, 'enrolmentkey' => '', 'picture' => 0,
                            'hidepicture' => 0, 'timecreated' => 1307100305, 'timemodified' => 1307100387);
        $params = array();
        $params['courseid'] = 2;
        $params['userid'] = 1;
        $params['startpage'] = 0;
        $params['n'] = 10;
        $class = 'Group';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    /*public function test_get_groupings_by_courseid_exception() {
        $params = array('fail', 2);
        $this->expectException();
        $group = moodbileserver_group_external :: get_groupings_by_courseid($params);
    }*/

    /*public function test_get_groupings_by_courseid() {
        $functionname = 'get_groupings_by_courseid';
        $array_to_object = array('id' => 1, 'courseid' => 2, 'name' => 'agrupament a',
                                'description' => '<p>asdasdsad</p>', 'descriptionformat' => 1,
                                'configdata' => null, 'timecreated' => 1307529334, 'timemodified' => 1307529334);
        $params = array();
        $params['courseid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 10;
        $class = 'Grouping';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }*/

    /*public function test_get_groupings_by_courseid_and_userid_exception() {
        $params = array('fail', 2);
        $params['userid'] = 3;
        $this->expectException();
        $group = moodbileserver_group_external :: get_groupings_by_courseid_and_userid($params);
    }*/

    public function test_get_groupings_by_courseid_and_userid() {
        $functionname = 'get_groupings_by_courseid_and_userid';
        $array_to_object = array('id' => 1, 'courseid' => 2, 'name' => 'agrupament a',
                                'description' => '<p>asdasdsad</p>', 'descriptionformat' => 1,
                                'configdata' => null, 'timecreated' => 1307529334, 'timemodified' => 1307529334);
        $params = array();
        $params['courseid'] = 2;
        $params['userid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 10;
        $class = 'Grouping';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    /*public function test_get_groups_by_groupingid_exception() {
        $params = array('fail', 2);
        $params['roupingid'] = 3;
        $this->expectException();
        $group = moodbileserver_group_external :: get_groups_by_groupingid($params);
    }*/

    public function test_get_groups_by_groupingid() {
        $functionname = 'get_groups_by_groupingid';
        $array_to_object = array('id' => 1, 'courseid' => 1, 'name' => 'aaa', 'description' => 'test aaa',
                                'descriptionformat' => 1, 'enrolmentkey' => '', 'picture' => 0,
                                'hidepicture' => 0, 'timecreated' => 1307100305, 'timemodified' => 1307100387);
        $params = array();
        $params['groupingid'] = 2;
        $params['startpage'] = 0;
        $params['n'] = 10;
        $class = 'Group';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    private function _tester_multiple($functionname, $params, $array_to_object, $class, $fix=false) {
        global $MBL;
        global $DB;

        $object = new StdClass();
        $object = (object) $array_to_object;

        $course = new stdClass();
        $course = (object) array('id'=>2,'category'=>1,'sortorder'=>10002,'fullname'=>'course test',
                                'shortname'=>'DOCS','idnumber'=>'','summary'=>'testcourse','summaryformat'=>1,
                                'format'=>'weeks','showgrades'=>1,'modinfo'=>'a:1:{i:1;O:8:\"stdClass\":10:{s:2:'.
                                '\"id\";s:1:\"1\";s:2:\"cm\";s:1:\"1\";s:3:\"mod\";s:5:\"forum\";s:7:\"section\";'.
                                's:1:\"0\";s:9:\"sectionid\";s:1:\"1\";s:6:\"module\";s:1:\"7\";s:5:\"added\";'.
                                's:10:\"1306259166\";s:7:\"visible\";s:1:\"1\";s:10:\"visibleold\";s:1:\"1\";s:4:'.
                                '\"name\";s:10:\"News forum\";}}','newsitems'=>5,'startdate'=>1306274400,
                                'numsections'=>10,'marker'=>0,'maxbytes'=>20971520,'legacyfiles'=>0,'showreports'=>0,
                                'visible'=>1,'visibleold'=>1,'hiddensections'=>0,'groupmode'=>2,'groupmodeforce'=>0,
                                'defaultgroupingid'=>0,'lang'=>'','theme'=>'','timecreated'=>1306259151,
                                'timemodified'=>1307624411,'requested'=>0,'restrictmodules'=>0,'enablecompletion'=>0,
                                'completionstartonenrol'=>0,'completionnotify'=>0);

        $mockobjects = array();
        for($i = 0; $i <= 4; $i++) {
            $object->id = $i+1;
            $mockobjects[$i] = clone($object);
        }

        $mockcourses = array();
        for($i = 0; $i <= 4; $i++) {
            $course->id = $i+1;
            $mockcourses[$i] = clone($course);
        }

        $MBL->DB->setReturnValueAt(0, 'get_records_sql', $mockobjects);
        if ($fix) {
            $MBL->DB->setReturnValueAt(1, 'get_records_sql', $mockobjects);
        }

        for($i = 0; $i < 8; $i++) {
            $DB->setReturnValueAt($i, 'get_record', $mockcourses[$i % 5]);
        }

        for($i = 0; $i < 8; $i++) {
            $MBL->DB->setReturnValueAt($i, 'get_record', $mockcourses[$i % 5]);
        }

        for($i = 0; $i < 8; $i++) {
            $MBL->DB->setReturnValueAt($i, 'get_record_sql', $mockcourses[$i % 5]);
        }

//        $objects = moodbileserver_group_external :: $functionname($params);
        if (count($params) == 1) {
            $objects = call_user_func(array('moodbileserver_group_external', $functionname), $params);
        }
        $objects = call_user_func_array(array('moodbileserver_group_external', $functionname), $params);

        $this->assertEqual(count($objects), 5, "Same number of results");

        //$struct = $class :: get_class_structure();//only for php >=5.3.0
        $struct = call_user_func(array($class, 'get_class_structure'));

        for($i = 0; $i <= 4; $i++) {
            $this->assertEqual(sizeof($struct->keys), sizeof($objects[$i]), 'Same size');

            foreach($struct->keys as $key => $value) {
                $this->assertEqual($mockobjects[$i]-> $key, $objects[$i][$key], 'Same ' . $key . ' field');
            }
        }

        $mockobjects = array();
        for($i = 0; $i <= 2; $i++) {
            $object->id = $i+1;
            $mockobjects[$i] = clone($object);
        }

        $MBL->DB->setReturnValueAt(1, 'get_records_sql', $mockobjects);
        if ($fix) {
            $MBL->DB->setReturnValueAt(2, 'get_records_sql', $mockobjects);
            $MBL->DB->setReturnValueAt(3, 'get_records_sql', $mockobjects);
        }
        //$objects = moodbileserver_group_external :: $functionname($params);
        if (count($params) == 1) {
            $objects = call_user_func(array('moodbileserver_group_external', $functionname), $params);
        }
        $objects = call_user_func_array(array('moodbileserver_group_external', $functionname), $params);
        $this->assertEqual(sizeof($objects), 3, "Same number of results");

        for($i = 0; $i <= 2; $i++) {
            $this->assertEqual(sizeof($struct->keys), sizeof($objects[$i]), 'Same size');

            foreach($struct->keys as $key => $value) {
                $this->assertEqual($mockobjects[$i]-> $key, $objects[$i][$key], 'Same ' . $key . ' field');
            }
        }
    }
}