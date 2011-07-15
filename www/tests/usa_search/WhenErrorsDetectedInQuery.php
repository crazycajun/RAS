<?php
require_once '../simpletest/autorun.php';
require_once '../../usa_search/UsaSearch.php';
require_once '../../usa_search/Query.php';

class WhenErrorsDetectedInQuery extends UnitTestCase {

	function testExceptionThrownForNoQueryEntity() {
		$api = new UsaSearch();
		$this->expectException(new InvalidArgumentException('A Query entity must be provided to perform the search operation.'));
		$api->search(NULL);
	}
	
	function testExceptionThrownForQueryEntityWithoutSearchTermOrAutoDetails() {
		$api = new UsaSearch();
		$this->expectException(new InvalidArgumentException('The query must have a searchTerm or autoDetails provided.'));
		$api->search(new Query());
	}
}

?>