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
 
// Add the library directory to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . 
    '..' . DIRECTORY_SEPARATOR . 'library');

require_once('PHPUnit/Framework.php');
require_once('OAuth/OAuth.php');
require_once('OpenSocial/OpenSocialHttpLib.php');

/**
 * Tests the socket http library implementation.
 */
abstract class AbstractHttpLibTest extends PHPUnit_Framework_TestCase {
  protected $httplib;
  
  /**
   * Returns an appropriate OpenSocialHttpLib implementation to use.
   * @return OpenSocialHttpLib An instance of the type of HttpLib to test.
   */
  abstract function getHttpLib();
  
  /**
   * Initializes the test class.
   */
  public function setUp() {
    $this->httplib = $this->getHttpLib();
  }
  
  /**
   * Cleans up after each test.
   */
  public function tearDown() {
    unset($this->httplib);
  }
  
  /**
   * Tests GET requests.
   */
  public function testGet() {
    $request = new OAuthRequest(
        "GET", 
        "http://osda.appspot.com/js/samplejson.js", 
        null);
    $result = $this->httplib->sendRequest($request);
    $expected_result = '{ "Success" : true }';
    $this->assertEquals($result, $expected_result);
  }
}

/**
 * Tests the socket http library implementation.
 */
class TestSocketHttpLib extends AbstractHttpLibTest {
  public function getHttpLib() {
    return new SocketHttpLib();
  }
}

/**
 * Tests the curl http library implementation.
 */
class TestCurlHttpLib extends AbstractHttpLibTest {
  public function getHttpLib() {
    return new CurlHttpLib();
  }
}