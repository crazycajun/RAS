<?php

require_once '../simpletest/autorun.php';
require_once '../../usa_search/RestApi.php';
require_once '../../usa_search/UsaSearch.php';
require_once '../../usa_search/Query.php';
require_once '../../ApplicationConfiguration.php';

class UsaSearchBuildParams extends UnitTestCase {
	
	private $params;
	private $config;
	
	function setUp() {
		$search = new UsaSearch(null);
		$query = new Query();
		$query->searchTerm = "benjamins baby!!";
		$query->page = 6;
		
		$autoDetails = new AutoDetails();
		$autoDetails->make = 'Ford';
		$autoDetails->model = 'Taurus';
		$autoDetails->year = 2011;
		$query->autoDetails = $autoDetails;
		
		$this->config = new ApplicationConfiguration();
		$this->params = $search->buildParamsArray($query);
	}
	
	function testContainsApiKey() {
		$this->assertKeyValueInParams('api_key', $this->config->UsaSearchApiKey);
	}
	
	function testContainsSearchFormat() {
		$this->assertKeyValueInParams('format', $this->config->UsaSearchFormat);
	}
	
	function testContainsSearchTerm() {
		$this->assertKeyValueInParams('query', 'benjamins baby!!');
	} 
	
	function testContainsPageNumber() {
		$this->assertKeyValueInParams('page', 6);
	}
	
	function testContainsModel() {
		$this->assertKeyValueInParams('model', 'Taurus');
	}
	
	function testContainsMake() {
		$this->assertKeyValueInParams('make', 'Ford');
	}
	
	function testContainsYear() {
		$this->assertKeyValueInParams('year', 2011);
	}
	
	function testDoesNotFailWhenQueryIsEmpty() {
		$query = new Query();
		$search = new UsaSearch(null);
		$this->params = $search->buildParamsArray($query);
		$this->assertKeyMissing('query', null);
		$this->assertKeyMissing('make', null);
		$this->assertKeyMissing('model', null);
		$this->assertKeyMissing('year', null);
	}
	
	function assertKeyValueInParams($key, $value) {
		$this->assertTrue(array_key_exists($key, $this->params), 'The array does not contain the key: ' . $key);
		$this->assertEqual($this->params[$key], $value);
	}
	
	function assertKeyMissing($key) {
		$this->assertFalse(array_key_exists($key, $this->params), 'The array should not contain the key: ' . $key);
	}
}

?>