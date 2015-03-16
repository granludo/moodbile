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
 * Moodbile Server Library
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

require_once(dirname(__FILE__).'/config.php');
global $MBL;

/**
 * Moodbile app service name
 */
define('MOODLE_MOODBILE_SERVICE', 'moodbile_app');

function get_link($userpicture){
    global $OUTPUT, $PAGE, $USER;

    if (empty($userpicture->size)) {
        $file = 'f2';
        $size = 35;
    } else if ($userpicture->size === true or $userpicture->size == 1) {
        $file = 'f1';
        $size = 100;
    } else if ($userpicture->size >= 50) {
        $file = 'f1';
        $size = $userpicture->size;
    } else {
        $file = 'f2';
        $size = $userpicture->size;
    }

    $class = $userpicture->class;
    $user = $userpicture->user;
    $usercontext = get_context_instance(CONTEXT_USER, $USER->id);
    if ($user->picture == 1) {
        $usercontext = get_context_instance(CONTEXT_USER, $user->id);
        $src = moodle_url::make_pluginfile_url($usercontext->id, 'user', 'icon', NULL, '/', $file);
    } else if ($user->picture == 2) {
        //TODO: gravatar user icon support
    } else { // Print default user pictures (use theme version if available)
        $PAGE->set_context($usercontext);
        $src = $OUTPUT->pix_url('u/' . $file);
    }

    return $src->out();
}
