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
 * DataBase Class for Moodle 2.0
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

class DB {

    public function __construct(){
    }

    public function get_record($table, array $conditions, $fields='*', $strictness=IGNORE_MISSING) {
        global $DB;
        return $DB->get_record($table, $conditions, $fields, $strictness);
    }

    public function get_record_sql($sql, array $params=null, $strictness=IGNORE_MISSING) {
        global $DB;
        return $DB->get_record_sql($sql, $params, $strictness);
    }

    public function get_records($table, array $conditions=null, $sort='', $fields='*', $limitfrom=0, $limitnum=0){
        global $DB;
        return $DB->get_records($table, $conditions, $sort, $fields, $limitfrom, $limitnum);
    }

    public function get_records_sql($sql, array $params=null, $limitfrom=0, $limitnum=0) {
        global $DB;
        return $DB->get_records_sql($sql, $params, $limitfrom, $limitnum);
    }

    public function get_in_or_equal($items, $type=SQL_PARAMS_QM, $prefix='param', $equal=true) {
        global $DB;
        return $DB->get_in_or_equal($items, $type, $prefix, $equal);
    }

    public function execute($sql, array $params=null){
        global $DB;
        return $DB->execute($sql, $params);
    }

    public function delete_records($table, array $conditions=null) {
        global $DB;
        return $DB->delete_records($table, $conditions);
    }

    public function set_field($table, $newfield, $newvalue, array $conditions=null) {
        global $DB;
        return $DB->set_field($table, $newfield, $newvalue, $conditions);
    }

    public function insert_record($table, $dataobject, $returnid=true, $bulk=false) {
        global $DB;
        return $DB->insert_record($table, $dataobject, $returnid, $bulk);
    }

    public function update_record($table, $dataobject, $bulk=false) {
        global $DB;
        return $DB->update_record($table, $dataobject, $bulk);
    }

}
?>
