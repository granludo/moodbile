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
 * User Class
 *
 * @package MoodbileServer
 * @subpackage User
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

require_once(dirname(__FILE__).'/../config.php');
global$MBL;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot.'/lib/externalObject.class.php');
/**
* Course Class
*
* @package MoodbileServer
* @subpackage User
* @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
* @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
*
* @author Jordi Piguillem
* @author Nikolas Galanis
* @author Imanol Urra
*
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class User extends ExternalObject{

    function __construct($userrecord) {
        parent::__construct($userrecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array(
                'id'            => new external_value(PARAM_INT,    'ID of the user', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'username'      => new external_value(PARAM_RAW,    'Username policy is defined in Moodle security config', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'idnumber'      => new external_value(PARAM_RAW,    'An arbitrary ID code number perhaps from the institution', VALUE_OPTIONAL, '', NULL_NOT_ALLOWED),
                'firstname'     => new external_value(PARAM_TEXT,   'The first name of the user', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'lastname'      => new external_value(PARAM_TEXT,   'The family name of the user', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'email'         => new external_value(PARAM_EMAIL,  'A valid and unique email address', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'city'          => new external_value(PARAM_TEXT,   'Home city of the user', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'country'       => new external_value(PARAM_TEXT,   'Home country code of the user, such as AU or CZ', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'lang'          => new external_value(PARAM_TEXT,   'Language code such as "en", must exist on server', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'avatar'        => new external_value(PARAM_URL,    'URL of the user avatar', VALUE_OPTIONAL, '', NULL_NOT_ALLOWED),
                'timemodified'  => new external_value(PARAM_INT,    'Time of last modification in seconds', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            )
        );
    }

}