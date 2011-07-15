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
	
	// The url for the rest service.
	public $url = NULL;
		
	function __construct($url) {
		$this->url = $url;
	}
	
	// Configures options for JSON input and converts the input into JSON.
	function json($input) {
		$this->params = json_encode($input);
		$this->contentType = self::JsonContentType;
		return $this;
	}
}

?>