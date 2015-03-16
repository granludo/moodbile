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
// along with Foobar.  If not, see <http://www.gnu.org/licenses/>.


/**
 * JSON AJAX client
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
class webservice_jsonajax_client {

    private $serverurl;
    private $token;

    /**
     * Constructor
     * @param string $serverurl a Moodle URL
     * @param string $token
     */
    public function __construct($serverurl, $token) {
        $this->serverurl = $serverurl;
        $this->token = $token;
    }

    /**
     * Set the token used to do the REST call
     * @param string $token
     */
    public function set_token($token) {
        $this->token = $token;
    }

    /**
     * Execute client WS request with token authentication
     * @param string $functionname
     * @param array $params
     * @return mixed
     */
    public function call($functionname, $params) {
        global $DB, $CFG;

        $result = null;

        //TODO : transform the XML result into PHP values - MDL-22965

        return $result;
    }

}