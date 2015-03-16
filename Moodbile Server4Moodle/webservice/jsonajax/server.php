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
 * Moodbile JSON AJAX
 *
 * @package MoodbileServer
 * @subpackage jsonajax
 * @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
 * @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
 *
 * @author Jordi Piguillem
 * @author Nikolas Galanis
 * @author Imanol Urra
 * @author Oscar Martinez
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */


// disable moodle specific debug messages and any errors in output
define('NO_DEBUG_DISPLAY', false);
//define('NO_MOODLE_COOKIES', true);

require_once('../../local/moodbileserver/config.php');
require_once($CFG->dirroot.'/webservice/jsonajax/locallib.php');

if (!webservice_protocol_is_enabled('jsonajax')) {
    die;
}

$server = new webservice_jsonajax_server(false);
$server->run();
die;

