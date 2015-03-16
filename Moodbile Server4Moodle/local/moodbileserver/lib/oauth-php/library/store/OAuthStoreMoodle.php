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
 * Moodle database connector
 *
 * @package MoodbileServer
 * @subpackage OAuth
 * @copyright 2010 Maria José Casañ, Marc Alier, Jordi Piguillem, Nikolas Galanis marc.alier@upc.edu
 * @copyright 2010 Universitat Politecnica de Catalunya - Barcelona Tech http://www.upc.edu
 *
 * @author Jordi Piguillem
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once dirname(__FILE__) . '/OAuthStoreSQL.php';


class OAuthStoreMoodle extends OAuthStoreSQL{


    /**
     * Construct the OAuthStoreMySQL.
     * In the options you have to supply either:
     * - server, username, password and database (for a mysql_connect)
     * - conn (for the connection to be used)
     *
     * @param array options
     */
    function __construct ( $options = array() ){
    }

    public function install (){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Find stored credentials for the consumer key and token. Used by an OAuth server
     * when verifying an OAuth request.
     *
     * @param string consumer_key
     * @param string token
     * @param string token_type     false, 'request' or 'access'
     * @exception OAuthException2 when no secrets where found
     * @return array    assoc (consumer_secret, token_secret, osr_id, ost_id, user_id)
     */
    public function getSecretsForVerify ( $consumer_key, $token, $token_type = 'access' ){
        global $MBL;

        $rs = array();
        if ($token_type === false){

            $record = $MBL->DB->get_record_sql('
                        SELECT  osr_id,
                                osr_consumer_key        as consumer_key,
                                osr_consumer_secret     as consumer_secret
                        FROM {mbl_oauth_server_registry}
                        WHERE osr_consumer_key  = :consumerkey
                          AND osr_enabled       = 1
                        ',
                        array('consumerkey'=>$consumer_key));

            if ($record) {
                $rs = (array) $record;
                $rs['token']            = false;
                $rs['token_secret']     = false;
                $rs['user_id']          = false;
                $rs['ost_id']           = false;
            }
        } else {
            $record = $MBL->DB->get_record_sql('
                        SELECT  osr_id,
                                ost_id,
                                ost_usa_id_ref          as user_id,
                                osr_consumer_key        as consumer_key,
                                osr_consumer_secret     as consumer_secret,
                                ost_token               as token,
                                ost_token_secret        as token_secret
                        FROM {mbl_oauth_server_registry}
                                JOIN {mbl_oauth_server_token}
                                ON ost_osr_id_ref = osr_id
                        WHERE ost_token_type    = :token_type
                          AND osr_consumer_key  = :consumer_key
                          AND ost_token         = :token
                          AND osr_enabled       = 1
                          AND ost_token_ttl     >= :now
                        ',
                        array('token_type' => $token_type, 'consumer_key' => $consumer_key, 'token' =>$token, 'now' => date('Y-m-d H:i:s')));

            if (empty($record)) {
                throw new OAuthException2('The consumer_key "'.$consumer_key.'" token "'.$token.'" combination does not exist or is not enabled.');
            }

            $rs = (array) $record;
        }

        return $rs;
    }


    /**
     * Find the server details for signing a request, always looks for an access token.
     * The returned credentials depend on which local user is making the request.
     *
     * The consumer_key must belong to the user or be public (user id is null)
     *
     * For signing we need all of the following:
     *
     * consumer_key         consumer key associated with the server
     * consumer_secret      consumer secret associated with this server
     * token                access token associated with this server
     * token_secret         secret for the access token
     * signature_methods    signing methods supported by the server (array)
     *
     * @todo filter on token type (we should know how and with what to sign this request, and there might be old access tokens)
     * @param string uri    uri of the server
     * @param int user_id   id of the logged on user
     * @param string name   (optional) name of the token (case sensitive)
     * @exception OAuthException2 when no credentials found
     * @return array
     */
    public function getSecretsForSignature ( $uri, $user_id, $name = '' ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Get the token and token secret we obtained from a server.
     *
     * @param string    consumer_key
     * @param string    token
     * @param string    token_type
     * @param int       user_id         the user owning the token
     * @param string    name            optional name for a named token
     * @exception OAuthException2 when no credentials found
     * @return array
     */
    public function getServerTokenSecrets ( $consumer_key, $token, $token_type, $user_id, $name = '' ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Add a request token we obtained from a server.
     *
     * @todo remove old tokens for this user and this ocr_id
     * @param string consumer_key   key of the server in the consumer registry
     * @param string token_type     one of 'request' or 'access'
     * @param string token
     * @param string token_secret
     * @param int    user_id            the user owning the token
     * @param array  options            extra options, name and token_ttl
     * @exception OAuthException2 when server is not known
     * @exception OAuthException2 when we received a duplicate token
     */
    public function addServerToken ( $consumer_key, $token_type, $token, $token_secret, $user_id, $options = array() ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Delete a server key.  This removes access to that site.
     *
     * @param string consumer_key
     * @param int user_id   user registering this server
     * @param boolean user_is_admin
     */
    public function deleteServer ( $consumer_key, $user_id, $user_is_admin = false ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Get a server from the consumer registry using the consumer key
     *
     * @param string consumer_key
     * @param int user_id
     * @param boolean user_is_admin (optional)
     * @exception OAuthException2 when server is not found
     * @return array
     */
    public function getServer ( $consumer_key, $user_id, $user_is_admin = false ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }



    /**
     * Find the server details that might be used for a request
     *
     * The consumer_key must belong to the user or be public (user id is null)
     *
     * @param string uri    uri of the server
     * @param int user_id   id of the logged on user
     * @exception OAuthException2 when no credentials found
     * @return array
     */
    public function getServerForUri ( $uri, $user_id ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Get a list of all server token this user has access to.
     *
     * @param int usr_id
     * @return array
     */
    public function listServerTokens ( $user_id ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Count how many tokens we have for the given server
     *
     * @param string consumer_key
     * @return int
     */
    public function countServerTokens ( $consumer_key ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Get a specific server token for the given user
     *
     * @param string consumer_key
     * @param string token
     * @param int user_id
     * @exception OAuthException2 when no such token found
     * @return array
     */
    public function getServerToken ( $consumer_key, $token, $user_id ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Delete a token we obtained from a server.
     *
     * @param string consumer_key
     * @param string token
     * @param int user_id
     * @param boolean user_is_admin
     */
    public function deleteServerToken ( $consumer_key, $token, $user_id, $user_is_admin = false ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Set the ttl of a server access token.  This is done when the
     * server receives a valid request with a xoauth_token_ttl parameter in it.
     *
     * @param string consumer_key
     * @param string token
     * @param int token_ttl
     */
    public function setServerTokenTtl ( $consumer_key, $token, $token_ttl ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Get a list of all consumers from the consumer registry.
     * The consumer keys belong to the user or are public (user id is null)
     *
     * @param string q  query term
     * @param int user_id
     * @return array
     */
    public function listServers ( $q = '', $user_id ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Register or update a server for our site (we will be the consumer)
     *
     * (This is the registry at the consumers, registering servers ;-) )
     *
     * @param array server
     * @param int user_id   user registering this server
     * @param boolean user_is_admin
     * @exception OAuthException2 when fields are missing or on duplicate consumer_key
     * @return consumer_key
     */
    public function updateServer ( $server, $user_id, $user_is_admin = false ) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Insert/update a new consumer with this server (we will be the server)
     * When this is a new consumer, then also generate the consumer key and secret.
     * Never updates the consumer key and secret.
     * When the id is set, then the key and secret must correspond to the entry
     * being updated.
     *
     * (This is the registry at the server, registering consumers ;-) )
     *
     * @param array consumer
     * @param int user_id   user registering this consumer
     * @param boolean user_is_admin
     * @return string consumer key
     */
    public function updateConsumer ( $consumer, $user_id, $user_is_admin = false ){
        global $MBL;

        if (!$user_is_admin) {
            foreach (array('requester_name', 'requester_email') as $f) {
                if (empty($consumer[$f])) {
                    throw new OAuthException2('The field "'.$f.'" must be set and non empty');
                }
            }
        }

        if (!empty($consumer['id'])) {
            if (empty($consumer['consumer_key'])) {
                throw new OAuthException2('The field "consumer_key" must be set and non empty');
            }
            if (!$user_is_admin && empty($consumer['consumer_secret'])) {
                throw new OAuthException2('The field "consumer_secret" must be set and non empty');
            }

            // Check if the current user can update this server definition
            if (!$user_is_admin) {
                $osr_usa_id_ref = $MBL->DB->get_record_sql('
                                    SELECT osr_usa_id_ref
                                    FROM {mbl_oauth_server_registry}
                                    WHERE osr_id = :consumerid
                                    ', array('consumerid' => $consumer['id']));

                if (!empty($osr_usa_id_ref['osr_usa_id_ref']) && $osr_usa_id_ref['osr_usa_id_ref'] != $user_id) {
                    throw new OAuthException2('The user "'.$user_id.'" is not allowed to update this consumer');
                }
            } else {
                // User is an admin, allow a key owner to be changed or key to be shared
                if (array_key_exists('user_id',$consumer)) {
                    if (is_null($consumer['user_id'])) {
                        $MBL->DB->execute('
                            UPDATE {mbl_oauth_server_registry}
                            SET osr_usa_id_ref = NULL
                            WHERE osr_id = :consumerid
                            ', array('consumerid' => $consumer['id']));
                    }
                    else
                    {
                        $MBL->DB->execute('
                            UPDATE {mbl_oauth_server_registry}
                            SET osr_usa_id_ref = :userid
                            WHERE osr_id = :consumerid
                            ', array('userid' => $consumer['user_id'], 'consumerid' => $consumer['id']));
                    }
                }
            }

            $MBL->DB->execute('
                UPDATE {mbl_oauth_server_registry}
                SET osr_requester_name      = :requestername,
                    osr_requester_email     = :requestermail,
                    osr_callback_uri        = :callback,
                    osr_application_uri     = :uri,
                    osr_application_title   = :title,
                    osr_application_descr   = :desc,
                    osr_application_notes   = :notes,
                    osr_application_type    = :type,
                    osr_application_commercial = :commercial,
                    osr_timestamp           = :now
                WHERE osr_id              = :id
                  AND osr_consumer_key    = :consumerkey
                  AND osr_consumer_secret = :comsumersecret
                ',
                array(  'requestername'     => $consumer['requester_name'],
                        'requestermail'     => $consumer['requester_email'],
                        'callback'          => isset($consumer['callback_uri'])        ? $consumer['callback_uri']              : '',
                        'uri'               => isset($consumer['application_uri'])     ? $consumer['application_uri']           : '',
                        'title'             => isset($consumer['application_title'])   ? $consumer['application_title']         : '',
                        'desc'              => isset($consumer['application_descr'])   ? $consumer['application_descr']         : '',
                        'notes'             => isset($consumer['application_notes'])   ? $consumer['application_notes']         : '',
                        'type'              => isset($consumer['application_type'])    ? $consumer['application_type']          : '',
                        'commercial'        => isset($consumer['application_commercial']) ? 1 : 0,
                        'now' => date('Y-m-d H:i:s'),
                        'id'                => $consumer['id'],
                        'consumerkey'       => $consumer['consumer_key'],
                        'consumersecret'    => $consumer['consumer_secret']
                ));


            $consumer_key = $consumer['consumer_key'];
        } else {
            $consumer_key   = $this->generateKey(true);
            $consumer_secret= $this->generateKey();

            // When the user is an admin, then the user can be forced to something else that the user
            if ($user_is_admin && array_key_exists('user_id',$consumer)) {
                if (is_null($consumer['user_id'])) {
                    $owner_id = 'NULL';
                } else {
                    $owner_id = intval($consumer['user_id']);
                }
            } else {
                // No admin, take the user id as the owner id.
                $owner_id = intval($user_id);
            }

            $MBL->DB->execute('
                INSERT INTO {mbl_oauth_server_registry}
                SET osr_enabled             = 1,
                    osr_status              = \'active\',
                    osr_usa_id_ref          = :ownerid,
                    osr_consumer_key        = :consumerkey,
                    osr_consumer_secret     = :consumersecret,
                    osr_requester_name      = :name,
                    osr_requester_email     = :email,
                    osr_callback_uri        = :callback,
                    osr_application_uri     = :uri,
                    osr_application_title   = :title,
                    osr_application_descr   = :desc,
                    osr_application_notes   = :notes,
                    osr_application_type    = :type,
                    osr_application_commercial = :commercial,
                    osr_timestamp           = :timestamp,
                    osr_issue_date          = :date
                ',
                array(
                    'ownerid'           => $owner_id,
                    'consumerkey'       => $consumer_key,
                    'consumersecret'    => $consumer_secret,
                    'name'              => $consumer['requester_name'],
                    'email'             => $consumer['requester_email'],
                    'callback'          => isset($consumer['callback_uri'])        ? $consumer['callback_uri']              : '',
                    'uri'               => isset($consumer['application_uri'])     ? $consumer['application_uri']           : '',
                    'title'             => isset($consumer['application_title'])   ? $consumer['application_title']         : '',
                    'desc'              => isset($consumer['application_descr'])   ? $consumer['application_descr']         : '',
                    'notes'             => isset($consumer['application_notes'])   ? $consumer['application_notes']         : '',
                    'type'              => isset($consumer['application_type'])    ? $consumer['application_type']          : '',
                    'commercial'        => isset($consumer['application_commercial']) ? 1 : 0,
                    'timestamp'         => date('Y-m-d H:i:s'),
                    'date'              => date('Y-m-d H:i:s')
                ));
        }
        return $consumer_key;

    }



    /**
     * Delete a consumer key.  This removes access to our site for all applications using this key.
     *
     * @param string consumer_key
     * @param int user_id   user registering this server
     * @param boolean user_is_admin
     */
    public function deleteConsumer ( $consumer_key, $user_id, $user_is_admin = false ){
        global $MBL;

        if ($user_is_admin) {
            $MBL->DB->execute('
                    DELETE FROM {mbl_oauth_server_registry}
                    WHERE osr_consumer_key = :consumerkey
                      AND (osr_usa_id_ref = :userid OR osr_usa_id_ref IS NULL)
                    ', array('consumerkey' => $consumer_key, 'userid' => $user_id));
        } else {
            $MBL->DB->execute('
                    DELETE FROM {mbl_oauth_server_registry}
                    WHERE osr_consumer_key = :consumerkey
                      AND osr_usa_id_ref   = :userid
                    ', array('consumerkey' => $consumer_key, 'userid' => $user_id));
        }
    }



    /**
     * Fetch a consumer of this server, by consumer_key.
     *
     * @param string consumer_key
     * @param int user_id
     * @param boolean user_is_admin (optional)
     * @exception OAuthException2 when consumer not found
     * @return array
     */
    public function getConsumer ( $consumer_key, $user_id, $user_is_admin = false ) {
        global $MBL;
        $consumer = $MBL->DB->get_record_sql('
                        SELECT  *
                        FROM {mbl_oauth_server_registry}
                        WHERE osr_consumer_key = :consumerkey
                        ', array('consumerkey' => $consumer_key));

        if (empty($consumer)){
            throw new OAuthException2('No consumer with consumer_key "'.$consumer_key.'"');
        }

        $consumer = (array) $consumer;
        $c = array();
        foreach ($consumer as $key => $value)
        {
            $c[substr($key, 4)] = $value;
        }
        $c['user_id'] = $c['usa_id_ref'];

        if (!$user_is_admin && !empty($c['user_id']) && $c['user_id'] != $user_id)
        {
            throw new OAuthException2('No access to the consumer information for consumer_key "'.$consumer_key.'"');
        }
        return $c;
    }


    /**
     * Fetch the static consumer key for this provider.  The user for the static consumer
     * key is NULL (no user, shared key).  If the key did not exist then the key is created.
     *
     * @return string
     */
    public function getConsumerStatic () {
        global $MBL;

        $consumer = $MBL->DB->get_record_sql('
                        SELECT osr_consumer_key
                        FROM {mbl_oauth_server_registry}
                        WHERE osr_consumer_key LIKE \'sc-%%\'
                          AND osr_usa_id_ref IS NULL
                        ');

        if (empty($consumer))
        {
            $consumer_key = 'sc-'.$this->generateKey(true);
            $MBL->DB->execute('
                INSERT INTO {mbl_oauth_server_registry}
                SET osr_enabled             = 1,
                    osr_status              = \'active\',
                    osr_usa_id_ref          = NULL,
                    osr_consumer_key        = :consumerkey,
                    osr_consumer_secret     = \'\',
                    osr_requester_name      = \'\',
                    osr_requester_email     = \'\',
                    osr_callback_uri        = \'\',
                    osr_application_uri     = \'\',
                    osr_application_title   = \'Static shared consumer key\',
                    osr_application_descr   = \'\',
                    osr_application_notes   = \'Static shared consumer key\',
                    osr_application_type    = \'\',
                    osr_application_commercial = 0,
                    osr_timestamp           = :now,
                    osr_issue_date          = :now
                ',
                array(
                    'consumerkey' => $consumer_key,
                    'now' =>  date('Y-m-d H:i:s')
                )

                );

            // Just make sure that if the consumer key is truncated that we get the truncated string
            $consumer = $this->getConsumerStatic();
        }
        return $consumer;
    }


    /**
     * Add an unautorized request token to our server.
     *
     * @param string consumer_key
     * @param array options     (eg. token_ttl)
     * @return array (token, token_secret)
     */
    public function addConsumerRequestToken ( $consumer_key, $options = array() )
    {
        global $MBL;
        $token  = $this->generateKey(true);
        $secret = $this->generateKey();
        try{
            $rs = $MBL->DB->get_record_sql('
                            SELECT osr_id
                            FROM {mbl_oauth_server_registry}
                            WHERE osr_consumer_key = :consumerkey
                              AND osr_enabled      = 1
                            ', array(
                                'consumerkey' => $consumer_key
                                ),
                            MUST_EXIST
                            );
            $osr_id =  $rs->osr_id;
        } catch (dml_exception $e){
            throw new OAuthException2('No server with consumer_key "'.$consumer_key.'" or consumer_key is disabled');
        }

        if (isset($options['token_ttl']) && is_numeric($options['token_ttl']))
        {
            $ttl = intval($options['token_ttl']);
        }
        else
        {
            $ttl = $this->max_request_token_ttl;
        }

        if (!isset($options['oauth_callback'])) {
            // 1.0a Compatibility : store callback url associated with request token
            $options['oauth_callback']='oob';
        }

        try{
        $MBL->DB->execute('
                INSERT INTO {mbl_oauth_server_token}
                SET ost_osr_id_ref      = :id,
                    ost_usa_id_ref      = 1,
                    ost_token           = :token,
                    ost_token_secret    = :secret,
                    ost_token_type      = \'request\',
                    ost_token_ttl       = :ttl,
                    ost_callback_url    = :callback',

                    array('id' => $osr_id,
                          'token' => $token,
                          'secret' => $secret,
                          'ttl' => date('Y-m-d H:i:s', time() + $ttl),
                          'callback' => $options['oauth_callback']
                    )
                );
        } catch(dml_exception $e){
            $servertoken = $MBL->DB->get_record('mbl_oauth_server_token', array('ost_token' => $token));
            $servertoken->ost_timestamp = date('Y-m-d H:i:s');
            $MBL->DB->update_record('mbl_oauth_server_token', $servertoken);
        }


        return array('token'=>$token, 'token_secret'=>$secret, 'token_ttl'=>$ttl);
    }


    /**
     * Fetch the consumer request token, by request token.
     *
     * @param string token
     * @return array  token and consumer details
     */
    public function getConsumerRequestToken ( $token )
    {
        global $MBL;

        $rs = $MBL->DB->get_record_sql('
                SELECT  ost_token           as token,
                        ost_token_secret    as token_secret,
                        osr_consumer_key    as consumer_key,
                        osr_consumer_secret as consumer_secret,
                        ost_token_type      as token_type,
                        ost_callback_url    as callback_url,
                        osr_application_title as application_title,
                        osr_application_descr as application_descr,
                        osr_application_uri   as application_uri
                FROM {mbl_oauth_server_token}
                        JOIN {mbl_oauth_server_registry}
                        ON ost_osr_id_ref = osr_id
                WHERE ost_token_type = \'request\'
                  AND ost_token      = :token
                  AND ost_token_ttl  >= :now
                ', array(
                            'token' => $token,
                            'now' => date('Y-m-d H:i:s')
                ));

        return (array)$rs;
    }


    /**
     * Delete a consumer token.  The token must be a request or authorized token.
     *
     * @param string token
     */
    public function deleteConsumerRequestToken ( $token )
    {
        global $MBL;
        $MBL->DB->execute('
                    DELETE FROM {mbl_oauth_server_token}
                    WHERE ost_token      = :token
                      AND ost_token_type = \'request\'
                    ',
                    array(
                            'token' => $token
                        )
                    );
    }


    /**
     * Upgrade a request token to be an authorized request token.
     *
     * @param string token
     * @param int    user_id  user authorizing the token
     * @param string referrer_host used to set the referrer host for this token, for user feedback
     */
    public function authorizeConsumerRequestToken ( $token, $user_id, $referrer_host = '' )
    {
        global $MBL;

        // 1.0a Compatibility : create a token verifier
        $verifier = substr(md5(rand()),0,10);

        $MBL->DB->execute('
                    UPDATE {mbl_oauth_server_token}
                    SET ost_authorized    = 1,
                        ost_usa_id_ref    = :userid,
                        ost_timestamp     = :now,
                        ost_referrer_host = :referrer,
                        ost_verifier      = :verifier
                    WHERE ost_token      = :token
                      AND ost_token_type = \'request\'
                    ',
                    array(
                            'userid' => $user_id,
                            'now' => date('Y-m-d H:i:s'),
                            'referrer' => $referrer_host,
                            'verifier' => $verifier,
                            'token' => $token
                            )
                    );
        return $verifier;
    }


    /**
     * Count the consumer access tokens for the given consumer.
     *
     * @param string consumer_key
     * @return int
     */
    public function countConsumerAccessTokens ( $consumer_key )
    {
        global $MBL;
        $count = $MBL->DB->execute('
                    SELECT COUNT(ost_id)
                    FROM {mbl_oauth_server_token}
                            JOIN {mbl_oauth_server_registry}
                            ON ost_osr_id_ref = osr_id
                    WHERE ost_token_type   = \'access\'
                      AND osr_consumer_key = :key
                      AND ost_token_ttl    >= :now
                    ',
                    array(
                            'key' => $consumer_key,
                            'now' => date('Y-m-d H:i:s')
                        )
                    );

        return $count;
    }


    /**
     * Exchange an authorized request token for new access token.
     *
     * @param string token
     * @param array options     options for the token, token_ttl
     * @exception OAuthException2 when token could not be exchanged
     * @return array (token, token_secret)
     */
    public function exchangeConsumerRequestForAccessToken ( $token, $options = array() )
    {
        global $MBL;
        $new_token  = $this->generateKey(true);
        $new_secret = $this->generateKey();

        // Maximum time to live for this token
        $ttl = '';
        if (isset($options['token_ttl']) && is_numeric($options['token_ttl']))
        {
            $ttl = date('Y-m-d H:i:s', time() + $options['token_ttl']);
        }
        else
        {
            $ttl= '9999-12-31';
        }

        try{
            if (isset($options['verifier'])) {
                $verifier = $options['verifier'];

                // 1.0a Compatibility : check token against oauth_verifier
                $MBL->DB->execute('
                            UPDATE {mbl_oauth_server_token}
                            SET ost_token           = :newtoken,
                                ost_token_secret    = :secret,
                                ost_token_type      = \'access\',
                                ost_timestamp       = :now,
                                ost_token_ttl       = :ttl
                            WHERE ost_token      = :token
                              AND ost_token_type = \'request\'
                              AND ost_authorized = 1
                              AND ost_token_ttl  >= :tokenttl
                              AND ost_verifier = :verifier
                            ',
                            array(
                                'newtoken' => $new_token,
                                'secret' => $new_secret,
                                'now' => date('Y-m-d H:i:s'),
                                'ttl' => $ttl,
                                'token' => $token,
                                'tokenttl' => date('Y-m-d H:i:s'),
                                'verifier' => $verifier
                                )
                            );
            } else {

                // 1.0
                $MBL->DB->execute('
                            UPDATE {mbl_oauth_server_token}
                            SET ost_token           = :newtoken,
                                ost_token_secret    = :secret,
                                ost_token_type      = \'access\',
                                ost_timestamp       = :now,
                                ost_token_ttl       = :ttl
                            WHERE ost_token      = :token
                              AND ost_token_type = \'request\'
                              AND ost_authorized = 1
                              AND ost_token_ttl  >= :tokenttl
                            ',
                            array(
                                'newtoken' => $new_token,
                                'secret' => $new_secret,
                                'now' => date('Y-m-d H:i:s'),
                                'ttl' => $ttl,
                                'token' => $token,
                                'tokenttl' => date('Y-m-d H:i:s'),
                                )
                            );
            }
        } catch (dml_exception $e){
            throw new OAuthException2('Can\'t exchange request token "'.$token.'" for access token. No such token or not authorized');
        }

        $ret = array('token' => $new_token, 'token_secret' => $new_secret);
        $ttl = $MBL->DB->get_record_sql('
                    SELECT  ost_token_ttl as token_ttl
                    FROM {mbl_oauth_server_token}
                    WHERE ost_token = :token', array( 'token' => $new_token));

        // \'9999-12-31\', NULL, UNIX_TIMESTAMP(ost_token_ttl) - UNIX_TIMESTAMP(NOW())
        if ($ttl->token_ttl != '9999-12-31')
        {
            $ret['token_ttl'] = strtotime($ttl->token_ttl) - time();
        }
        return $ret;
    }


    /**
     * Fetch the consumer access token, by access token.
     *
     * @param string token
     * @param int user_id
     * @exception OAuthException2 when token is not found
     * @return array  token and consumer details
     */
    public function getConsumerAccessToken ( $token, $user_id )
    {
        global $MBL;

        try {
            $rs = $MBL->DB->get_record_sql('
                    SELECT  ost_token               as token,
                            ost_token_secret        as token_secret,
                            ost_referrer_host       as token_referrer_host,
                            osr_consumer_key        as consumer_key,
                            osr_consumer_secret     as consumer_secret,
                            osr_application_uri     as application_uri,
                            osr_application_title   as application_title,
                            osr_application_descr   as application_descr,
                            osr_callback_uri        as callback_uri
                    FROM {mbl_oauth_server_token}
                            JOIN {mbl_oauth_server_registry}
                            ON ost_osr_id_ref = osr_id
                    WHERE ost_token_type = \'access\'
                      AND ost_token      = :token
                      AND ost_usa_id_ref = :user
                      AND ost_token_ttl  >= :now
                    ',
                    array(
                            'token' => $token,
                            'user' => $user_id,
                            'now' => date('Y-m-d H:i:s')
                            ),
                    MUST_EXIST
                    );
        } catch (dml_exception $e){
            throw new OAuthException2('No server_token "'.$token.'" for user "'.$user_id.'"');
        }

        return $rs;
    }


    /**
     * Delete a consumer access token.
     *
     * @param string token
     * @param int user_id
     * @param boolean user_is_admin
     */
    public function deleteConsumerAccessToken ( $token, $user_id, $user_is_admin = false )
    {
        global $MBL;

        if ($user_is_admin)
        {
            $MBL->DB->execute('
                        DELETE FROM {mbl_oauth_server_token}
                        WHERE ost_token      = :token
                          AND ost_token_type = \'access\'
                        ',
                        array('token' => $token));
        }
        else
        {
            $MBL->DB->execute('
                        DELETE FROM {mbl_oauth_server_token}
                        WHERE ost_token      = :token
                          AND ost_token_type = \'access\'
                          AND ost_usa_id_ref = :userid
                        ',
                        array(
                            'token' => $token,
                            'userid' => $user_id
                            )
                        );
        }
    }


    /**
     * Set the ttl of a consumer access token.  This is done when the
     * server receives a valid request with a xoauth_token_ttl parameter in it.
     *
     * @param string token
     * @param int ttl
     */
    public function setConsumerAccessTokenTtl ( $token, $token_ttl )
    {
        global $MBL;

        if ($token_ttl <= 0)
        {
            // Immediate delete when the token is past its ttl
            $this->deleteConsumerAccessToken($token, 0, true);
        }
        else
        {
            // Set maximum time to live for this token
            $MBL->DB->execute('
                        UPDATE {mbl_oauth_server_token}
                        SET ost_token_ttl = :ttl
                        WHERE ost_token      = :token
                          AND ost_token_type = \'access\'
                        ',
                        array(
                            'ttl' => date('Y-m-d H:i:s', time() + $token_ttl),
                            'token' => $token
                            )
                        );
        }
    }


    /**
     * Fetch a list of all consumer keys, secrets etc.
     * Returns the public (user_id is null) and the keys owned by the user
     *
     * @param int user_id
     * @return array
     */
    public function listConsumers ( $user_id )
    {
        global $MBL;
        $rs = $MBL->DB->get_records_sql('
                SELECT  osr_id                  as id,
                        osr_usa_id_ref          as user_id,
                        osr_consumer_key        as consumer_key,
                        osr_consumer_secret     as consumer_secret,
                        osr_enabled             as enabled,
                        osr_status              as status,
                        osr_issue_date          as issue_date,
                        osr_application_uri     as application_uri,
                        osr_application_title   as application_title,
                        osr_application_descr   as application_descr,
                        osr_requester_name      as requester_name,
                        osr_requester_email     as requester_email,
                        osr_callback_uri        as callback_uri
                FROM {mbl_oauth_server_registry}
                WHERE (osr_usa_id_ref = :user OR osr_usa_id_ref IS NULL)
                ORDER BY osr_application_title
                ', array('user' => $user_id));
        return $rs;
    }

    /**
     * List of all registered applications. Data returned has not sensitive
     * information and therefore is suitable for public displaying.
     *
     * @param int $begin
     * @param int $total
     * @return array
     */
    public function listConsumerApplications($begin = 0, $total = 25)
    {
        global $MBL;
        $rs = $MBL->DB->get_records_sql('
                SELECT  osr_id                  as id,
                        osr_enabled             as enabled,
                        osr_status              as status,
                        osr_issue_date          as issue_date,
                        osr_application_uri     as application_uri,
                        osr_application_title   as application_title,
                        osr_application_descr   as application_descr
                FROM {mbl_oauth_server_registry}
                ORDER BY osr_application_title
                ');
        // TODO: pagination
        return $rs;
    }

    /**
     * Fetch a list of all consumer tokens accessing the account of the given user.
     *
     * @param int user_id
     * @return array
     */
    public function listConsumerTokens ( $user_id )
    {
        global $MBL;

        $rs = $MBL->DB->get_records_sql('
                SELECT  osr_consumer_key        as consumer_key,
                        osr_consumer_secret     as consumer_secret,
                        osr_enabled             as enabled,
                        osr_status              as status,
                        osr_application_uri     as application_uri,
                        osr_application_title   as application_title,
                        osr_application_descr   as application_descr,
                        ost_timestamp           as timestamp,
                        ost_token               as token,
                        ost_token_secret        as token_secret,
                        ost_referrer_host       as token_referrer_host,
                        osr_callback_uri        as callback_uri
                FROM {mbl_oauth_server_registry}
                    JOIN {mbl_oauth_server_token}
                    ON ost_osr_id_ref = osr_id
                WHERE ost_usa_id_ref = :user
                  AND ost_token_type = \'access\'
                  AND ost_token_ttl  >= :now
                ORDER BY osr_application_title
                ',
                array (
                    'user' => $user_id,
                    'now' =>date('Y-m-d H:i:s')
                    )
                );
        return $rs;
    }


    /**
     * Check an nonce/timestamp combination.  Clears any nonce combinations
     * that are older than the one received.
     *
     * @param string    consumer_key
     * @param string    token
     * @param int       timestamp
     * @param string    nonce
     * @exception OAuthException2   thrown when the timestamp is not in sequence or nonce is not unique
     */
    public function checkServerNonce ( $consumer_key, $token, $timestamp, $nonce )
    {
        global $MBL;

        $r = $MBL->DB->get_record_sql('
                            SELECT MAX(osn_timestamp) as maxtimestamp, MAX(osn_timestamp) > :sumtime as checktime
                            FROM {mbl_oauth_server_nonce}
                            WHERE osn_consumer_key = :key
                              AND osn_token        = :token
                            ',
                            array(
                                    'sumtime' => $timestamp + $this->max_timestamp_skew,
                                    'key' => $consumer_key,
                                    'token' => $token
                                    )
                            );
        if (!empty($r) && $r->checktime)
        {
            throw new OAuthException2('Timestamp is out of sequence. Request rejected. Got '.$timestamp.' last max is '.$r->maxtimestamp.' allowed skew is '.$this->max_timestamp_skew);
        }

        try{
            // Insert the new combination
            $MBL->DB->execute('
                    INSERT INTO {mbl_oauth_server_nonce}
                    SET osn_consumer_key    = :key,
                        osn_token           = :token,
                        osn_timestamp       = :time,
                        osn_nonce           = :nonce
                    ',
                    array(
                            'key' => $consumer_key,
                            'token' => $token,
                            'time' => $timestamp,
                            'nonce' => $nonce
                            )
                    );
        } catch (dml_exception $e){
            throw new OAuthException2('Duplicate timestamp/nonce combination, possible replay attack.  Request rejected.');
        }

        // Clean up all timestamps older than the one we just received
        $MBL->DB->execute('
                DELETE FROM {mbl_oauth_server_nonce}
                WHERE osn_consumer_key  = :key
                  AND osn_token         = :token
                  AND osn_timestamp     < :time
                ',
                array(
                        'key' => $consumer_key,
                        'token' => $token,
                        'time' => $timestamp - $this->max_timestamp_skew
                        )
                );
    }


    /**
     * Add an entry to the log table
     *
     * @param array keys (osr_consumer_key, ost_token, ocr_consumer_key, oct_token)
     * @param string received
     * @param string sent
     * @param string base_string
     * @param string notes
     * @param int (optional) user_id
     */
    public function addLog ( $keys, $received, $sent, $base_string, $notes, $user_id = null )
    {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Get a page of entries from the log.  Returns the last 100 records
     * matching the options given.
     *
     * @param array options
     * @param int user_id   current user
     * @return array log records
     */
    public function listLog ( $options, $user_id )
    {
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Perform a query, ignore the results
     *
     * @param string sql
     * @param vararg arguments (for sprintf)
     */
    protected function query ( $sql ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Perform a query, ignore the results
     *
     * @param string sql
     * @param vararg arguments (for sprintf)
     * @return array
     */
    protected function query_all_assoc ( $sql ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Perform a query, return the first row
     *
     * @param string sql
     * @param vararg arguments (for sprintf)
     * @return array
     */
    protected function query_row_assoc ( $sql ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }

    /**
     * Perform a query, return the first row
     *
     * @param string sql
     * @param vararg arguments (for sprintf)
     * @return array
     */
    protected function query_row ( $sql ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Perform a query, return the first column of the first row
     *
     * @param string sql
     * @param vararg arguments (for sprintf)
     * @return mixed
     */
    protected function query_one ( $sql ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Return the number of rows affected in the last query
     */
    protected function query_affected_rows (){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    /**
     * Return the id of the last inserted row
     *
     * @return int
     */
    protected function query_insert_id (){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    protected function sql_printf ( $args ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    protected function sql_escape_string ( $s ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }


    protected function sql_errcheck ( $sql ){
        throw new moodle_exception('generalexceptionmessage', 'error', '', __FUNCTION__ . 'not implemented');
    }
}
