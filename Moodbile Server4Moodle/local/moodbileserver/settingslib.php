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
global $MBL;
global $CFG;
require_once($CFG->libdir.'/adminlib.php');
require_once(dirname(__FILE__).'/lib.php');

/**
 * Special checkbox for enabling/disabling moodbile web services
 *
 */
class admin_setting_enablemoodbileservice extends admin_setting_configcheckbox {

    private $jsonajaxuse; //boolean: true => capability 'webservice/jsonajax:use' is set for authenticated user role
    private $jsonrpcoauthuse; //boolean: true => capability 'webservice/jsonrpcoauth:use' is set for authenticated user role
    private $jsonrpcuse; //boolean: true => capability 'webservice/jsonrpc:use' is set for authenticated user role


    /**
     * Return true if Authenticated user role has the capability 'webservice/jsonajax:use', otherwise false
     * @return boolean
     */
    private function is_jsonajax_cap_allowed() {
        global $DB, $CFG;

        //if the $this->jsonajaxuse variable is not set, it needs to be set
        if (empty($this->jsonajaxuse) and $this->jsonajaxuse !== false) {
            $params = array();
            $params['permission'] = CAP_ALLOW;
            $params['roleid'] = $CFG->defaultuserroleid;
            $params['capability'] = 'webservice/jsonajax:use';
            $this->jsonajaxuse = $DB->record_exists('role_capabilities', $params);
        }

        return $this->jsonajaxuse;
    }

    /**
     * Set the 'webservice/jsonajax:use' to the Authenticated user role (allow or not)
     * @param type $status true to allow, false to not set
     */
    private function set_jsonajax_cap($status) {
        global $CFG;
        if ($status and !$this->is_jsonajax_cap_allowed()) {
            //need to allow the cap
            $permission = CAP_ALLOW;
            $assign = true;
        } else if (!$status and $this->is_jsonajax_cap_allowed()){
            //need to disallow the cap
            $permission = CAP_INHERIT;
            $assign = true;
        }
        if (!empty($assign)) {
            $systemcontext = get_system_context();
            assign_capability('webservice/jsonajax:use', $permission, $CFG->defaultuserroleid, $systemcontext->id, true);
        }
    }


    /**
     * Return true if Authenticated user role has the capability 'webservice/jsonrpcoauth:use', otherwise false
     * @return boolean
     */
    private function is_jsonrpcoauth_cap_allowed() {
        global $DB, $CFG;

        //if the $this->jsonrpcoauthuse variable is not set, it needs to be set
        if (empty($this->jsonrpcoauthuse) and $this->jsonrpcoauthuse!==false) {
            $params = array();
            $params['permission'] = CAP_ALLOW;
            $params['roleid'] = $CFG->defaultuserroleid;
            $params['capability'] = 'webservice/jsonrpcoauth:use';
            $this->jsonrpcoauthuse = $DB->record_exists('role_capabilities', $params);
        }

        return $this->jsonrpcoauthuse;
    }

    /**
     * Set the 'webservice/jsonrpcoauth:use' to the Authenticated user role (allow or not)
     * @param type $status true to allow, false to not set
     */
    private function set_jsonrpcoauth_cap($status) {
        global $CFG;
        if ($status and !$this->is_jsonrpcoauth_cap_allowed()) {
            //need to allow the cap
            $permission = CAP_ALLOW;
            $assign = true;
        } else if (!$status and $this->is_jsonrpcoauth_cap_allowed()){
            //need to disallow the cap
            $permission = CAP_INHERIT;
            $assign = true;
        }
        if (!empty($assign)) {
            $systemcontext = get_system_context();
            assign_capability('webservice/jsonrpcoauth:use', $permission, $CFG->defaultuserroleid, $systemcontext->id, true);
        }
    }

    /**
     * Return true if Authenticated user role has the capability 'webservice/jsonrpc:use', otherwise false
     * @return boolean
     */
    private function is_jsonrpc_cap_allowed() {
        global $DB, $CFG;

        //if the $this->jsonrpcoauthuse variable is not set, it needs to be set
        if (empty($this->jsonrpcuse) and $this->jsonrpcuse!==false) {
            $params = array();
            $params['permission'] = CAP_ALLOW;
            $params['roleid'] = $CFG->defaultuserroleid;
            $params['capability'] = 'webservice/jsonrpc:use';
            $this->jsonrpcuse = $DB->record_exists('role_capabilities', $params);
        }

        return $this->jsonrpcuse;
    }

