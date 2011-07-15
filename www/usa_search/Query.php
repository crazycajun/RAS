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