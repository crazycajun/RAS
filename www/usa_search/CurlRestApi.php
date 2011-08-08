<?php

require_once('RestApi.php');

// This class is the true implementation for interfacing with REST APIs.
// It is based off of the blog post found here:
//	http://www.gen-x-design.com/archives/making-restful-requests-in-php/
class CurlRestApi implements RestApi {
	function forUrl($url) {
		return new RestOptions($url);
	}
	
	// Makes an HTTP GET request using the specified RestOptions
	// instance.
	function get($restOpts) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_TIMEOUT, $restOpts->getRequestTimeout());
		curl_setopt($curl, CURLOPT_URL, $restOpts->getUrl() . '?' . $restOpts->getParams());
		
		// Tells curl to return the result as part of the response instead of outputting it.
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$responseBody = curl_exec($curl);
		$responseInfo = curl_getinfo($curl);
		
		curl_close($curl);
		return $this->parse($responseBody, $responseInfo);
	}
	
	// Parses the curl response information into a RestRequest.
	function parse($responseBody, $responseInfo) {
		$result = new RequestResult();
		$result->httpStatusCode = $responseInfo['http_code'];
		$result->responseBody = $responseBody;
		return $result;
	}
}
?>