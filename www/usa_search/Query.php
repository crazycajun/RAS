<?php

// The class that gets converted into the JSON request object for 
// the USA Search REST API.
class Query {
	// Free text search input option.
	public $searchTerm = NULL;
	
	// Details for an automotive search.
	public $autoDetails = NULL;
}

// The details to make searching against the API for auto recalls quicker.
class AutoDetails {
	public $make;
	public $model;
	public $year;
}

// This class contains the details of the query's result.
class SearchResult {
	// The total number of records that matched the query.
	public $totalMathces;
	
	// The parsed product recalls. This will be an array of 
	// ProductInfo records and will never be null.
	private $records;
	
	// Indicates whether or not the query was executed successfully. This does 
	// not necessarily mean that records were returned.
	public $success;
	
	// Indicates that an error occurred in the system. This should always be the 
	// opposite of success.
	public $failed;
	
	// The collection of errors.
	public $errors;
	
	function __construct() {
		$records = array();
	}
	
	// The parsed product recalls. This will be an array of 
	// ProductInfo records and will never be null.
	function getRecords() {
		return $this->records;
	}
	
	// This method is used to set the records returned. We are using
	// getters/setters here to facilitate the requirement that the field
	// should never be null.
	function setRecords($records) {
		if (is_null($records)) return;
		$this->records = $records;
	}
}