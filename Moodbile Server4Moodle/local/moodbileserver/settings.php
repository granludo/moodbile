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
 * Moodbile Server
 *
 * @package MoodbileServer
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

defined('MOODLE_INTERNAL') || die;
require_once(dirname(__FILE__) . '/settingslib.php');

$pagetitle = get_string('pluginname', 'local_moodbileserver');
$moodbileserversettings = new admin_settingpage('pluginsettingmoodbile', $pagetitle, 'moodle/site:config');

$moodbileserversettings->add(new admin_setting_enablemoodbileservice('moodbileserverenableui',
                                                              get_string('enablemoodbileserverwebservice', 'local_moodbileserver'),
                                                              get_string('configenablemoodbileserverwebservice', 'local_moodbileserver'), 0));

$ADMIN->add('webservicesettings', $moodbileserversettings);
