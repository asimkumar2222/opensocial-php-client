<?php
/*
 * Copyright 2008 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once("Zend/Json.php");
require_once("OpenSocialHttpRequest.php");
require_once("OpenSocialCollection.php");
require_once("OpenSocialPerson.php");

/**
 * Client library helper for making OpenSocial requests.
 */
class OpenSocial {
  private $oauth_consumer_key;
  private $oauth_consumer_secret;
  private $server_rest_base;
  private $server_rpc_base;

  /**
   * Initializes this client object with the supplied configuration.
   */
  public function __construct($config, $httplib=null, $cache=null) {
    $this->oauth_consumer_key = $config["oauth_consumer_key"];
    $this->oauth_consumer_secret = $config["oauth_consumer_secret"];
    $this->server_rest_base = $config["server_rest_base"];
    $this->server_rpc_base = $config["server_rpc_base"];
  }
  
  /**
   * Fetches data for a single person.
   */
  public function fetchPerson($guid, $fields = Array()) {
    //TODO: Must refactor out this code
    global $user;
    $user = $guid;
    
    //TODO: Build this URL in a better way that supports a more arbitrary config
    $rest_endpoint = $this->server_rest_base . 'people/' . $guid . '/@self';
    $result = $this->rest_fetch($rest_endpoint, $fields);
    return OpenSocialPerson::parseJson($result);
  }
  
  /**
   * Fetches data for the friends of the specified user.
   */
  public function fetchFriends($guid, $fields = Array()) {
    //TODO: Must refactor out this code
    global $user;
    $user = $guid;
    
    $rest_endpoint = $this->server_rest_base . 'people/' . $guid . '/@friends';
    $result = $this->rest_fetch($rest_endpoint, $fields);
    return OpenSocialPerson::parseJsonCollection($result);
  }
  
  /** TODO: Continue refactoring below this line **/

  public function people_getAllInfo($guid, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'people/' . $guid . '/@all';
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  public function people_getGroupFriends($guid, $group_id, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'people/' . $guid . '/' . $group_id;
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  public function people_getMyInfo($fields = Array()) {
    $rest_endpoint = $this->server_rest_base . '@me/' . '@self';
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  public function people_getFriendInfo($guid, $fid, $fields = Array()) {
    //TODO: Must refactor out this code
    global $user;
    $user = $guid;
    
    $rest_endpoint = $this->server_rest_base . 'people/' . $guid . '/@all/' . $fid;
    $result = $this->rest_fetch($rest_endpoint, $fields);
    return OpenSocialPerson::parseJsonCollection($result);
  }

  // get groups associated with a user
  public function group_getUserGroups($guid, $group_id = 1, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'group/' . $guid . '/' . $group_id;
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  // get activities generated by a user
  public function activity_getUserActivity($guid, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'activity/' . $guid . '@self';
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  // get activities generated by a user
  public function activity_getFriendActivity($guid, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'activity/' . $guid . '@friends';
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  // get app data of a user guid for app given by appid
  public function appdata_getUserAppData($guid, $appid, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'appdata/@me/@self/@app'; // . $appid;
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  // get app data of friends of a user guid for app given by appid
  public function appdata_getFriendsAppData($guid, $appid, $fields = Array()) {
    $rest_endpoint = $this->server_rest_base . 'appdata/' . $guid . '@friends/' . $appid;
    return $this->rest_fetch($rest_endpoint, $fields);
  }

  /* utility */

  public function rest_fetch($endpoint, $params) {

    $httplib = new OpenSocialHttpLib(
        $this->server_rest_base, 
        $this->oauth_consumer_key, 
        $this->oauth_consumer_secret
    );
    $json_result = $httplib->send_request($endpoint, $params);

    // json_encode is supported after PHP 5.2.0 so for simplicity Zend library is included and used
    $result = Zend_Json::decode($json_result);
   
    return $result;
  }

  // RPC functions

  public function rpcGetMyInfo() {
    return $this->rpc_fetch($this->server_rpc_base, "");
  }

  public function rpc_fetch($rpc_endpoint, $json_body) {

    $httplib = new OpenSocialHttpLib($this->server_addr, $this->oauth_consumer_key, $this->oauth_consumer_secret);
    $json_array['method'] = 'people.get';
    $json_array['id'] = 'myself';
    $json_array['params']['userid'] = '@me';
    $json_array['params']['groupid'] = '@self';

    $json_body = Zend_Json::encode($json_array );
    
    $result = $httplib->send_rpc_request($rpc_endpoint, $json_body);

    return $result;
  }

}

?>
