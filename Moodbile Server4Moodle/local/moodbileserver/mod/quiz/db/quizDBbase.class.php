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
 * Quiz DataBase base Class
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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

class quiz_db_base {

    /**
     * Return all questions from a quiz
     *
     * @global object
     * @global object
     * @param  int $quizid
     *
     * @return a list of questions
     */
    public static function moodbile_get_quiz_questions($quizid) {
        global $CFG, $MBL, $QTYPES;

        $params = array();
        $params['quizid'] = $quizid;


        $sql = "SELECT q.questions
                    FROM {quiz} q
                    WHERE q.id = :quizid";

        $questionnumbers = $MBL->DB->get_record_sql($sql, $params);
        $questionlist = quiz_questions_in_quiz($questionnumbers->questions);
        if (empty($questionlist)) {
            return array();
        }

        $wheresql = " id IN ($questionlist)";

        $qsql = "SELECT *
                    FROM {question}
                    WHERE $wheresql";

        $questions = $MBL->DB->get_records_sql($qsql);

        $qresults = array();

        // iterate through questions, getting stuff we need
        foreach($questions as $question) {
            $questiontype = $QTYPES[$question->qtype];
            $question->export_process = true;
            $questiontype->get_question_options($question);
            $qresults[] = $question;
        }

        return $qresults;
    }
}

