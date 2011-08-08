<?php

// Interface for all search forms.
interface SearchForm {
	// Display the form.
	public function echoForm($action);
	
	// Build the Query.
	public function buildSearchQuery();
	
	// Display the results.
	public function echoResults($results);
	
}

?>