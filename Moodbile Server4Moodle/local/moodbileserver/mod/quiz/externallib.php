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
 * Quiz External API Library
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

require_once(dirname(__FILE__).'/../../config.php');
global $MBL;
require_once("$MBL->mdllibdir/externallib.php");
require_once($CFG->dirroot.'/mod/quiz/locallib.php');
require_once($CFG->dirroot.'/mod/data/lib.php');
require_once("$MBL->mblroot/mod/quiz/db/quizDB.php");


class moodbileserver_quiz_external extends external_api {

    /**
     * Makes sure user may execute functions in this context.
     * @param object $context
     * @return void
     */
    protected static function validate_context($context) {
        global $CFG;

        if (empty($context)) {
            throw new invalid_parameter_exception('Context does not exist');
        }

        $rcontext = get_context_instance(CONTEXT_SYSTEM);

        if ($rcontext->contextlevel == $context->contextlevel) {
            if ($rcontext->id != $context->id) {
                throw new restricted_context_exception();
            }
        } else if ($rcontext->contextlevel > $context->contextlevel) {
            throw new restricted_context_exception();
        } else {
            $parents = get_parent_contexts($context);
            if (!in_array($rcontext->id, $parents)) {
                throw new restricted_context_exception();
            }
        }

        if ($context->contextlevel >= CONTEXT_COURSE) {
            list($context, $course, $cm) = get_context_info_array($context->id);
        }
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function export_quiz_to_xml_parameters() {
        return new external_function_parameters (
            array(
                'quizid' => new external_value(PARAM_INT,  'A quiz Id ', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    /**
     * Exports quiz questions in XML
     *
     * @param int quizid
     * @return XML text
     */
    public static function export_quiz_to_xml($quizid) {
        global $MBL;
        require_once("$MBL->mblroot/mod/quiz/lib/mbl_qformat_xml.php");
        require_once("$MBL->mblroot/mod/quiz/lib/quizlib.php");
        if (!$cm = get_coursemodule_from_instance('quiz', $quizid)) {
            throw new moodle_exception('generalexceptionmessage','moodbile_quiz', '','Quiz not found');
        }

        $context = get_context_instance(CONTEXT_MODULE, $cm->id);

        self::validate_context($context);

        $questions = quiz_db::moodbile_get_quiz_questions($quizid);

        $withcategories = 'withcategories';
        $withcontexts = 'withcontexts';
        $qformat = new mbl_qformat_xml();
        $exporttext = $qformat->exportprocess($questions);
print_object($exporttext);
        $file = export_to_file($exporttext, $quizid, 'xml');

        return array('fileid' => $file->get_id());
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function export_quiz_to_xml_returns() {
        return new external_single_structure(
            array(
                'fileid' => new external_value(PARAM_INT, 'id of the xml file containing the exported questions', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function export_quiz_to_qti_parameters() {
        return new external_function_parameters (
            array(
                'quizid' => new external_value(PARAM_INT,  'A quiz Id ', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
            )
        );
    }

    /**
     * Exports quiz questions in IMS QTI
     *
     * @param int quizid
     * @return QTI text
     */
    public static function export_quiz_to_qti($quizid) {
        global $MBL;
        require_once("$MBL->mblroot/mod/quiz/lib/mbl_qformat_qti_two.php");
        require_once("$MBL->mblroot/mod/quiz/lib/quizlib.php");
        if (!$cm = get_coursemodule_from_instance('quiz', $quizid)) {
            throw new moodle_exception('generalexceptionmessage','moodbile_quiz', '','Quiz not found');
        }

        $context = get_context_instance(CONTEXT_MODULE, $cm->id);

        self::validate_context($context);

        $questions = quiz_db::moodbile_get_quiz_questions($quizid);

        $withcategories = 'withcategories';
        $withcontexts = 'withcontexts';
        $qformat = new mbl_qformat_qti_two();
        $exporttext = $qformat->exportprocess($questions, $withcategories, $withcontexts);
        $file = export_to_file($exporttext, $quizid, 'qti');

        return array('fileid' => $file->get_id());
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function export_quiz_to_qti_returns() {
        return new external_single_structure(
            array(
                'fileid' => new external_value(PARAM_INT, 'id of the qti file containing the exported questions', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function answer_quiz_parameters() {
        return new external_function_parameters (
            array(
                'quizid'    => new external_value(PARAM_INT,  'A quiz Id ', VALUE_REQUIRED, 0, NULL_NOT_ALLOWED),
                'answers'   => new external_multiple_structure(
                                    new external_single_structure(
                                        array(
                                            'answerid'  => new external_value(PARAM_INT,  'file id', VALUE_REQUIRED),
                                            'answer'    => new external_value(PARAM_RAW,  'answer', VALUE_OPTIONAL)
                                        )
                                    ), 'quiz answers', VALUE_REQUIRED)
            )
        );
    }

    /**
     * Receives the answers to a quiz
     *
     * @param int quizid
     * @param array of answers
     *
     * @return bool success
     */
    public static function answer_quiz($quizid, $answers) {
        global $MBL;

        if (!$cm = get_coursemodule_from_instance('quiz', $quizid)) {
            throw new moodle_exception('generalexceptionmessage','moodbile_quiz', '','Quiz not found');
        }

        $context = get_context_instance(CONTEXT_MODULE, $cm->id);

        self::validate_context($context);

        return array('success' => true);
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function answer_quiz_returns() {
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'The result of the "answer_quiz" operation', VALUE_REQUIRED, '', NULL_NOT_ALLOWED)
            )
        );
    }
}
