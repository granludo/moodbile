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
 * Calendar Event Class
 *
 * @package MoodbileServer
 * @subpackage Calendar
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
* Event Class
*
* @package MoodbileServer
* @subpackage Calendar
* @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
* @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
*
* @author Oscar Martinez
*
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

class Event extends ExternalObject{

    function __construct($eventrecord) {
        parent::__construct($eventrecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array (
                'id'          => new external_value(PARAM_INT,        'The id within the event table', VALUE_REQUIRED, 0 , NULL_NOT_ALLOWED),
                'name'        => new external_value(PARAM_TEXT,       'The name of the event', VALUE_REQUIRED, '0' , NULL_NOT_ALLOWED),
                'description' => new external_value(PARAM_RAW,        'The description of the event', VALUE_REQUIRED, '' , NULL_NOT_ALLOWED),
                'courseid'    => new external_value(PARAM_INT,        'The course the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'groupid'     => new external_value(PARAM_INT,        'The group the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'userid'      => new external_value(PARAM_INT,        'The user the event is associated with (0 if none)', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
                'repeatid'    => new external_value(PARAM_INT,        'If this is a repeated event this will be set to the id of the original', VALUE_DEFAULT, 0 , NULL_NOT_ALLOWED),
                'modulename'  => new external_value(PARAM_TEXT,       'If added by a module this will be the module name', VALUE_DEFAULT, '0' , NULL_NOT_ALLOWED),
                'instance'    => new external_value(PARAM_INT,        'If added by a module this will be the module instance', VALUE_DEFAULT, 0 , NULL_NOT_ALLOWED),
                'eventtype'   => new external_value(PARAM_ALPHANUMEXT,'The event type', VALUE_REQUIRED, '0' , NULL_NOT_ALLOWED),
                'timestart'   => new external_value(PARAM_INT,        'Timestamp in UTC that holds when event starts or, in assignment module, date when event expires', VALUE_REQUIRED, 0 , NULL_NOT_ALLOWED),
                'timeduration'=> new external_value(PARAM_INT,        'The duration of the event in seconds', VALUE_DEFAULT, 0, NULL_NOT_ALLOWED),
            ), 'Event'
        );
    }
}