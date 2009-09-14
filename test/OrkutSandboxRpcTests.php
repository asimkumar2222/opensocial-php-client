<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * 'License'); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * 'AS IS' BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/*
 * This file is meant to be run through a php command line, not called
 * directly through the web browser. To run these tests from the command line:
 * # cd /path/to/client
 * # phpunit test/OrkutSandboxRpcTests.php
 */

require_once '__init__.php';
require_once 'online/OnlineTestSuite.php';

class OrkutSandboxRpcTests extends OnlineTestSuite {
  public $CONSUMER_KEY = 'orkut.com:249475676706';
  public $CONSUMER_SECRET = 'fWPcoVP6DOLVqZOF2HH+ihU2';
  public $USER_A_ID = '01912035848546370285';
  public $USER_A_DISPLAY_NAME = 'Alice Testington';
  public $USER_A_EXTENDED_PROFILE_FIELDS = array('aboutMe', 'gender');
  public $UNSUPPORTED_METHODS = array('activities.create');
  
  protected function getOsapi() {
    $provider = new osapiOrkutProvider();
    $auth = new osapiOAuth2Legged($this->CONSUMER_KEY, $this->CONSUMER_SECRET, $this->USER_A_ID);
    return new osapi($provider, $auth);
  }

  public static function suite() {
    return new OrkutSandboxRpcTests();
  }
}
