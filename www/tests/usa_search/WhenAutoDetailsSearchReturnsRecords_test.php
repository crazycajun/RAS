<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/UsaSearch.php';
require_once '../../usa_search/Query.php';
require_once '../../usa_search/RestApi.php';
require_once '../../ApplicationConfiguration.php';

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
		$api = new UsaSearch($this->fakeRestService);
		$this->searchResult = $api->search($this->query);
	}
	
	function testRestOptionsHasUrlFromConfiguration() {
		$this->assertEqual($this->fakeRestService->options->getUrl(), $this->config->UsaSearchUrl);
	}
	
	function testRestOptionsHasRequestTimeoutConfiguration() {
		$this->assertEqual($this->fakeRestService->options->getRequestTimeout(), $this->config->UsaRequestTimeoutInSeconds);
	}
	
	function testRestOptionsHasTheseParams() {
		$expectedParams = 'api_key=TODO%3A+Get+This&format=json&make=Toyota&model=Prius&page=1';
		$this->assertEqual($this->fakeRestService->options->getParams(), $expectedParams);
	}
	
	function testSearchResultIndicatesSuccess() {
		$this->assertTrue($this->searchResult->Succeeded, 'The search result did not indicate success.');
	}
}

class FakeRestService implements RestApi {
	public $options = NULL;
	public $result = NULL;
	
	public function forUrl($url) {
		return new RestOptions($url);
	}
	
	public function get($restOptions) {
		$this->options = $restOptions;
		return $this->result;
	}
}
?>