    /**
     * Set the 'webservice/jsonrpc:use' to the Authenticated user role (allow or not)
     * @param type $status true to allow, false to not set
     */
    private function set_jsonrpc_cap($status) {
        global $CFG;
        if ($status and !$this->is_jsonrpc_cap_allowed()) {
            //need to allow the cap
            $permission = CAP_ALLOW;
            $assign = true;
        } else if (!$status and $this->is_jsonrpc_cap_allowed()){
            //need to disallow the cap
            $permission = CAP_INHERIT;
            $assign = true;
        }
        if (!empty($assign)) {
            $systemcontext = get_system_context();
            assign_capability('webservice/jsonrpc:use', $permission, $CFG->defaultuserroleid, $systemcontext->id, true);
        }
    }
    /**
     * Builds XHTML to display the control.
     * The main purpose of this overloading is to display a warning when https
     * is not supported by the server
     * @param string $data Unused
     * @param string $query
     * @return string XHTML
     */
    public function output_html($data, $query='') {
        global $CFG, $OUTPUT;
        $html = parent::output_html($data, $query);

        return $html;
    }

    /**
     * Retrieves the current setting using the objects name
     *
     * @return string
     */
    public function get_setting() {
        global $CFG;

        // For install cli script, $CFG->defaultuserroleid is not set so return 0
        // Or if web services aren't enabled this can't be,
        if (empty($CFG->defaultuserroleid) || empty($CFG->enablewebservices)) {
            return 0;
        }

        require_once($CFG->dirroot . '/webservice/lib.php');
        $webservicemanager = new webservice();
        $mobileservice = $this->get_external_service();
        if ($mobileservice->enabled and $this->is_jsonajax_cap_allowed() and $this->is_jsonrpcoauth_cap_allowed() and $this->is_jsonrpc_cap_allowed()) {
            return $this->config_read($this->name); //same as returning 1
        } else {
            return 0;
        }
    }


