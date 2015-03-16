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
 * Group DataBase Version Selector Class
 *
 * @package MoodbileServer
 * @subpackage Group
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

// Load Moodle Config
require_once(dirname(__FILE__).'/../../config.php');

// Load Moodbile Config
global $MBL;

$moodleversion = substr($CFG->release, 0, 3);
if ($moodleversion == '2.0' or $moodleversion == '2.1'){
    require_once($MBL->mblroot . '/group/db/m20/groupDB.class.php');
} else if ($moodleversion == '1.9'){
    require_once($MBL->mblroot . '/group/db/m19/groupDB.class.php');
}