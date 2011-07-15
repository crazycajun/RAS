<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/RestApi.php';

class RequestResultTests extends UnitTestCase {
	function testSucceedsIndicatesSuccessForOkStatusCode() {
		$result = new RequestResult();
		$result->httpStatusCode = HttpStatusCodes::OK;
		$this->assertTrue($result->succeeded(), 'The request result indicates failure.');
	}
}