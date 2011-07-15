<?php

class UsaSearch {
	function search($query) {
		$this->validate($query);
	}
	
	private function validate($query) {
		if (is_null($query)) throw new InvalidArgumentException('A Query entity must be provided to perform the search operation.');
		if (empty($query->searchTerm)) throw new InvalidArgumentException('The query must have a searchTerm or autoDetails provided.');
	}
}
?>