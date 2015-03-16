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
 * File External Functions' Tests
 *
 * @package MoodbileServer
 * @subpackage Files
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

require_once($MBL->mblroot . '/files/externallib.php');

Mock :: generatePartial(get_class($MBL->DB), 'filesMockDB', array('get_record', 'get_records_sql' , 'get_record_sql'));

global $DB;
Mock :: generatePartial(get_class($DB), 'filesMockDBglob', array('get_records'));

class filesexternal_test extends UnitTestCase {

    public $realDB;

    function setUp() {
        global $MBL;

        $this->realDB = $MBL->DB;
        $MBL->DB = new filesMockDB();

        global $DB;
        $this->realDB = $DB;
        $DB = new filesMockDBglob();
    }

    function tearDown() {
        global $MBL;
        $MBL->DB = $this->realDB;

        global $DB;
        $DB = $this->realDB;
    }

    public function test_get_user_files() {
        global $MBL;
        global $DB;
        $params = array();
        $params['startpage'] = 0;
        $params['n'] = 10;

        $ret_files = array ( 0 => (object) array('id'=>'205','contenthash'=>'33bb682a56ac25609a7352eb2e3bf9abbcc37f78','pathnamehash'=>'2efab3dfea275500da7b0f10e6487832bb4c7ee8','contextid'=>'13','component'=>'user','filearea'=>'private','itemid'=>'0','filepath'=>'/test_f/','filename'=>'notes.txt','userid'=>'2','filesize'=>'1215','mimetype'=>'text/plain','status'=>'0','source'=>NULL,'author'=>'Admin User','license'=>'allrightsreserved','timecreated'=>'1317976356','timemodified'=>'1317976359','sortorder'=>'0'),
                             1 => (object) array('id'=>'172','contenthash'=>'a8a48e9afa79166ce27e78d32805e56914c73ff6','pathnamehash'=>'7ce39de1ccb193de94da75bfb8721d71ddbc8c50','contextid'=>'13','component'=>'user','filearea'=>'private','itemid'=>'0','filepath'=>'/','filename'=>'aaabbb.png','userid'=>NULL,'filesize'=>'144','mimetype'=>'image/png','status'=>'0','source'=>NULL,'author'=>NULL,'license'=>NULL,'timecreated'=>'1317291116','timemodified'=>'1317291116','sortorder'=>'0'),
                             2 => (object) array('id'=>'177','contenthash'=>'54a5a5967cce9b32bf5096c1229dc1b14be5079c','pathnamehash'=>'3d25ef1d207cc5266dbe0a5af112f31cbe434f5b','contextid'=>'13','component'=>'user','filearea'=>'private','itemid'=>'0','filepath'=>'/','filename'=>'a.html','userid'=>'2','filesize'=>'1877','mimetype'=>'text/html','status'=>'0','source'=>NULL,'author'=>'Admin User','license'=>'allrightsreserved','timecreated'=>'1317291990','timemodified'=>'1317291997','sortorder'=>'0'),
            );
        $DB->setReturnValueAt(0, 'get_records', $ret_files);
        $ret = call_user_func_array(array('moodbileserver_files_external', 'get_user_filesinfo'),$params);
        $expected_output = array ( 0 => array ( 'fileid' => '205', 'filename' => 'notes.txt'),
                                   1 => array ( 'fileid' => '172', 'filename' => 'aaabbb.png'),
                                   2 => array ( 'fileid' => '177', 'filename' => 'a.html')
                           );
        for ($i=0; $i<3; $i++) {
            $this->assertEqual($ret[$i], $expected_output[$i]);
        }
    }

}