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
 * Course Class
 *
 * @package MoodbileServer
 * @subpackage Course
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
 * Course Class
 *
 * @package MoodbileServer
 * @subpackage Course
 * @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
 * @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
 *
 * @author Jordi Piguillem
 * @author Nikolas Galanis
 * @author Imanol Urra
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Course extends ExternalObject{

    function __construct($courserecord) {
        parent::__construct($courserecord);
    }

    public static function get_class_structure() {
        return new external_single_structure(
            array(
                'id'            => new external_value(PARAM_INT,            'course id number', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'idnumber'      => new external_value(PARAM_RAW,            'id number', VALUE_OPTIONAL, '', NULL_NOT_ALLOWED),
                'category'      => new external_value(PARAM_INT,            'course category id', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'fullname'      => new external_value(PARAM_TEXT,           'full name of the course', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'shortname'     => new external_value(PARAM_TEXT,           'short name of the course', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
                'summary'       => new external_value(PARAM_RAW,            'course description', VALUE_OPTIONAL, null, NULL_ALLOWED),
                'format'        => new external_value(PARAM_ALPHANUMEXT,    'course format: weeks, topics, social, site,..', VALUE_DEFAULT, 1, NULL_NOT_ALLOWED),
                'startdate'     => new external_value(PARAM_INT,            'timestamp for course start', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'groupmode'     => new external_value(PARAM_INT,            'no group, separate, visible', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'lang'          => new external_value(PARAM_ALPHANUMEXT,    'forced course language', VALUE_OPTIONAL, '', NULL_NOT_ALLOWED),
                'timecreated'   => new external_value(PARAM_INT,            'timestamp of course creation', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'timemodified'  => new external_value(PARAM_INT,            'timestamp of course last modification', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'showgrades'    => new external_value(PARAM_INT,            '1 if grades are shown, otherwise 0', VALUE_DEFAULT, 1, NULL_NOT_ALLOWED)
            ), 'Course'
        );
    }

}
