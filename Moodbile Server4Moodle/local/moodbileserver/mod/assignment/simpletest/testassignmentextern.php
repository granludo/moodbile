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
 * Assignment External Functions Tests
 *
 * @package MoodbileServer
 * @subpackage Assignment
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

require_once(dirname(__FILE__) . '/../../../config.php');

global $MBL;

require_once($MBL->mblroot . '/mod/assignment/assignment.class.php');
require_once($MBL->mblroot . '/mod/assignment/submission.class.php');
require_once($MBL->mblroot . '/mod/assignment/externallib.php');

Mock :: generatePartial(get_class($MBL->DB), 'assignmentMockDB', array('get_records',  'insert_record', 'update_record', 'get_record_sql','get_record'));

global $DB;
Mock :: generatePartial(get_class($DB), 'assignmentMockDBglob', array('get_record', 'get_records', 'get_records_sql', 'get_record_sql' , 'get_records_select', 'insert_record', 'insert_record_raw', 'set_field'));

class assignmentexternal_test extends UnitTestCase {


    public $realDB;
    public $realDB2;


  function setUp() {
        global $MBL;

        $this->realDB = $MBL->DB;
        $MBL->DB = new assignmentMockDB();

        global $DB;
        $this->realDB2 = $DB;
        $DB = new assignmentMockDBglob();
    }

    function tearDown() {
        global $MBL;
        $MBL->DB = $this->realDB;

        global $DB;
        $DB = $this->realDB2;
    }

    public function test_get_submission_by_assigid() {
        global $MBL;
        global $DB;

        $assig = array('id'=>'1','course'=>'5','name'=>'advanded uploading','intro'=>'<p>test</p>','introformat'=>'1','assignmenttype'=>'upload','resubmit'=>'1','preventlate'=>'0','emailteachers'=>'0','var1'=>'3','var2'=>'0','var3'=>'0','var4'=>'1','var5'=>'0','maxbytes'=>'1048576','timedue'=>'1312194300','timeavailable'=>'1311589500','grade'=>'100','timemodified'=>'1311589802');
        $submi = array('id'=>'11','assignment'=>'1','userid'=>'5','timecreated'=>'1316511925','timemodified'=>'1316511960','numfiles'=>'0','data1'=>'','data2'=>'submitted','grade'=>'-1','submissioncomment'=>'','format'=>'0','teacher'=>'0','timemarked'=>'0','mailed'=>'0');

        $MBL->DB->setReturnValueAt(0, 'get_record', (object) $assig);
        $MBL->DB->setReturnValueAt(1, 'get_record', (object) $submi);
        $DB->setReturnValueAt(0, 'get_record_sql', (object) array('id' => 1));
        $DB->setReturnValueAt(0, 'insert_record_raw', true);

        $params = 1;
        $return = moodbileserver_assignment_external :: get_submission_by_assigid($params);

        $expected = array ('id' => 11, 'assignment' => '1' ,'userid'=>'5', 'data1' => '', 'grade' => '-1', 'submissioncomment' => '');
        $this->assertEqual($return, $expected);
    }

    public function test_get_submission_files() {
        global $MBL;
        global $DB;

        $params = array();
        $params['courseid'] = 3;
        $params['assigid'] = 4;

        $files = array (
            (object) array('id'=>'202','contenthash'=>'da39a3ee5e6b4b0d3255bfef95601890afd80709','pathnamehash'=>'756b9bb8a57fa9e342d3b9490be11cad67c7916e','contextid'=>'13','component'=>'user','filearea'=>'draft','itemid'=>'325942611','filepath'=>'/test_f/','filename'=>'.','userid'=>NULL,'filesize'=>'0','mimetype'=>NULL,'status'=>'0','source'=>NULL,'author'=>NULL,'license'=>NULL,'timecreated'=>'1317976338','timemodified'=>'1317976338','sortorder'=>'0'),
            (object) array('id'=>'203','contenthash'=>'33bb682a56ac25609a7352eb2e3bf9abbcc37f78','pathnamehash'=>'2cbcbcaffb1ee36649c0d6ab908fa82eaa26ac89','contextid'=>'13','component'=>'user','filearea'=>'draft','itemid'=>'325942611','filepath'=>'/test_f/','filename'=>'notes.txt','userid'=>'2','filesize'=>'1215','mimetype'=>'text/plain','status'=>'0','source'=>NULL,'author'=>'Admin User','license'=>'allrightsreserved','timecreated'=>'1317976356','timemodified'=>'1317976356','sortorder'=>'0'),
            (object) array('id'=>'204','contenthash'=>'da39a3ee5e6b4b0d3255bfef95601890afd80709','pathnamehash'=>'e8b182b29e1d40ffc82c41a88fb8dd38fb7793d6','contextid'=>'13','component'=>'user','filearea'=>'private','itemid'=>'0','filepath'=>'/test_f/','filename'=>'.','userid'=>NULL,'filesize'=>'0','mimetype'=>NULL,'status'=>'0','source'=>NULL,'author'=>NULL,'license'=>NULL,'timecreated'=>'1317976338','timemodified'=>'1317976359','sortorder'=>'0'),
            (object) array('id'=>'205','contenthash'=>'33bb682a56ac25609a7352eb2e3bf9abbcc37f78','pathnamehash'=>'2efab3dfea275500da7b0f10e6487832bb4c7ee8','contextid'=>'13','component'=>'user','filearea'=>'private','itemid'=>'0','filepath'=>'/test_f/','filename'=>'notes.txt','userid'=>'2','filesize'=>'1215','mimetype'=>'text/plain','status'=>'0','source'=>NULL,'author'=>'Admin User','license'=>'allrightsreserved','timecreated'=>'1317976356','timemodified'=>'1317976359','sortorder'=>'0')
        );

        $context = array('id'=>3,'contextlevel'=>40,'instanceid'=>1,'path'=>'/1/3','depth'=>2);
        $DB->setReturnValueAt(0, 'get_record_sql', (object) array('id' => 1));//cm
        $MBL->DB->setReturnValueAt(0, 'get_record', (object) array('id' => 3));//subid
        $DB->setReturnValueAt(0, 'get_record', (object) $context); //get_context_instance
        $DB->setReturnValueAt(0, 'get_records', $files);
        $return = moodbileserver_assignment_external :: get_submission_files($params['assigid'], 0, 10);

        $expected = array( 0 =>  array ('fileid' => '203', 'filename' => 'notes.txt'),
                           1 =>  array ('fileid' => '205', 'filename' => 'notes.txt')
                    );
        $this->assertEqual($expected, $return);
    }

}
