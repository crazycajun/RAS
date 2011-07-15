<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/RestApi.php';
require_once '../../usa_search/CurlRestApi.php';
require_once '../../ApplicationConfiguration.php';

class CurlRestApiParse extends UnitTestCase {
	private $api;
	private $fakeCurlInfo = array(
		'http_code' => '403'
	);

	function setUp() {
		$this->api = new CurlRestApi();
	}
	
	function testResultHasHttpStatusCodeSet() {
		$result = $this->api->parse(null, $this->fakeCurlInfo);
		$this->assertEqual($result->httpStatusCode, '403');
	}
	
	function testResultHasTheResponseBody() {
		$result = $this->api->parse("the response body", $this->fakeCurlInfo);
		$this->assertEqual($result->responseBody, 'the response body');
	}
}

?>