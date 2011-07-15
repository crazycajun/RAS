<?php

// This is the class responsible for interfacing with the rest api to 
// request the recall information from the government website.
class UsaSearch {
	
	private $restApi = null;
	private $config = null;
	
	// Constructs an instance of the class with the restApi and config specified.
	// If the config is null, the class constructs a default configuration instance.
	function __construct($restApi, $config = null) {
		$this->restApi = $restApi;
		$this->config = is_null($config) ? new ApplicationConfiguration() : $config; 
	}
	
	// Searches the recall information for the query specified. The Query must be non-null
	// and provide a value for either the searchTerm or an instance of the AutoDetails.	
	function search($query) {
		$this->validate($query);
		
		$options = $this->restApi
			->forUrl($this->config->UsaSearchUrl)
			->timeout($this->config->UsaRequestTimeoutInSeconds)
			->httpGet($this->buildParamsArray($query));
			
		$this->restApi->get($options);
	}
	
	// Builds the parameter array based on the query object.
	public function buildParamsArray($query) {
		return array(
			"api_key" => $this->config->UsaSearchApiKey,
			"format" => $this->config->UsaSearchFormat,
			"make" => $query->autoDetails->make,
			"model" => $query->autoDetails->model,
			"page" => 1
		);
	}
	
	
	// Validates the query input.
	private function validate($query) {
		if (is_null($query)) throw new InvalidArgumentException('A Query entity must be provided to perform the search operation.');
		if (empty($query->searchTerm) && is_null($query->autoDetails)) throw new InvalidArgumentException('The query must have a searchTerm or autoDetails provided.');
	}
}
?>