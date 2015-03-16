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
 * Assignment Class
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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once(dirname(__FILE__).'/../../config.php');
global$MBL;
require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot.'/lib/externalObject.class.php');

/**
* Assignment Class
*
* @package MoodbileServer
* @subpackage Assignment
* @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
* @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
*
* @author Oscar Martinez
*
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

class Assignment extends ExternalObject{

    function __construct($assignmentrecord) {
        parent::__construct($assignmentrecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array (
                'id'              => new external_value(PARAM_INT,  'assignment record id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'name'            => new external_value(PARAM_TEXT, 'multilang compatible name', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'intro'           => new external_value(PARAM_RAW,  'assignment description text', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'assignmenttype'  => new external_value(PARAM_ALPHA, 'assignment type: upload, online, uploadsingle, offline', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'maxbytes'        => new external_value(PARAM_INT,  'maximium bytes per submission', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'timedue'         => new external_value(PARAM_INT,  'assignment due time', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'grade'           => new external_value(PARAM_INT,  'grade scale for assignment', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            ), 'Assignment'
        );
    }
}
/*id	course 	name	intro	introformat	assignmenttype	resubmit	preventlate	emailteachers	var1	var2	var3	var4	var5	maxbytes	timedue	timeavailable	grade	timemodified
*/

