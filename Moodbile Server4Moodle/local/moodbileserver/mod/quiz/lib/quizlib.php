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
 * Quiz External API Auxiliary Library
 *
 * @package MoodbileServer
 * @subpackage Quiz
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


function export_to_file($exporttext, $quizid, $type) {
    global $USER;
    $fs = get_file_storage();
    $context = get_context_instance(CONTEXT_USER, $USER->id);
    $filerecord = new stdClass;
    $filerecord->contextid = $context->id;
    $filerecord->component = "user";
    $filerecord->filearea = "private";
    $filerecord->itemid = 0;
    $filerecord->filepath = '/moodbile/quiz/';
    $filerecord->filename = 'quiz_'.$quizid.'.'.$type;
    $filerecord->userid = $USER->id;

    $file = $fs->get_file($filerecord->contextid, $filerecord->component, $filerecord->filearea, 0, $filerecord->filepath, $filerecord->filename);
    if ($file){
        $file->delete();
    }

    $file = $fs->create_file_from_string($filerecord, $exporttext);
print_object($file);

    return $file;
}

