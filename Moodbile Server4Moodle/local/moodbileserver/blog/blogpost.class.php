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
 * Blog Post Class
 *
 * @package MoodbileServer
 * @subpackage Blog
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

class BlogPost extends ExternalObject{

    function __construct($eventrecord) {
        parent::__construct($eventrecord);
    }

    public static function get_class_structure(){
        return new external_single_structure(
            array (
                'id'                => new external_value(PARAM_INT,        'blog post id', VALUE_REQUIRED),
                'module'            => new external_value(PARAM_TEXT,       'module name', VALUE_REQUIRED),
                'userid'            => new external_value(PARAM_INT,        'Id of the user', VALUE_DEFAULT, 0),
                'courseid'          => new external_value(PARAM_INT,        'Id of the course', VALUE_DEFAULT, 0),
                'groupid'           => new external_value(PARAM_INT,        'Id of the group', VALUE_DEFAULT, 0),
                'moduleid'          => new external_value(PARAM_INT,        'Id of the module', VALUE_DEFAULT, 0),
                'coursemoduleid'    => new external_value(PARAM_INT,        'Id of the course module', VALUE_DEFAULT, 0),
                'subject'           => new external_value(PARAM_TEXT,       'blog post subject', VALUE_REQUIRED),
                'summary'           => new external_value(PARAM_TEXT,       'blog post summary', VALUE_DEFAULT, null),
                'created'           => new external_value(PARAM_INT,        'date of creation', VALUE_REQUIRED),
                'lastmodified'      => new external_value(PARAM_INT,        'date of last modification', VALUE_DEFAULT, null),
            )
        );
    }
}
