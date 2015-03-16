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

require_once("$CFG->dirroot/webservice/lib.php");

/**
 * JSON-P service server implementation.
 * @author Nikolas Galanis
 */
class webservice_jsonajax_server extends webservice_base_server {

    protected $callback;
    protected $simple;
    /**
     * Contructor
     */
    public function __construct($authmethod) {

        require_once 'Zend/Json/Server.php';
        parent::__construct($authmethod, 'Zend_Json_Ajax_Server');
        $this->wsname = 'jsonajax';
    }

    /**
     * Set up zend service class
     * @return void
     */
    protected function init_zend_server() {
        parent::init_zend_server();
        // this exception indicates request failed
        Zend_Json_Server_Fault::attachFaultException('moodle_exception');
    }

    protected function authenticate_user() {
        global $USER;
        if ($USER->id == 0){
            throw new moodle_exception('generalexceptionmessage', 'error', '', 'Must be logged in');
        }
        session_set_user($USER);
        $this->userid = $USER->id;

        external_api::set_context_restriction($this->restricted_context);
    }

    /**
     * This method parses the $_REQUEST superglobal and looks for
     * the following information:
     *  1/ user authentication - username+password or token (wsusername, wspassword and wstoken parameters)
     *
     * @return void
     */
    protected function parse_request() {
        if (!isset($_REQUEST['request'])){
            throw new moodle_exception('generalexceptionmessage', 'error', '', 'Parameter \'request\' not found');
        }

        $data = json_decode(urldecode($_REQUEST['request']), TRUE);

        $this->functionname = isset($data['wsfunction']) ? $data['wsfunction'] : null;
        unset($data['wsfunction']);

        $this->parameters = $data;
        $this->simple = true;
    }

    protected function send_response() {
        //$returndata = self::filter_data($this->returns, $this->function->returns_desc);

        //$json =  json_encode($returndata) ;
        $json =  json_encode($this->returns) ;
        echo $json;
    }

    protected function send_error($ex=null) {
        $json = json_encode($ex);
        echo $json;
    }

    protected static function filter_data($returns, $desc) {

        if ($desc === null) {
            return '';
        } else if ($desc instanceof external_multiple_structure) {
             $endresult = array();
            if (!empty($returns)) {
                foreach ($returns as $val) {
                    $result = self::filter_data($val, $desc->content);
                    $endresult[] = $result;
                }
            }

            return $endresult;
        }
        else if ($desc instanceof external_single_structure) {
            return self::filter_data($returns, $desc->keys);
        }
        else if (is_array($desc)) { // It's an array
            $returnarray = array();
            foreach ($desc as $key=>$val) {
                foreach ($returns as $k=>$v) {
                    if ($k == $key) {
                        $returnarray[$key] = self::filter_data($v, $val);
                    }
                }
            }

            return $returnarray;
        }
        else {
            return $returns;
        }
    }
}
