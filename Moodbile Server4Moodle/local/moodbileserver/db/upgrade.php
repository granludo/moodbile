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
 * Database Upgrade
 *
 * @package MoodbileServer
 * @subpackage Database
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

function xmldb_local_moodbileserver_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2012050100) {

        // Define table mbl_tokens to be created
        $table = new xmldb_table('mbl_tokens');

        // Adding fields to table mbl_tokens
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('token', XMLDB_TYPE_CHAR, '128', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('fid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('timedue', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);

        // Adding keys to table mbl_tokens
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('unique', XMLDB_KEY_UNIQUE, array('token'));

        // Conditionally launch create table for mbl_tokens
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // moodbileserver savepoint reached
        upgrade_plugin_savepoint(true, 2012050100, 'local', 'moodbileserver');
    }

    return true;
}
