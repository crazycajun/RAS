<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/RestApi.php';
require_once '../../usa_search/CurlRestApi.php';
require_once '../../ApplicationConfiguration.php';

// Use this to see if curl is installed as an extension.
//var_dump(get_loaded_extensions());

// Run this file to troubleshoot the USA Search api and the curl
// REST API. This file is not named against the conventions intentionally.
class CurlRestApiTest extends UnitTestCase {
	function testRecallDataReturned() {
		$curlApi = new CurlRestApi();
		$config = new ApplicationConfiguration();
		$opts = new RestOptions($config->UsaSearchUrl);
		$opts->httpGet(array(
			"api_key" => $config->UsaSearchApiKey,
			"make" => "Toyota",
			"model" => "Prius",
			"year" => 2003,
			"format" => $config->UsaSearchFormat
		));
		
		$result = $curlApi->get($opts);
		$this->assertTrue($result->succeeded(), 'The request should have succeeded');
		$this->assertPattern('/\{"success":/', $result->responseBody);
	}
}

?>