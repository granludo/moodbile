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
 * Calendar External Function's Test Library
 *
 * @package MoodbileServer
 * @subpackage Calendar
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

require_once($MBL->mblroot . '/calendar/event.class.php');
require_once($MBL->mblroot . '/calendar/externallib.php');

Mock :: generatePartial(get_class($MBL->DB), 'calendarMockDB', array('get_record', 'get_record_sql', 'get_records_sql', 'insert_record',  'set_field', 'delete_records', 'get_records', 'execute','update_record'));

global $DB;
Mock :: generatePartial(get_class($DB), 'calendarMockDBglob', array('get_record', 'get_records_sql', 'get_records_select', 'insert_record', 'insert_record_raw'));

class calendarexternal_test extends UnitTestCase {

    public $realDB;

  function setUp() {
        global $MBL;

        $this->realDB = $MBL->DB;
        $MBL->DB = new calendarMockDB();

        global $DB;
        $this->realDB = $DB;
        $DB = new calendarMockDBglob();
    }

    function tearDown() {
        global $MBL;
        $MBL->DB = $this->realDB;

        global $DB;
        $DB = $this->realDB;
    }

    public function test_get_events() {
        $functionname = 'get_events';
        $array_to_object = array('id' => 1, 'name' => 'test site event', 'description' => '<p>wkfmwkfmwelfkmwfw</p>
<p>weflmwfl</p>
<p>l,weflwefwefwefweflllll</p>', 'format' => 1, 'courseid' => 1, 'groupid' => 0, 'userid' => 2, 'repeatid' => 0, 'modulename' => 0, 'instance' => 0, 'eventtype' => 'site', 'timestart' => 1308780000, 'timeduration' => 0, 'visible' => 1, 'uuid' => '', 'sequence' => 1, 'timemodified' => 1308847563);
        $params = array();
        $params['userid'] = 2;
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['timestart'] = 0;
        $params['timeend'] = 900000;
        $class = 'Event';
        $this->_tester_multiple($functionname, $params, $array_to_object, $class);
    }

    //This test doesn't test the actual functionality,
    //we could say that it just tests that it "compiles"
    public function test_create_event() {
        global $MBL;
        global $DB;
        //"normal" event
        $params = array();
        $params['name'] = "testname";
        $params['description'] = "testdescription";
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['userid'] = 2;
        $params['numrepetitions'] = 1;
        $params['eventtype'] = "group";
        $params['timestart'] = 1308780000;
        $params['timeduration'] = 0;


        $context = (object) array('id'=>3,'contextlevel'=>40,'instanceid'=>1,'path'=>'/1/3','depth'=>2);
        $DB->setReturnValueAt(0, 'get_record', $context);
        $DB->setReturnValueAt(0, 'insert_record_raw', true);

        $MBL->DB->setReturnValueAt(0, 'insert_record', true);

        $return = call_user_func_array(array('moodbileserver_calendar_external', 'create_event'), $params);

        $this->assertEqual($return, true);
    }

    public function test_create_event_with_repetitions() {
        global $MBL;
        global $DB;
        //event with repetitions
        $params = array();
        $params['name'] = "testname";
        $params['description'] = "testdescription";
        $params['userid'] = 2;
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['numrepetitions'] = 5;
        $params['eventtype'] = "group";
        $params['timestart'] = 1308780000;
        $params['timeduration'] = 0;

        $context = (object) array('id'=>3,'contextlevel'=>40,'instanceid'=>1,'path'=>'/1/3','depth'=>2);
        $DB->setReturnValueAt(0, 'get_record', $context);

        $max = $params['numrepetitions'];
        for ($i=0; $i < $max; $i++) {
          $MBL->DB->setReturnValueAt($i, 'insert_record', true);
          $MBL->DB->setReturnValueAt($i, 'set_field', true);
        }

        $return = call_user_func_array(array('moodbileserver_calendar_external', 'create_event'), $params);

        $this->assertEqual($return, true);
    }

    public function test_create_event_exception1() {
        $params = array();
        $params['name'] = "testname";
        $params['description'] = "testdescription";
        $params['userid'] = 2;
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['numrepetitions'] = 1;
        $params['eventtype'] = "groupjwnfjwf";//event type doesn't exist
        $params['timestart'] = 1308780000;
        $params['timeduration'] = 0;

        $this->expectException();
        $return = call_user_func_array(array('moodbileserver_calendar_external', 'create_event'), $params);
    }

   /* public function test_create_event_exception2() {
        $params = array();
        $params['name'] = 8867867867;
        $params['description'] = "testdescription";
        $params['userid'] = 2;
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['numrepetitions'] = -1;
        $params['eventtype'] = "group";
        $params['timestart'] = "aaa";
        $params['timeduration'] = 0;

        $this->expectException();
        $return = call_user_func_array(array('moodbileserver_calendar_external', 'create_event'), $params);
    }*/

    //This test doesn't test the actual functionality,
    //we could say that it just tests that it "compiles"
    public function test_delete_events() {
        global $MBL;

        $params = array();
        $params['id'] = 1;
        $params['deleterepeated'] = false;

        $event = (object) array('id' => 1, 'name' => 'test site event', 'description' => '<p>wkfmwkfmwelfkmwfw</p>
<p>weflmwfl</p>
<p>l,weflwefwefwefweflllll</p>', 'format' => 1, 'courseid' => 1, 'groupid' => 0, 'userid' => 2, 'repeatid' => 0, 'modulename' => 0, 'instance' => 0, 'eventtype' => 'site', 'timestart' => 1308780000, 'timeduration' => 0, 'visible' => 1, 'uuid' => '', 'sequence' => 1, 'timemodified' => 1308847563);

        $MBL->DB->setReturnValueAt(0, 'get_record', $event);
        $MBL->DB->setReturnValueAt(0, 'delete_records', true);

        $return = call_user_func_array(array('moodbileserver_calendar_external', 'delete_event'), $params);

        $this->assertEqual($return, true);
    }

    public function test_delete_events_exception() {
        $params = array();
        $params['id'] = 'pedro';
        $params['deleterepeated'] = false;
        $this->expectException( );
        $return = call_user_func_array(array('moodbileserver_calendar_external', 'delete_event'), $params);
    }

    public function test_delete_events_with_repetitions() {
        global $MBL;
        global $DB;

        $params = array();
        $params['id'] = 1;
        $params['deleterepeated'] = true;

        $context = (object) array('id'=>3,'contextlevel'=>40,'instanceid'=>1,'path'=>'/1/3','depth'=>2);

        for($i = 0; $i <= 4; $i++) {
            $DB->setReturnValueAt($i, 'get_record', $context);
        }

        $event = (object) array('id' => 1, 'name' => 'test site event', 'description' => '<p>wkfmwkfmwelfkmwfw</p>
<p>weflmwfl</p>
<p>l,weflwefwefwefweflllll</p>', 'format' => 1, 'courseid' => 1, 'groupid' => 0, 'userid' => 2, 'repeatid' => 0, 'modulename' => 0, 'instance' => 0, 'eventtype' => 'site', 'timestart' => 1308780000, 'timeduration' => 0, 'visible' => 1, 'uuid' => '', 'sequence' => 1, 'timemodified' => 1308847563);

        $mockobjects = array();
        for($i = 0; $i <= 5; $i++) {
            $event->id = $i+1;
            $mockobjects[$i] = clone($event);
        }

        for($i = 0; $i <= 5 ; $i++) {
            $MBL->DB->setReturnValueAt($i, 'get_record', $mockobjects[$i]);
        }
        unset($mockobjects[5]);
        $MBL->DB->setReturnValueAt(0, 'get_records', $mockobjects);

        for($i = 0; $i <= 4; $i++) {
            $MBL->DB->setReturnValueAt($i, 'delete_records', true);
        }

        $return = call_user_func_array(array('moodbileserver_calendar_external', 'delete_event'), $params);

        $this->assertEqual($return['success'], true);
    }

    public function test_export_events_to_ical_events() {
      global $MBL;
      global $DB;

      $params = array();
      $params['what'] = "all";
      $params['time'] = "weeknow";

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

      $group_t = new StdClass();
      $group_t = (object) array('id'=>1,'courseid'=>2,'name'=>'aaa','description'=>'test aaa',
                                 'descriptionformat'=>1,'enrolmentkey'=>'','picture'=>0,'hidepicture'=>0,
                                 'timecreated'=>1307100305,'timemodified'=>1307533377);

      $event = (object) array('id' => 1, 'name' => 'test site event', 'description' => '<p>wkfmwkfmwelfkmwfw</p>
<p>weflmwfl</p>
<p>l,weflwefwefwefweflllll</p>', 'format' => 1, 'courseid' => 1, 'groupid' => 0, 'userid' => 2, 'repeatid' => 7, 'modulename' => 0, 'instance' => 0, 'eventtype' => 'site', 'timestart' => 1308780000, 'timeduration' => 0, 'visible' => 1, 'uuid' => '', 'sequence' => 1, 'timemodified' => 1308847563);

        $mockobjects = array();
        for($i = 0; $i <= 5; $i++) {
            $event->id = $i+1;
            $mockobjects[$i] = clone($event);
        }

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

      $MBL->DB->setReturnValueAt(0, 'get_records_sql', $mockcourses);
      for($i = 0; $i <= 4; $i++) {
        $DB->setReturnValueAt($i, 'get_records_sql', $mockgroups);
      }

      $DB->setReturnValueAt(0, 'get_records_select', $mockobjects);//events
      $DB->setReturnValueAt(0, 'insert_record', true);//for inserting the file in the db

      //$url = moodbileserver_calendar_external::export_events_to_ical($params);
      $url = call_user_func_array(array('moodbileserver_calendar_external', 'export_events_to_ical'), $params);
      if (!empty($url)) {
        $this->assertEqual(true, true);
      }
      else {
        $this->assertEqual(true, false);
      }
    }

    public function test_update_event() {
        global $MBL;
        global $DB;

        $event = (object) array('id' => 1, 'name' => 'test site event', 'description' => '<p>wkfmwkfmwelfkmwfw</p>
<p>weflmwfl</p>
<p>l,weflwefwefwefweflllll</p>', 'format' => 1,
            'courseid' => 1, 'groupid' => 0, 'userid' => 2, 'repeatid' => 7, 'modulename' => 0, 'instance' => 0, 'eventtype' => 'site', 'timestart' => 1308780000, 'timeduration' => 0, 'visible' => 1, 'uuid' => '', 'sequence' => 1, 'timemodified' => 1308847563);

        $MBL->DB->setReturnValueAt(0, 'get_record', $event);
        $MBL->DB->setReturnValueAt(0, 'update_record', true);

        $params = array();
        $params['id'] = 1;
        $params['name'] = "newname";
        $params['description'] = "testnewdescription";
        $params['repeatid'] = 7;
        $params['userid'] = 2;
        $params['courseid'] = 1;
        $params['groupid'] = 0;
        $params['eventtype'] = "group";
        $params['timestart'] = 1308780000;
        $params['timeduration'] = 0;
        $params = array('event' => (object)$params, 'repeateditall'=> false);

        //$ret  = moodbileserver_calendar_external::update_event($params);
        $ret = call_user_func_array(array('moodbileserver_calendar_external', 'update_event'), $params);

        $this->assertEqual(true, $ret);
    }

    public function test_update_event_with_repetitions() {
        global $MBL;
        global $DB;

        $event = (object) array('id' => 1, 'name' => 'test site event', 'description' => '<p>wkfmwkfmwelfkmwfw</p>
<p>weflmwfl</p>
<p>l,weflwefwefwefweflllll</p>', 'format' => 1, 'courseid' => 1, 'groupid' => 0, 'userid' => 2, 'repeatid' => 7, 'modulename' => 0, 'instance' => 0, 'eventtype' => 'site', 'timestart' => 1308780000, 'timeduration' => 0, 'visible' => 1, 'uuid' => '', 'sequence' => 1, 'timemodified' => 1308847563);

        $MBL->DB->setReturnValueAt(0, 'get_record', $event);
        $MBL->DB->setReturnValueAt(0, 'execute', true);

        $params = array();
        $params['id'] = 1;
        $params['name'] = "newname";
        $params['description'] = "testnewdescription";
        $params['repeatid'] = 1;
        $params['userid'] = 2;
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['eventtype'] = "group";
        $params['timestart'] = 1308780000;
        $params['timeduration'] = 0;
        $params = array('event' => (object)$params, 'repeateditall'=> true);

        $ret = call_user_func_array(array('moodbileserver_calendar_external', 'update_event'), $params);

        $this->assertEqual(true, $ret);
    }

    /*public function test_update_event_exception() {
        $params = array();
        $params['name'] = "newname";
        $params['description'] = "testnewdescription";
        $params['repeatid'] = "-x";
        $params['userid'] = 2;
        $params['courseid'] = 0;
        $params['groupid'] = 5;
        $params['eventtype'] = "group";
        $params['timestart'] = 1308780000;
        $params['timeduration'] = 0;
        $params['repeateditall'] = true;

        $this->expectException( );
        $params = array('event' => (object)$params, 'repeateditall'=> true);
        $ret = call_user_func_array(array('moodbileserver_calendar_external', 'update_event'), $params);
    }*/

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
            $MBL->DB->setReturnValueAt($i, 'get_record_sql', $mockobjects[$i % 5]);
        }

        $objects = call_user_func_array(array('moodbileserver_calendar_external', $functionname), $params);

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
        //$objects = moodbileserver_calendar_external :: $functionname($params);
        $objects = call_user_func_array(array('moodbileserver_calendar_external', $functionname), $params);
        $this->assertEqual(sizeof($objects), 3, "Same number of results");

        for($i = 0; $i <= 2; $i++) {
            $this->assertEqual(sizeof($struct->keys), sizeof($objects[$i]), 'Same size');

            foreach($struct->keys as $key => $value) {
                $this->assertEqual($mockobjects[$i]-> $key, $objects[$i][$key], 'Same ' . $key . ' field');
            }
        }
    }
}