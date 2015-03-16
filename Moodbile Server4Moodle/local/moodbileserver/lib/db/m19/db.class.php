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
 * DataBase Class for Moodle 1.9
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
    public function get_record($table, array $conditions, $fields='*', $strictness=null) {
        $field1 = '';
        $value1 = '';
        $field2 = '';
        $value2 = '';
        $field3 = '';
        $value3 = '';

        $i = 1;
        foreach ($conditions as $key=>$val){
            $field = 'field'.$i;
            $value = 'value'.$i;
            $$field = $key;
            $$value = $val;
            $i++;
        }

        return get_record($table, $field1, $value1, $field2, $value2, $field3, $value3, $fields);
    }

    public function get_record_sql($sql, array $params=null, $strictness=IGNORE_MISSING) {
        $sql = $this->fix_table_names($sql);
        $sql = $this->fix_sql_params($sql, $params);
        return get_record_sql($sql);
    }

    public function get_records($table, array $conditions=null, $sort='', $fields='*', $limitfrom=0, $limitnum=0){
        $field = array_shift(array_keys($conditions));
        $value = $conditions[$field];
        return get_records($table, $field, $value, $sort, $fields, $limitfrom, $limitnum);
    }

    public function get_records_sql($sql, array $params=null, $limitfrom=0, $limitnum=0) {

        $sql = $this->fix_table_names($sql);
        $sql = $this->fix_sql_params($sql, $params);
        return get_records_sql($sql, $limitfrom, $limitnum);
    }

    public function delete_records($table, array $conditions=null) {
        $field1 = '';
        $value1 = '';
        $field2 = '';
        $value2 = '';
        $field3 = '';
        $value3 = '';

        $i = 1;
        foreach ($conditions as $key=>$val){
            $field = 'field'.$i;
            $value = 'value'.$i;
            $$field = $key;
            $$value = $val;
            $i++;
        }
        return delete_records($table, $field1, $value1, $field2, $value2, $field3, $value3);
    }

    public function set_field($table, $newfield, $newvalue, array $conditions=null) {
        $field1 = '';
        $value1 = '';
        $field2 = '';
        $value2 = '';
        $field3 = '';
        $value3 = '';

        $i = 1;
        foreach ($conditions as $key=>$val){
            $field = 'field'.$i;
            $value = 'value'.$i;
            $$field = $key;
            $$value = $val;
            $i++;
        }
        return set_field($table, $newfield, $newvalue, $field1, $value1, $field2, $value2, $field3, $value3);
    }

    public function insert_record($table, $dataobject, $returnid=true, $bulk=false) {
        return insert_record($table, $dataobject, $returnid, $primarykey='id');
    }

    public function update_record($table, $dataobject, $bulk=false) {
        return update_record($table, $dataobject);
    }

    public function execute($sql, array $params=null){
        $sql = $this->fix_table_names($sql);
        $sql = $this->fix_sql_params($sql, $params);
        return execute_sql($sql,false);
    }

    /**
     * Converts short table name {tablename} to real table name
     * @param string sql
     * @return string sql
     */
    private function fix_table_names($sql) {
        global $CFG;
        return preg_replace('/\{([a-z][a-z0-9_]*)\}/', $CFG->prefix.'$1', $sql);
    }

    private function fix_sql_params($sql, array $params=null) {
        $params = (array)$params; // mke null array if needed
        $count = 0;
        // cast booleans to 1/0 int
        foreach ($params as $key => $value) {
            $params[$key] = is_bool($value) ? (int)$value : $value;
            $params[$key] = is_string($value) ? "'".$value."'" : $value;
        }

        // NICOLAS C: Fixed regexp for negative backwards lookahead of double colons. Thanks for Sam Marshall's help
        $named_count = preg_match_all('/(?<!:):[a-z][a-z0-9_]*/', $sql, $named_matches); // :: used in pgsql casts

        if ($named_count) {
            $count = $named_count;
        }


        if ($count > count($params)) {
            $a = new stdClass;
            $a->expected = $count;
            $a->actual = count($params);
            throw new Exception('invalidqueryparam', $a);
        }

        foreach($params as $key=>$value){
            $sql = str_replace(':'.$key, $value, $sql);
        }

        return $sql;
    }

}
?>