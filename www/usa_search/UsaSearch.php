<?php
class UsaSearch {
	function search($query) {
		if (is_null($query)) throw new InvalidArgumentException('A Query entity must be provided to perform the search operation.');
	}
}
?>