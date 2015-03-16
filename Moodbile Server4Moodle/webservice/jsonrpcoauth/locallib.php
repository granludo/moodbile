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
 * Moodbile JSON-RPC 2.0 Connector using OAuth autentication
 *
 * @package MoodbileServer
 * @subpackage jsonrpcoauth
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
require_once($MBL->mblroot . '/lib/oauth-php/library/OAuthServer.php');
require_once($MBL->mblroot . '/lib/oauth-php/library/OAuthStore.php');
require_once($CFG->dirroot . '/webservice/jsonrpc/locallib.php');
/**
 * JSON service server implementation.
 * @author Nikolas Galanis
 */
class webservice_json_rpc_oauth_server extends webservice_json_rpc_server {
    /**
     * Contructor
     */
    public function __construct($authmethod) {
        require_once 'Zend/Json/Server.php';
        parent::__construct($authmethod, 'Zend_Json_RPC_Server');
        $this->wsname = 'jsonrpcoauth';
    }


    protected function authenticate_user() {
        global $CFG, $MBL;

        $store = OAuthStore::instance('Moodle');

        if (OAuthRequestVerifier::requestIsSigned()){
            try {
                $req = new OAuthRequestVerifier();
                $userid = $req->verify();

                // If we have a user_id, then login as that user (for this request)
                if ($userid) {
                    $user = $MBL->DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
                    session_set_user($user);
                    $this->userid = $user->id;

                    external_api::set_context_restriction($this->restricted_context);

                }
            } catch (OAuthException $e) {
                // The request was signed, but failed verification
                throw new moodle_exception('generalexceptionmessage', 'error', '', $e->getMessage());
            }
        } else {
            throw new moodle_exception('generalexceptionmessage', 'error', '', 'Request must be signed!');
        }

        $data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], TRUE);

        if (!isset($data['jsonrpc']) || ($data['jsonrpc'] != "2.0")) {
            throw new moodle_exception('generalexceptionmessage', 'error', '', 'Invalid JSON-rpc version!');
        }

        $this->id = isset($data['id']) ? $data['id'] : null;

        $this->functionname = isset($data['method']) ? $data['method'] : null;

        $this->parameters = $data['params'];
        $this->simple = true;

    }

    /**
     * This method parses the $_REQUEST superglobal and looks for
     * the following information:
     *  1/ user authentication - username+password or token (wsusername, wspassword and wstoken parameters)
     *
     * @return void
     */
    protected function parse_request() {
        // DO NOTHING BEFORE VERIFING OAUTH REQUEST
    }

}
