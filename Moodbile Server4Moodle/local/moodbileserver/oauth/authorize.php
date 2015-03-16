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
 * OAuth Authorize
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

require_once('../config.php');
require_once ($MBL->mblroot.'/lib/oauth-php/library/OAuthServer.php');
require_once ($MBL->mblroot.'/lib/oauth-php/library/OAuthStore.php');

$oauthgoto = required_param('oauthgoto', PARAM_URL);

$store = OAuthStore::instance('Moodle');

require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
$course = $DB->get_record('course', array('id' => SITEID));

$PAGE->set_url('/local/moodbile/oauth/authorize.php');
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->fullname);

$url = explode('?', $oauthgoto);
parse_str($url[1], $params);
$token = $params['oauth_token'];
$requestoken = $store->getConsumerRequestToken($token);
$consumer = $store->getConsumer($requestoken['consumer_key'], 0);

echo $OUTPUT->header();

echo $OUTPUT->box_start();

echo '<h1>'.get_string('authorizationrequest', 'local_moodbileserver').'</h1>';
echo '<p><strong>'. $consumer['application_title'] .'</strong> ' .get_string('authorizationdesc', 'local_moodbileserver'). '</p>';
echo '<form method="POST" action="'.$oauthgoto.'">';
echo '<div style="text-align: center">';
echo '<input style="margin: 10px;" type="submit" name="authorize" value="'.get_string('authorize', 'local_moodbileserver').'" />';
echo '<input style="magin: 10px;" type="submit" name="reject" value="'.get_string('reject', 'local_moodbileserver').'" />';
echo '</div>';
echo '</form>';
echo $OUTPUT->box_end();

echo $OUTPUT->footer();