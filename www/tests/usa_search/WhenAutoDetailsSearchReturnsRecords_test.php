<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/UsaSearch.php';
require_once '../../usa_search/Query.php';
require_once '../../usa_search/RestApi.php';
require_once '../../ApplicationConfiguration.php';
require_once '../../ProductInfo.php';

class WhenAutoDetailsSearchReturnsRecords extends UnitTestCase {
	
	private $fakeRestService = NULL;
	private $query = NULL;
	private $config = NULL;
		
	function setUp() {
		$this->query = new Query();
		$autoDetails = new AutoDetails();
		$autoDetails->make = 'Toyota';
		$autoDetails->model = 'Prius';
		$this->query->autoDetails = $autoDetails;
		
		$this->fakeRestService = new FakeRestService();
		$this->config = new ApplicationConfiguration();
		$api = new UsaSearch($this->fakeRestService, new FakeRequestParser());
		$this->searchResult = $api->search($this->query);
	}
	
	function testRestOptionsHasUrlFromConfiguration() {
		$this->assertEqual($this->fakeRestService->options->getUrl(), $this->config->UsaSearchUrl);
	}
	
	function testRestOptionsHasRequestTimeoutConfiguration() {
		$this->assertEqual($this->fakeRestService->options->getRequestTimeout(), $this->config->UsaRequestTimeoutInSeconds);
	}
	
	function testRestOptionsHasTheseParams() {
		$expectedParams = 'api_key=195dbebb8f6c7fd8a7d143d5d13c2a76&format=json&page=1&make=Toyota&model=Prius';
		$this->assertEqual($this->fakeRestService->options->getParams(), $expectedParams);
	}
	
	function testResultReturnedFromParser() {
		$this->assertEqual($this->searchResult->totalMatches, 1);
		$records = $this->searchResult->getRecords();
		$pi = $records[0];
		$this->assertEqual($pi->manufacturer, 'Toyota');
	}
}

class FakeRestService implements RestApi {
	public $options = NULL;
	
	public function forUrl($url) {
		return new RestOptions($url);
	}
	
	public function get($restOptions) {
		$this->options = $restOptions;
		return new RequestResult();
	}
}

class FakeRequestParser {
	function parse($result) {
		$searchResult = new SearchResult();
		$searchResult->totalMatches = 1;
		$searchResult->success = true;
		
		$info = new ProductInfo();
		$info->manufacturer = 'Toyota';
		
		$searchResult->setRecords(array($info));
		return $searchResult;
	}
}
?>