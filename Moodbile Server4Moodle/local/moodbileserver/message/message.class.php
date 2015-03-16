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
 * Message Class
 *
 * @package MoodbileServer
 * @subpackage Message
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
* Message Class
*
* @package MoodbileServer
* @subpackage Message
* @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
* @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
*
* @author Oscar Martinez
*
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

class Message extends ExternalObject{

    function __construct($grouprecord) {
        parent::__construct($grouprecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array (
                'id'          => new external_value(PARAM_INT,  'message id'),
                'useridfrom'  => new external_value(PARAM_INT,  'user id from'),
                'useridto'    => new external_value(PARAM_INT,  'user id to'),
                'subject'     => new external_value(PARAM_TEXT, 'message subject'),
                'fullmessage' => new external_value(PARAM_RAW,  'full message'),
                'smallmessage'=> new external_value(PARAM_TEXT, 'small message'),
            )
        );
    }
}
/*
id	bigint(10)	No
useridfrom	bigint(10)	No 	0
useridto	bigint(10)	No 	0
subject	text	Si 	NULL
fullmessage	text	Si 	NULL
fullmessageformat	smallint(4)	Si 	0
fullmessagehtml	mediumtext	Si 	NULL
smallmessage	text	Si 	NULL
notification	tinyint(1)	Si 	0
contexturl	text	Si 	NULL
contexturlname	text	Si 	NULL
timecreated
*/