    /**
     * Save the selected setting
     *
     * @param string $data The selected site
     * @return string empty string or error message
     */
    public function write_setting($data) {
        global $DB, $CFG;

        //for install cli script, $CFG->defaultuserroleid is not set so do nothing
        if (empty($CFG->defaultuserroleid)) {
            return '';
        }

        $servicename = MOODLE_MOODBILE_SERVICE;

        require_once($CFG->dirroot . '/webservice/lib.php');
        $webservicemanager = new webservice();

        if ((string)$data === $this->yes) {
            //code run when enabling moodbile web service
            //enable web service system if necessary
            set_config('enablewebservices', true);

            //enable moodbile service
            $mobileservice = $this->get_external_service();
            $mobileservice->enabled = 1;
            $webservicemanager->update_external_service($mobileservice);

            // Add an entry in mdl_config containing the servicename and the external service id

            //enable json-ajax server
            $activeprotocols = empty($CFG->webserviceprotocols) ? array() : explode(',', $CFG->webserviceprotocols);

            if (!in_array('jsonajax', $activeprotocols)) {
                $activeprotocols[] = 'jsonajax';
                set_config('webserviceprotocols', implode(',', $activeprotocols));
            }

            //allow json-ajax:use capability for authenticated user
            $this->set_jsonajax_cap(true);

            //enable json-rpc oAuth server
            $activeprotocols = empty($CFG->webserviceprotocols) ? array() : explode(',', $CFG->webserviceprotocols);

            if (!in_array('jsonrpcoauth', $activeprotocols)) {
                $activeprotocols[] = 'jsonrpcoauth';
                set_config('webserviceprotocols', implode(',', $activeprotocols));
            }

            //allow json-rpc-oauth:use capability for authenticated user
            $this->set_jsonrpcoauth_cap(true);

            if (!in_array('jsonrpc', $activeprotocols)) {
                $activeprotocols[] = 'jsonrpc';
                set_config('webserviceprotocols', implode(',', $activeprotocols));
            }

            //allow json-rpc-oauth:use capability for authenticated user
            $this->set_jsonrpc_cap(true);

            //enable authentication plugins
            get_enabled_auth_plugins(true); // fix the list of enabled auths
            if (empty($CFG->auth)) {
                $authsenabled = array();
            } else {
                $authsenabled = explode(',', $CFG->auth);
            }

            //enable Web Services authentication plugin
            // add to enabled list
            $auth = 'webservice';
            if (!in_array($auth, $authsenabled)) {
                $authsenabled[] = $auth;
                $authsenabled = array_unique($authsenabled);
                set_config('auth', implode(',', $authsenabled));
            }

            //enable OAuth authentication plugin
            $auth = 'oauth';
            if (!in_array($auth, $authsenabled)) {
                $authsenabled[] = $auth;
                $authsenabled = array_unique($authsenabled);
                set_config('auth', implode(',', $authsenabled));
            }

            //enable Moodbile authentication plugin
            $auth = 'moodbile';
            if (!in_array($auth, $authsenabled)) {
                $authsenabled[] = $auth;
                $authsenabled = array_unique($authsenabled);
                set_config('auth', implode(',', $authsenabled));
            }

            session_gc(); // remove stale sessions

        } else {
            //disable web service system if no other services are enabled
            $otherenabledservices = $DB->get_records_select('external_services',
                    'enabled = :enabled AND (component != :componentname OR component IS NULL)', array('enabled' => 1,
                        'componentname' => 'local_moodbileserver'));
            if (empty($otherenabledservices)) {
                set_config('enablewebservices', false);
                 //also disable json-ajax server
                $activeprotocols = empty($CFG->webserviceprotocols) ? array() : explode(',', $CFG->webserviceprotocols);
                $protocolkey = array_search('jsonajax', $activeprotocols);
                if ($protocolkey !== false) {
                   unset($activeprotocols[$protocolkey]);
                   set_config('webserviceprotocols', implode(',', $activeprotocols));
                }

                //disallow json-ajax:use capability for authenticated user
                $this->set_jsonajax_cap(false);
                //also disable json-rpc-oAuth server
                $activeprotocols = empty($CFG->webserviceprotocols) ? array() : explode(',', $CFG->webserviceprotocols);
                $protocolkey = array_search('jsonrpcoauth', $activeprotocols);
                if ($protocolkey !== false) {
                   unset($activeprotocols[$protocolkey]);
                   set_config('webserviceprotocols', implode(',', $activeprotocols));
                }

                //disallow json-rpc-oAuth:use capability for authenticated user
                $this->set_jsonrpcoauth_cap(false);

                //also disable json-rpc server
                $activeprotocols = empty($CFG->webserviceprotocols) ? array() : explode(',', $CFG->webserviceprotocols);
                $protocolkey = array_search('jsonrpc', $activeprotocols);
                if ($protocolkey !== false) {
                   unset($activeprotocols[$protocolkey]);
                   set_config('webserviceprotocols', implode(',', $activeprotocols));
                }

                //disallow json-rpc-oAuth:use capability for authenticated user
                $this->set_jsonrpc_cap(false);
            }

            //disable the mobile service
            $mobileservice = $this->get_external_service();
            $mobileservice->enabled = 0;
            $webservicemanager->update_external_service($mobileservice);

            // remove moodbile from enabled authentication plugins list

            //get authentication plugins
            get_enabled_auth_plugins(true); // fix the list of enabled auths
            if (empty($CFG->auth)) {
                $authsenabled = array();
            } else {
                $authsenabled = explode(',', $CFG->auth);
            }

            $auth = 'moodbile';
            $key = array_search($auth, $authsenabled);
            if ($key !== false) {
                unset($authsenabled[$key]);
                set_config('auth', implode(',', $authsenabled));
            }

            if ($auth == $CFG->registerauth) {
                set_config('registerauth', '');
            }
            session_gc(); // remove stale sessions
        }

        return (parent::write_setting($data));
    }

    /**
     * Get an external service for a given shortname
     * @param service shortname $shortname
     * @param integer $strictness IGNORE_MISSING, MUST_EXIST...
     * @return object external service
     */
    public function get_external_service($strictness=IGNORE_MISSING) {
        global $DB;
        $service = $DB->get_record('external_services',
                        array('component' => 'local_moodbileserver'), '*', $strictness);
        return $service;
    }
}
