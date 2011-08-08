<?php

// This is the class responsible for interfacing with the rest api to 
// request the recall information from the government website.
class UsaSearch {
	
	private $restApi = null;
	private $config = null;
	private $parser = null;
	
	// Constructs an instance of the class with the restApi and config specified.
	// If the config is null, the class constructs a default configuration instance.
	function __construct($restApi = null, $parser = null, $config = null) {
		$this->restApi = is_null($restApi) ? new CurlRestApi() : $restApi;
		$this->parser = is_null($parser) ? new UsaSearchResultParser() : $parser;
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
			
		$result = $this->restApi->get($options);
		return $this->parser->parse($result->responseBody);
	}
	
	// Builds the parameter array based on the query object.
	public function buildParamsArray($query) {
		$autoDetails = is_null($query->autoDetails) ? new AutoDetails() : $query->autoDetails;
		$params = array(
			"api_key" => $this->config->UsaSearchApiKey,
			"format" => $this->config->UsaSearchFormat,
			"page" => $query->page
		);
		
		$this->addKeyIfSet('query', $query->searchTerm, $params);
		$this->addKeyIfSet('make', $autoDetails->make, $params);
		$this->addKeyIfSet('model', $autoDetails->model, $params);
		$this->addKeyIfSet('year', $autoDetails->year, $params);
		return $params;
	}
	
	// Adds the key value pair to the array if the value is set.
	private function addKeyIfSet($key, $value, &$arr) {
		if (isset($value)) {
			$arr[$key] = $value;
		}
	}
	
	
	// Validates the query input.
	private function validate($query) {
		if (is_null($query)) throw new InvalidArgumentException('A Query entity must be provided to perform the search operation.');
		if (empty($query->searchTerm) && is_null($query->autoDetails)) throw new InvalidArgumentException('The query must have a searchTerm or autoDetails provided.');
	}
}
?>