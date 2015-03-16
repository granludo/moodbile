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
 * Grade Class
 *
 * @package MoodbileServer
 * @subpackage Grade
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

require_once($MBL->mdllibdir.'/externallib.php');
require_once($MBL->mblroot.'/lib/externalObject.class.php');

/**
 * Grade Class
 *
 * @package MoodbileServer
 * @subpackage Course
 * @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
 * @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
 *
 * @author Nikolas Galanis
 * @author Jordi Piguillem
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Grade extends ExternalObject {

    function __construct($graderecord) {
        parent::__construct($graderecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array(
                'id'                => new external_value(PARAM_INT, 'grade id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'itemid'            => new external_value(PARAM_INT, 'item id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'userid'            => new external_value(PARAM_INT, 'user id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'rawgrade'          => new external_value(PARAM_FLOAT, 'raw grade', VALUE_OPTIONAL, null, NULL_ALLOWED),
                'rawgrademax'       => new external_value(PARAM_FLOAT, 'maximum grade', VALUE_REQUIRED, 100.00, NULL_NOT_ALLOWED),
                'rawgrademin'       => new external_value(PARAM_FLOAT, 'minimum grade', VALUE_REQUIRED, 0.00, NULL_NOT_ALLOWED),
                'rawscaleid'        => new external_value(PARAM_INT, 'scale id', VALUE_OPTIONAL, null, NULL_ALLOWED),
                'finalgrade'        => new external_value(PARAM_FLOAT, 'final grade', VALUE_REQUIRED, null, NULL_ALLOWED),
                'locked'            => new external_value(PARAM_INT, '1 if grade is locked, 0 otherwise', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'hidden'            => new external_value(PARAM_INT, '1 if grade is hidden, 0 otherwise', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'feedback'          => new external_value(PARAM_RAW, 'feedback comments left in addition to the numeric grade', VALUE_OPTIONAL, null, NULL_ALLOWED),
                'feedbackformat'    => new external_value(PARAM_INT, 'feedback format', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'timecreated'       => new external_value(PARAM_INT, 'time of creation in seconds', VALUE_REQUIRED, 0, NULL_ALLOWED),
                'timemodified'      => new external_value(PARAM_INT, 'time of last modification in seconds', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED)
            ), 'Grade'
        );
    }

}
