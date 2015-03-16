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
 * Quiz QTI 2.0 Question format class
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

global $CFG;
require_once($CFG->dirroot.'/question/format.php');
require_once($CFG->dirroot.'/question/format/qti_two/format.php');
require_once("{$CFG->libdir}/smarty/Smarty.class.php");

class mbl_qformat_qti_two extends qformat_qti_two {

    /**
     * Do the export
     * For most types this should not need to be overrided
     * @return stored_file
     */
    function exportprocess($questions) {
        global $CFG, $OUTPUT, $DB, $USER;

        //echo $OUTPUT->notification(get_string('exportingquestions','quiz'));
        $count = 0;

        // results are first written into string (and then to a file)
        // so create/initialize the string here
        $expout = "";

        // track which category questions are in
        // if it changes we will record the category change in the output
        // file if selected. 0 means that it will get printed before the 1st question
        $trackcategory = 0;

        $fs = get_file_storage();

        // iterate through questions
        foreach($questions as $question) {
            // used by file api
            $contextid = $DB->get_field('question_categories', 'contextid', array('id'=>$question->category));
            $question->contextid = $contextid;

            // do not export hidden questions
            if (!empty($question->hidden)) {
                continue;
            }

            // do not export random questions
            if ($question->qtype==RANDOM) {
                continue;
            }

            // check if we need to record category change
            if ($this->cattofile) {
                if ($question->category != $trackcategory) {
                    $trackcategory = $question->category;
                    $categoryname = $this->get_category_path($trackcategory, $this->contexttofile);

                    // create 'dummy' question for category export
                    $dummyquestion = new stdClass();
                    $dummyquestion->qtype = 'category';
                    $dummyquestion->category = $categoryname;
                    $dummyquestion->name = 'Switch category to ' . $categoryname;
                    $dummyquestion->id = 0;
                    $dummyquestion->questiontextformat = '';
                    $dummyquestion->contextid = 0;
                    $expout .= $this->writequestion($dummyquestion) . "\n";
                }
            }

            // export the question displaying message
            $count++;

            if (question_has_capability_on($question, 'view', $question->category)) {
                // files used by questiontext
                $files = $fs->get_area_files($contextid, 'question', 'questiontext', $question->id);
                $question->questiontextfiles = $files;
                // files used by generalfeedback
                $files = $fs->get_area_files($contextid, 'question', 'generalfeedback', $question->id);
                $question->generalfeedbackfiles = $files;
                if (!empty($question->options->answers)) {
                    foreach ($question->options->answers as $answer) {
                        if ($question->qtype == 'truefalse') {
                            $trueindex = $question->options->trueanswer;
                            $falseindex = $question->options->falseanswer;
                            $question->options->answers['true'] = $question->options->answers[$trueindex];
                            $question->options->answers['false'] = $question->options->answers[$falseindex];
                        }
                        $files = $fs->get_area_files($contextid, 'question', 'answerfeedback', $answer->id);
                        $answer->feedbackfiles = $files;
                    }
                }

                $expout .= $this->writequestion($question, $contextid) . "\n";
            }
        }

        // final pre-process on exported data
        $expout = $this->presave_process($expout);
        return $expout;
    }

}
