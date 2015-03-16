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
 * Quiz XML Question format class
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
require_once($CFG->dirroot.'/question/format/xml/format.php');

class mbl_qformat_xml extends qformat_xml {

    /**
     * Do the export
     * For most types this should not need to be overrided
     * @return stored_file
     */
    function exportprocess($questions, $withcategories = null, $withcontexts = null) {
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
            if (isset($withcategories)) {
                if ($question->category != $trackcategory) {
                    $trackcategory = $question->category;
                    $categoryname = $this->get_catexagory_path($trackcategory, (isset($withcontexts) ? true : false));

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


    /**
     * Turns question into an xml segment
     * @param object question object
     * @param int context id
     * @return string xml segment
     */
    function writequestion($question) {
        global $CFG, $QTYPES, $OUTPUT;

        $fs = get_file_storage();
        $contextid = $question->contextid;
        // initial string;
        $expout = "";

        // add comment
        $expout .= "\n\n<!-- question: $question->id  -->\n";

        // check question type
        if (!$question_type = $this->get_qtype( $question->qtype )) {
            // must be a plugin then, so just accept the name supplied
            $question_type = $question->qtype;
        }

        // add opening tag
        // generates specific header for Cloze and category type question
        if ($question->qtype == 'category') {
            $categorypath = $this->writetext( $question->category );
            $expout .= "  <question type=\"category\">\n";
            $expout .= "    <category>\n";
            $expout .= "        $categorypath\n";
            $expout .= "    </category>\n";
            $expout .= "  </question>\n";
            return $expout;
        } elseif ($question->qtype != MULTIANSWER) {
            // for all question types except Close
            $name_text = $this->writetext($question->name);
            $qtformat = $this->get_format($question->questiontextformat);
            $generalfeedbackformat = $this->get_format($question->generalfeedbackformat);

            $question_text = $this->writetext($question->questiontext);
            $question_text_files = $this->write_files($question->questiontextfiles);

            $generalfeedback = $this->writetext($question->generalfeedback);
            $generalfeedback_files = $this->write_files($question->generalfeedbackfiles);

            $expout .= "  <question type=\"$question_type\">\n";
            $expout .= "    <id>$question->id</id>\n";
            $expout .= "    <name>$name_text</name>\n";
            $expout .= "    <questiontext format=\"$qtformat\">\n";
            $expout .= $question_text;
            $expout .= $question_text_files;
            $expout .= "    </questiontext>\n";
            $expout .= "    <generalfeedback format=\"$generalfeedbackformat\">\n";
            $expout .= $generalfeedback;
            $expout .= $generalfeedback_files;
            $expout .= "    </generalfeedback>\n";
            $expout .= "    <defaultgrade>{$question->defaultgrade}</defaultgrade>\n";
            $expout .= "    <penalty>{$question->penalty}</penalty>\n";
            $expout .= "    <hidden>{$question->hidden}</hidden>\n";
        } else {
            // for Cloze type only
            $name_text = $this->writetext( $question->name );
            $question_text = $this->writetext( $question->questiontext );
            $generalfeedback = $this->writetext( $question->generalfeedback );
            $expout .= "  <question type=\"$question_type\">\n";
            $expout .= "    <id>$question->id</id>\n";
            $expout .= "    <name>$name_text</name>\n";
            $expout .= "    <questiontext>\n";
            $expout .= $question_text;
            $expout .= "    </questiontext>\n";
            $expout .= "    <generalfeedback>\n";
            $expout .= $generalfeedback;
            $expout .= "    </generalfeedback>\n";
        }

        if (!empty($question->options->shuffleanswers)) {
            $expout .= "    <shuffleanswers>{$question->options->shuffleanswers}</shuffleanswers>\n";
        }
        else {
            $expout .= "    <shuffleanswers>0</shuffleanswers>\n";
        }

        // output depends on question type
        switch($question->qtype) {
        case 'category':
            // not a qtype really - dummy used for category switching
            break;
        case TRUEFALSE:
            foreach ($question->options->answers as $answer) {
                $fraction_pc = round( $answer->fraction * 100 );
                if ($answer->id == $question->options->trueanswer) {
                    $answertext = 'true';
                } else {
                    $answertext = 'false';
                }
                $expout .= "    <answer>\n";
                $expout .= "      <answerid>$answer->id</answerid>\n";
                $expout .= $this->writetext($answertext, 3) . "\n";
                $expout .= "      <fraction>$answer->fraction</fraction>\n";
                $feedbackformat = $this->get_format($answer->feedbackformat);
                $expout .= "      <feedback format=\"$feedbackformat\">\n";
                $expout .= $this->writetext($answer->feedback,4,false);
                $expout .= $this->write_files($answer->feedbackfiles);
                $expout .= "      </feedback>\n";
                $expout .= "    </answer>\n";
            }
            break;
        case MULTICHOICE:
            $expout .= "    <single>".$this->get_single($question->options->single)."</single>\n";
            $expout .= "    <shuffleanswers>".$this->get_single($question->options->shuffleanswers)."</shuffleanswers>\n";

            $textformat = $this->get_format($question->options->correctfeedbackformat);
            $files = $fs->get_area_files($contextid, 'qtype_multichoice', 'correctfeedback', $question->id);
            $expout .= "    <correctfeedback format=\"$textformat\">\n";
            $expout .= $this->writetext($question->options->correctfeedback, 3);
            $expout .= $this->write_files($files);
            $expout .= "    </correctfeedback>\n";

            $textformat = $this->get_format($question->options->partiallycorrectfeedbackformat);
            $files = $fs->get_area_files($contextid, 'qtype_multichoice', 'partiallycorrectfeedback', $question->id);
            $expout .= "    <partiallycorrectfeedback format=\"$textformat\">\n";
            $expout .= $this->writetext($question->options->partiallycorrectfeedback, 3);
            $expout .= $this->write_files($files);
            $expout .= "    </partiallycorrectfeedback>\n";

            $textformat = $this->get_format($question->options->incorrectfeedbackformat);
            $files = $fs->get_area_files($contextid, 'qtype_multichoice', 'incorrectfeedback', $question->id);
            $expout .= "    <incorrectfeedback format=\"$textformat\">\n";
            $expout .= $this->writetext($question->options->incorrectfeedback, 3);
            $expout .= $this->write_files($files);
            $expout .= "    </incorrectfeedback>\n";

            $expout .= "    <answernumbering>{$question->options->answernumbering}</answernumbering>\n";
            foreach($question->options->answers as $answer) {
                $expout .= "      <answer>\n";
                $expout .= "      <answerid>$answer->id</answerid>\n";
                $expout .= $this->writetext($answer->answer,4,false);
                $feedbackformat = $this->get_format($answer->feedbackformat);
                $expout .= "      <feedback format=\"$feedbackformat\">\n";
                $expout .= $this->writetext($answer->feedback,5,false);
                $expout .= $this->write_files($answer->feedbackfiles);
                $expout .= "      </feedback>\n";
                $expout .= "    </answer>\n";
                }
            break;
        case SHORTANSWER:
            $expout .= "    <usecase>{$question->options->usecase}</usecase>\n ";
            foreach($question->options->answers as $answer) {
                $expout .= "    <answer>\n";
                $expout .= "      <answerid>$answer->id</answerid>\n";
                $expout .= $this->writetext( $answer->answer,3,false );
                $feedbackformat = $this->get_format($answer->feedbackformat);
                $expout .= "      <feedback format=\"$feedbackformat\">\n";
                $expout .= $this->writetext($answer->feedback);
                $expout .= $this->write_files($answer->feedbackfiles);
                $expout .= "      </feedback>\n";
                $expout .= "    </answer>\n";
            }
            break;
        case NUMERICAL:
            foreach ($question->options->answers as $answer) {
                $tolerance = $answer->tolerance;
                $expout .= "<answer>\n";
                $expout .= "      <answerid>$answer->id</answerid>\n";
                // <text> tags are an added feature, old filed won't have them
                $expout .= "    <text>{$answer->answer}</text>\n";
                $expout .= "    <tolerance>$tolerance</tolerance>\n";
                $feedbackformat = $this->get_format($answer->feedbackformat);
                $expout .= "    <feedback format=\"$feedbackformat\">\n";
                $expout .= $this->writetext($answer->feedback);
                $expout .= $this->write_files($answer->feedbackfiles);
                $expout .= "    </feedback>\n";
                $expout .= "</answer>\n";
            }

            $units = $question->options->units;
            if (count($units)) {
                $expout .= "<units>\n";
                foreach ($units as $unit) {
                    $expout .= "  <unit>\n";
                    $expout .= "    <multiplier>{$unit->multiplier}</multiplier>\n";
                    $expout .= "    <unit_name>{$unit->unit}</unit_name>\n";
                    $expout .= "  </unit>\n";
                }
                $expout .= "</units>\n";
            }
            if (isset($question->options->unitgradingtype)) {
                $expout .= "    <unitgradingtype>{$question->options->unitgradingtype}</unitgradingtype>\n";
            }
            if (isset($question->options->unitpenalty)) {
                $expout .= "    <unitpenalty>{$question->options->unitpenalty}</unitpenalty>\n";
            }
            if (isset($question->options->showunits)) {
                $expout .= "    <showunits>{$question->options->showunits}</showunits>\n";
            }
            if (isset($question->options->unitsleft)) {
                $expout .= "    <unitsleft>{$question->options->unitsleft}</unitsleft>\n";
            }
            if (!empty($question->options->instructionsformat)) {
                $textformat = $this->get_format($question->options->instructionsformat);
                $files = $fs->get_area_files($contextid, 'qtype_numerical', 'instruction', $question->id);
                $expout .= "    <instructions format=\"$textformat\">\n";
                $expout .= $this->writetext($question->options->instructions, 3);
                $expout .= $this->write_files($files);
                $expout .= "    </instructions>\n";
            }
            break;
        case MATCH:
            foreach($question->options->subquestions as $subquestion) {
                $files = $fs->get_area_files($contextid, 'qtype_match', 'subquestion', $subquestion->id);
                $textformat = $this->get_format($subquestion->questiontextformat);
                $expout .= "<subquestion format=\"$textformat\">\n";
                $expout .= $this->writetext($subquestion->questiontext);
                $expout .= $this->write_files($files);
                $expout .= "<answer>";
                $expout .= $this->writetext($subquestion->answertext);
                $expout .= "</answer>\n";
                $expout .= "</subquestion>\n";
            }
            break;
        case DESCRIPTION:
            // nothing more to do for this type
            break;
        case MULTIANSWER:
            $a_count=1;
            foreach($question->options->questions as $question) {
                $thispattern = preg_quote("{#".$a_count."}"); //TODO: is this really necessary?
                $thisreplace = $question->questiontext;
                $expout=preg_replace("~$thispattern~", $thisreplace, $expout );
                $a_count++;
            }
        break;
        case ESSAY:
            if (!empty($question->options->answers)) {
                foreach ($question->options->answers as $answer) {
                    $expout .= "<answer>\n";
                    $expout .= "      <answerid>$answer->id</answerid>\n";
                    $feedbackformat = $this->get_format($answer->feedbackformat);
                    $expout .= "    <feedback format=\"$feedbackformat\">\n";
                    $expout .= $this->writetext($answer->feedback);
                    $expout .= $this->write_files($answer->feedbackfiles);
                    $expout .= "    </feedback>\n";
                    $expout .= "</answer>\n";
                }
            }
            break;
        case CALCULATED:
        case CALCULATEDSIMPLE:
        case CALCULATEDMULTI:
            $expout .= "    <synchronize>{$question->options->synchronize}</synchronize>\n";
            $expout .= "    <single>{$question->options->single}</single>\n";
            $expout .= "    <answernumbering>{$question->options->answernumbering}</answernumbering>\n";
            $expout .= "    <shuffleanswers>".$this->writetext($question->options->shuffleanswers, 3)."</shuffleanswers>\n";

            $component = 'qtype_' . $question->qtype;
            $files = $fs->get_area_files($contextid, $component, 'correctfeedback', $question->id);
            $expout .= "    <correctfeedback>\n";
            $expout .= $this->writetext($question->options->correctfeedback, 3);
            $expout .= $this->write_files($files);
            $expout .= "    </correctfeedback>\n";

            $files = $fs->get_area_files($contextid, $component, 'partiallycorrectfeedback', $question->id);
            $expout .= "    <partiallycorrectfeedback>\n";
            $expout .= $this->writetext($question->options->partiallycorrectfeedback, 3);
            $expout .= $this->write_files($files);
            $expout .= "    </partiallycorrectfeedback>\n";

            $files = $fs->get_area_files($contextid, $component, 'incorrectfeedback', $question->id);
            $expout .= "    <incorrectfeedback>\n";
            $expout .= $this->writetext($question->options->incorrectfeedback, 3);
            $expout .= $this->write_files($files);
            $expout .= "    </incorrectfeedback>\n";

            foreach ($question->options->answers as $answer) {
                $tolerance = $answer->tolerance;
                $tolerancetype = $answer->tolerancetype;
                $correctanswerlength= $answer->correctanswerlength ;
                $correctanswerformat= $answer->correctanswerformat;
                $expout .= "<answer>\n";
                $expout .= "      <answerid>$answer->id</answerid>\n";
                // "<text/>" tags are an added feature, old files won't have them
                $expout .= "    <text>{$answer->answer}</text>\n";
                $expout .= "    <tolerance>$tolerance</tolerance>\n";
                $expout .= "    <tolerancetype>$tolerancetype</tolerancetype>\n";
                $expout .= "    <correctanswerformat>$correctanswerformat</correctanswerformat>\n";
                $expout .= "    <correctanswerlength>$correctanswerlength</correctanswerlength>\n";
                $feedbackformat = $this->get_format($answer->feedbackformat);
                $expout .= "    <feedback format=\"$feedbackformat\">\n";
                $expout .= $this->writetext($answer->feedback);
                $expout .= $this->write_files($answer->feedbackfiles);
                $expout .= "    </feedback>\n";
                $expout .= "</answer>\n";
            }
            if (isset($question->options->unitgradingtype)) {
                $expout .= "    <unitgradingtype>{$question->options->unitgradingtype}</unitgradingtype>\n";
            }
            if (isset($question->options->unitpenalty)) {
                $expout .= "    <unitpenalty>{$question->options->unitpenalty}</unitpenalty>\n";
            }
            if (isset($question->options->showunits)) {
                $expout .= "    <showunits>{$question->options->showunits}</showunits>\n";
            }
            if (isset($question->options->unitsleft)) {
                $expout .= "    <unitsleft>{$question->options->unitsleft}</unitsleft>\n";
            }

            if (isset($question->options->instructionsformat)) {
                $textformat = $this->get_format($question->options->instructionsformat);
                $files = $fs->get_area_files($contextid, $component, 'instruction', $question->id);
                $expout .= "    <instructions format=\"$textformat\">\n";
                $expout .= $this->writetext($question->options->instructions, 3);
                $expout .= $this->write_files($files);
                $expout .= "    </instructions>\n";
            }

            if (isset($question->options->units)) {
                $units = $question->options->units;
                if (count($units)) {
                    $expout .= "<units>\n";
                    foreach ($units as $unit) {
                        $expout .= "  <unit>\n";
                        $expout .= "    <multiplier>{$unit->multiplier}</multiplier>\n";
                        $expout .= "    <unit_name>{$unit->unit}</unit_name>\n";
                        $expout .= "  </unit>\n";
                    }
                    $expout .= "</units>\n";
                }
            }
            //The tag $question->export_process has been set so we get all the data items in the database
            //   from the function $QTYPES['calculated']->get_question_options(&$question);
            //  calculatedsimple defaults to calculated
            if( isset($question->options->datasets)&&count($question->options->datasets)){// there should be
                $expout .= "<dataset_definitions>\n";
                foreach ($question->options->datasets as $def) {
                    $expout .= "<dataset_definition>\n";
                    $expout .= "    <status>".$this->writetext($def->status)."</status>\n";
                    $expout .= "    <name>".$this->writetext($def->name)."</name>\n";
                    if ( $question->qtype == CALCULATED){
                        $expout .= "    <type>calculated</type>\n";
                    }else {
                        $expout .= "    <type>calculatedsimple</type>\n";
                    }
                    $expout .= "    <distribution>".$this->writetext($def->distribution)."</distribution>\n";
                    $expout .= "    <minimum>".$this->writetext($def->minimum)."</minimum>\n";
                    $expout .= "    <maximum>".$this->writetext($def->maximum)."</maximum>\n";
                    $expout .= "    <decimals>".$this->writetext($def->decimals)."</decimals>\n";
                    $expout .= "    <itemcount>$def->itemcount</itemcount>\n";
                    if ($def->itemcount > 0 ) {
                        $expout .= "    <dataset_items>\n";
                        foreach ($def->items as $item ){
                              $expout .= "        <dataset_item>\n";
                              $expout .= "           <number>".$item->itemnumber."</number>\n";
                              $expout .= "           <value>".$item->value."</value>\n";
                              $expout .= "        </dataset_item>\n";
                        }
                        $expout .= "    </dataset_items>\n";
                        $expout .= "    <number_of_items>".$def-> number_of_items."</number_of_items>\n";
                     }
                    $expout .= "</dataset_definition>\n";
                }
                $expout .= "</dataset_definitions>\n";
            }
            break;
        default:
            // try support by optional plugin
            if (!$data = $this->try_exporting_using_qtypes( $question->qtype, $question )) {
                echo $OUTPUT->notification( get_string( 'unsupportedexport','qformat_xml',$QTYPES[$question->qtype]->local_name() ) );
            }
            $expout .= $data;
        }

        // Write the question tags.
        if (!empty($CFG->usetags)) {
            require_once($CFG->dirroot.'/tag/lib.php');
            $tags = tag_get_tags_array('question', $question->id);
            if (!empty($tags)) {
                $expout .= "    <tags>\n";
                foreach ($tags as $tag) {
                    $expout .= "      <tag>" . $this->writetext($tag, 0, true) . "</tag>\n";
                }
                $expout .= "    </tags>\n";
            }
        }

        // close the question tag
        $expout .= "</question>\n";

        return $expout;
    }

}
