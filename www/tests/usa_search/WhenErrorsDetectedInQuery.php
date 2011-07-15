<?php
require_once '../simpletest/autorun.php';
require_once '../../usa_search/UsaSearch.php';

class WhenErrorsDetectedInQuery extends UnitTestCase {

	function testExceptionThrownForNoQueryEntity() {
		$api = new UsaSearch();
		$this->expectException(new InvalidArgumentException('A Query entity must be provided to perform the search operation.'));
		$api->search(NULL);	
	}
	
}

?>