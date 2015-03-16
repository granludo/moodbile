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
 * OAuth Token Authorization
 *
 * @package MoodbileServer
 * @subpackage OAuth
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

require_once(dirname(__FILE__) . '/../config.php');
require_once ($MBL->mblroot.'/lib/oauth-php/library/OAuthServer.php');
require_once ($MBL->mblroot.'/lib/oauth-php/library/OAuthStore.php');

$authorize = optional_param('authorize', null, PARAM_TEXT);
$reject = optional_param('reject', null, PARAM_TEXT);

$store = OAuthStore::instance('Moodle');


$URL = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
    $URL .= "s";
}
$URL .= "://" .$_SERVER["SERVER_NAME"];
if ($_SERVER["SERVER_PORT"] != "80") {
    $URL .= ":".$_SERVER["SERVER_PORT"];
}
$URL .= $_SERVER["REQUEST_URI"];

if (!isset($SESSION->loggedin)){
    header('Location: '.$CFG->wwwroot.'/login/index.php?oauthgoto=' . urlencode($URL));
    exit();
}

// TODO : Check previous authorization
if (!isset($authorize) && !isset($reject)){
    header('Location: '.$CFG->wwwroot.'/local/moodbileserver/oauth/authorize.php?oauthgoto=' . urlencode($URL));
    exit();
}

if (isset($authorize)){
    $authorize = true;
} else {
    $authorize = false;
}


try {
    $server = new OAuthServer();
    $server->authorizeVerify();
    $server->authorizeFinish($authorize, $USER->id);
} catch (OAuthException2 $e) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: text/plain');

    echo "Failed OAuth Request: " . $e->getMessage();
}

exit;
