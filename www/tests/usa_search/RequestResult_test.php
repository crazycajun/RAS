<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/RestApi.php';

class RequestResultTests extends UnitTestCase {
	function testSucceededIndicatesSuccessForOkStatusCode() {
		$result = new RequestResult();
		$result->httpStatusCode = HttpStatusCodes::OK;
		$this->assertTrue($result->succeeded(), 'The request result indicates failure.');
	}
	
	function testSucceededIndicatesSuccessForNoContentStatusCode() {
		$result = new RequestResult();
		$result->httpStatusCode = HttpStatusCodes::NoContent;
		$this->assertTrue($result->succeeded(), 'The request result indicates failure.s');
	}
	
	function testSucceededIsNotIndicatedByDefault() {
		$result = new RequestResult();
		$this->assertFalse($result->succeeded(), 'The request should not have succeeded.');
	}
	
	function testFailedIsIndicatedByDefault() {
		$result = new RequestResult();
		$this->assertTrue($result->failed(), 'The request should have failed.');
	}
	
	function testSucceededIsNotIndicatedForTimeouts() {
		$result = new RequestResult();
		$result->httpStatusCode = HttpStatusCodes::RequestTimeout;
		$this->assertTrue($result->serverTimedOut(), 'The result should indicate timeout.');
		$this->assertFalse($result->succeeded(), 'The result should have failed.');
		
		$result->httpStatusCode = HttpStatusCodes::GatewayTimeout;
		$this->assertTrue($result->serverTimedOut(), 'The result should indicate timeout.');
		$this->assertFalse($result->succeeded(), 'The result should have failed.');
	}
	
	function testServerIsUnavailableForTheseStatusCodes() {
		$result = new RequestResult();
		$result->httpStatusCode = HttpStatusCodes::InternalServerError;
		$this->assertTrue($result->serverUnavailable());
		
		$result->httpStatusCode = HttpStatusCodes::ServiceUnavailable;
		$this->assertTrue($result->serverUnavailable());
	}
}