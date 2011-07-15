<?php

// Interface for the class that will be responsible for REST communications 
// over HTTP.
interface RestApi {
	// This method acts as a builder for the configuration properties for the REST request. It 
	// returns a RestOptions entity.
	public function forUrl($url);
}

// Fluent options class that is used to pass REST configuration details to the RestApi instance.
class RestOptions {
	
	// The HTTP content type for JSON input.
	const JsonContentType = 'application/json';
	
	// The HTTP constent type for text data.
	const PlainTextContentType = 'text/plain';
	
	// The url for the rest service.
	private $url = NULL;
	
	// REST parameters.
	private $params = NULL;
	
	// The content type for the request.
	private $contentType = self::PlainTextContentType;
	
	// The HTTP timeout value in seconds.
	private $requestTimeout = 15; 
	
	function __construct($url) {
		$this->url = $url;
	}
	
	// Configures options for JSON input and converts the input into JSON.
	function json($input) {
		$this->params = json_encode($input);
		$this->contentType = self::JsonContentType;
		return $this;
	}
	
	// Sets the request timeout in seconds (non-zero, positive value).
	function timeout($value) {
		if ($value <= 0) throw new InvalidArgumentException('Timeout must be a positive, non-zero value.');
		$this->requestTimeout = $value;
		return $this;
	}
	
	// The getters for the fields on the instance. These methods should
	// have no other implementation than to return the values.
	function getRequestTimeout() {
		return $this->requestTimeout;
	}
	
	function getContentType() {
		return $this->contentType;
	}
	
	function getUrl() {
		return $this->url;
	}
	
	function getParams() {
		return $this->params;
	}
}

?>