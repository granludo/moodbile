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
 * Language Functions Tests
 *
 * @package MoodbileServer
 * @subpackage Lang
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

require_once($MBL->mblroot.'/lang/lang.class.php');
require_once($MBL->mblroot.'/lang/externallib.php');

class langexternal_test extends UnitTestCase {

	public function test_lang_get_texts() {

        $strings = array();
        $params = array();
        $strings['stringname'] = 'advancedoptions';
        $strings['module'] = 'calendar';
        $strings['a'] = NULL;

        $strings1['stringname'] = 'accessdenied';
        $strings1['module'] = 'admin';
        $strings1['a'] = NULL;
        
        $params = array($strings, $strings1);

        $result = moodbileserver_lang_external::get_texts($params);
        $translation = get_string($strings['stringname'], $strings['module']);
        var_dump($result);
        $this->assertEqual($result[0]['string'], $translation , "lang: " . $result[0]['string'] . " == " . $translation . "?");
        // @TODO: Finish tests
	}

	public function test_lang_get_all_texts() {
	    $strings = array();
        $params = array();
        $strings['modulename'] = 'table';
        $strings1['modulename'] = 'admin';
        //$params = array('modules' => $strings);
        $params = array($strings, $strings1);
        $result = moodbileserver_lang_external::get_all_texts($params);

        $lang = current_language();
        $stringman  = get_string_manager();
        $translation = array_merge($stringman->load_component_strings($strings['modulename'], $lang) , $stringman->load_component_strings($strings1['modulename'], $lang));
        $this->assertEqual(count($result),2, 'lang: ' . count($result) . ' == ' . 2 . "?" );
        $firstvalue=reset($translation);
        var_dump($result);
        $this->assertEqual($result[0]['strings'][0]['string'], $firstvalue , 'lang: ' . $result[0]['strings'][0]['string'] . ' == '. $firstvalue . "?");
        //@TODO: Do this better
	}

}