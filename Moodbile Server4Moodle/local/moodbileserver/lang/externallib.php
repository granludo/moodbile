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
 * Language External Functions
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

defined('MOODLE_INTERNAL') || die;
require_once(dirname(__FILE__).'/../config.php');
global $MBL;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot . '/lang/lang.class.php');


class moodbileserver_lang_external extends external_api {

    public static function get_texts_parameters() {
        return new external_function_parameters(
            array(
                'strings' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'stringname' => new external_value(PARAM_TEXT, 'The id (a string) for the string', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                            'module'     => new external_value(PARAM_TEXT, 'The module where the id is', VALUE_DEFAULT, '', NULL_NOT_ALLOWED),
                            'a'          => new external_value(PARAM_TEXT, 'An object, string or number that can be used within translation strings', VALUE_OPTIONAL)
                        )
                    )
                )
            )
        );
    }

    public static function get_texts($params) {

//      $params = self::validate_parameters(self::get_texts_parameters(), array('strings' => $options));
        $params = array('strings' => $params);
        $return = array();

        foreach ($params['strings'] as $param) {
          $string = get_string($param['stringname'], $param['module'], (isset($param['a'])) ? $param['a'] : null);
          $return[] = array('id' => $param['stringname'], 'module' => $param['module'], 'string' => $string);
        }
      return $return;
    }

    public static function get_texts_returns() {
        return
            new external_multiple_structure(
                Lang::get_class_structure()
            );
    }

    public static function get_all_texts_parameters() {
        return new external_function_parameters(
            array(
                'modules' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'modulename'    => new external_value(PARAM_TEXT, 'The module name id the strings are associated with', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                        )
                    )
                )
            )
        );
    }

    public static function get_all_texts($params) {

//        $params = self::validate_parameters(self::get_all_texts_parameters(), array('modules' => $options));
        $params = array('modules' => $params);
        $return = array();
        $ret = array();
        $lang = current_language();
        $stringman  = get_string_manager();
        foreach ($params['modules'] as $param) {
          $moduleid = $param['modulename'];
          $languagestrings = $stringman->load_component_strings($param['modulename'], $lang);
          foreach ($languagestrings as $stringid => $stringoriginal) {
              $return[] = array('id' => $stringid, 'module' => $param['modulename'], 'string' => $stringoriginal);
          }
          $ret[] = array('modulename' => $moduleid, 'strings' => $return);
        }
        return $ret;
    }

    public static function get_all_texts_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'modulename' => new external_value(PARAM_TEXT, 'The module name id the strings are associated with', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                    'strings' => new external_multiple_structure(Lang::get_class_structure()),
                )
            )
        );
    }
}
