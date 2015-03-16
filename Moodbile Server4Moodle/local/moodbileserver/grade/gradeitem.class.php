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
 * Grade Item Class
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
class Gradeitem extends ExternalObject {

    function __construct($gradeitemrecord) {
        parent::__construct($gradeitemrecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array(
                'id'                => new external_value(PARAM_INT,        'grade item id number', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'courseid'          => new external_value(PARAM_INT,        'the id of the course, the graded item belongs to', VALUE_REQUIRED, null, NULL_ALLOWED),
                'itemname'          => new external_value(PARAM_TEXT,       'name of the graded item', VALUE_REQUIRED, null, NULL_ALLOWED),
                'itemtype'          => new external_value(PARAM_ALPHA,      'type of the graded item', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'itemmodule'        => new external_value(PARAM_ALPHANUMEXT,'module of the grade item', VALUE_REQUIRED, null, NULL_ALLOWED),
                'iteminstance'      => new external_value(PARAM_INT,        'instance of the grade item', VALUE_REQUIRED, null, NULL_ALLOWED),
                'itemnumber'        => new external_value(PARAM_INT,        'number of the grade item', VALUE_REQUIRED, null, NULL_ALLOWED),
                'gradepass'         => new external_value(PARAM_FLOAT,      'minimum grade needed to pass', VALUE_REQUIRED, 0.00, NULL_NOT_ALLOWED),
                'grademax'          => new external_value(PARAM_FLOAT,      'maximum attainable grade', VALUE_REQUIRED, 100.00, NULL_NOT_ALLOWED),
                'grademin'          => new external_value(PARAM_FLOAT,      'minimum attainable grade', VALUE_REQUIRED, 0.00, NULL_NOT_ALLOWED),
                'locked'            => new external_value(PARAM_INT,        'grade is locked against further changes', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'hidden'            => new external_value(PARAM_INT,        'grade is hidden from users without the required privileges', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'timecreated'       => new external_value(PARAM_INT,        'time of creation in seconds', VALUE_REQUIRED, null, NULL_NOT_ALLOWED),
                'timemodified'      => new external_value(PARAM_INT,        'time of last modification in seconds', VALUE_REQUIRED, null, NULL_ALLOWED)
            ), 'Gradeitem'
        );
    }

}
