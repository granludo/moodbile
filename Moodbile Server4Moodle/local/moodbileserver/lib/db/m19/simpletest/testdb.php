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
 * DataBase Tests
 *
 * @package MoodbileServer
 * @subpackage Lib
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

require_once(dirname(__FILE__).'/../../../../config.php');


class db19class_test extends UnitTestCase {

    public function test_db_get_record() {
        global $MBL, $CFG;

        if (!(substr($CFG->release, 0, 3) == '1.9')){
            return;
        }

        require_once($MBL->mblroot.'/lib/db/m19/db.class.php'); // Required here to not break execution in Moodle 2.0

        $mdlrecord = get_record('user', 'id', 1);
        $mblrecord = $MBL->DB->get_record('user', array('id' => 1));
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        $mdlrecord = get_record('user', 'id', 1, 'username', 'guest');
        $mblrecord = $MBL->DB->get_record('user', array('id' => 1, 'username' =>'guest'));
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        $mdlrecord = get_record('user', 'id', 2, 'username', 'guest'); // false
        $mblrecord = $MBL->DB->get_record('user', array('id' => 2, 'username' =>'guest')); //false
        $this->assertEqual($mdlrecord, $mblrecord,'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        $mdlrecord = get_record('user', 'id', 1, 'username', 'guest', 'auth', 'manual');
        $mblrecord = $MBL->DB->get_record('user', array('id' => 1, 'username' =>'guest', 'auth' => 'manual'));
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        $mdlrecord = get_record('user', 'id', 1, 'username', 'guest', 'auth', 'qwert'); // false
        $mblrecord = $MBL->DB->get_record('user', array('id' => 1, 'username' =>'guest', 'auth' => 'qwert')); //false
        $this->assertEqual($mdlrecord, $mblrecord,'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        $mdlrecord = get_record('user', 'id', 1, 'username', 'guest', '','', 'id, username');
        $mblrecord = $MBL->DB->get_record('user', array('id' => 1, 'username' =>'guest'), 'id, username');
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        $mdlrecord = get_record('user', 'id', 1, 'username', 'guest', '','', 'id, username');
        $mblrecord = $MBL->DB->get_record('user', array('id' => 1, 'username' =>'guest'), 'id'); // Different fields
        $this->assertNotEqual($mdlrecord, $mblrecord, 'Same result');
        $mblrecord->username = 'guest'; // Restore field
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');
    }

    public function test_db_get_records_sql() {
                global $MBL, $CFG;

        if (!(substr($CFG->release, 0, 3) == '1.9')){
            return;
        }

        require_once($MBL->mblroot.'/lib/db/m19/db.class.php'); // Required here to not break execution in Moodle 2.0

        // Simple SQL
        $mdlsql = "SELECT * " .
                "FROM ".$CFG->prefix."user";
        $mblsql = "SELECT * " .
                "FROM {user}";
        $mdlrecord = get_records_sql($mdlsql);
        $mblrecord = $MBL->DB->get_records_sql($mblsql);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');


        // Testing simple WHERE
        unset($mdlrecord);
        unset($mblrecord);
        $mdlsql = "SELECT * " .
                "FROM ".$CFG->prefix."user " .
                "WHERE id = 1";
        $mblsql = "SELECT * " .
                "FROM {user} " .
                "WHERE id = 1";
        $mdlrecord = get_records_sql($mdlsql);
        $mblrecord = $MBL->DB->get_records_sql($mblsql);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        // Testing WHERE and varibles
        unset($mdlrecord);
        unset($mblrecord);
        $id = 2;
        $mdlsql = "SELECT * " .
                "FROM ".$CFG->prefix."user " .
                "WHERE id = ". $id;
        $mblsql = "SELECT * " .
                "FROM {user} " .
                "WHERE id = :id";

        $params = array();
        $params['id'] = $id;
        $mdlrecord = get_records_sql($mdlsql);
        $mblrecord = $MBL->DB->get_records_sql($mblsql, $params);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        unset($mdlrecord);
        unset($mblrecord);
        unset($params);
        $id = 1;
        $username = 'guest';
        $mdlsql = "SELECT * " .
                "FROM ".$CFG->prefix."user " .
                "WHERE id = ". $id. " AND username ='" .$username."'";
        $mblsql = "SELECT * " .
                "FROM {user} " .
                "WHERE id = :id AND username = :username";
        $params = array();
        $params['id'] = $id;
        $params['username'] = $username;
        $mdlrecord = get_records_sql($mdlsql);
        $mblrecord = $MBL->DB->get_records_sql($mblsql, $params);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');


        unset($mdlrecord);
        unset($mblrecord);
        unset($params);
        $courseid = 2;
        $modulename = 'forum';
        $viewhidden = 'AND cm.visible = 1';

        $mdlsql = "SELECT cm.*, m.name, md.name as modname
                FROM ".$CFG->prefix."course_modules cm, ".$CFG->prefix."modules md, ".$CFG->prefix."forum m
                WHERE cm.course = ".$courseid." AND
                      cm.instance = m.id AND
                      md.name = '".$modulename."' AND
                      md.id = cm.module $viewhidden";

        $mblsql = "SELECT cm.*, m.name, md.name as modname
                FROM {course_modules} cm, {modules} md, {forum} m
                WHERE cm.course = :courseid AND
                      cm.instance = m.id AND
                      md.name = :modulename AND
                      md.id = cm.module $viewhidden";

        $params = array();
        $params['courseid'] = $courseid;
        $params['modulename'] = $modulename;
        $mdlrecord = get_records_sql($mdlsql);
        $mblrecord = $MBL->DB->get_records_sql($mblsql, $params);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        // Simple LIMITS
        $mdlsql = "SELECT * " .
                "FROM ".$CFG->prefix."user";
        $mblsql = "SELECT * " .
                "FROM {user}";
        $mdlrecord = get_records_sql($mdlsql,0, 2);
        $mblrecord = $MBL->DB->get_records_sql($mblsql, null, 0,2);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        $mdlrecord = get_records_sql($mdlsql,1, 2);
        $mblrecord = $MBL->DB->get_records_sql($mblsql, null, 1,2);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

        // JOINS
        unset($mdlrecord);
        unset($mblrecord);
        unset($params);

        $mdlsql = "SELECT * " .
                "FROM ".$CFG->prefix."forum f " .
                "JOIN ".$CFG->prefix."forum_discussions d " .
                        "ON f.id = d.forum " .
                "WHERE f.id = 1";
        $mblsql = "SELECT * " .
                "FROM {forum} f " .
                "JOIN {forum_discussions} d " .
                        "ON f.id = d.forum " .
                "WHERE f.id = 1";
        $mdlrecord = get_records_sql($mdlsql);
        $mblrecord = $MBL->DB->get_records_sql($mblsql);
        $this->assertClone($mdlrecord, $mblrecord, 'Same result');

    }

}