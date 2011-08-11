<?php

// This class is responsible for parsing the raw contents returned from the
// search REST API.
class UsaSearchResultParser {
	
	// Takes the raw HTTP response from the REST API and translates it into an
	// instance of a SearchResult.
	function parse($apiResponse) {
		$jsonResponse = json_decode($apiResponse);
		
		if (property_exists($jsonResponse, 'success')) {
			return $this->parseSuccess($jsonResponse);
		}
		
		return $this->parseError($jsonResponse);
	}
	
	// The method used to parse successful search results.
	function parseSuccess($json) {
		$searchResult = new SearchResult();
		$searchResult->success = true;
		$searchResult->failed = false;
		
		$records = array();
		$results = $json->success->results;
		foreach ($results as $i => $record) {
			array_push($records, $this->parseRecord($record));
		}
		
		$searchResult->setRecords($records);
		$searchResult->totalMatches = $json->success->total;
		return $searchResult;
	}
	
	// The method used to parse failed search attempts.
	function parseError($json) {
		$searchResult = new SearchResult();
		$searchResult->success = false;
		$searchResult->failed = true;
		$searchResult->errors = array($json->error);
		
		return $searchResult;
	}
	
	// This method encapsulates the logic for converting a REST recall record
	// into a ProductInfo instance for use within the RAS application.
	function parseRecord($record) {
		$info = new ProductInfo();
		$info->manufacturer = $record->manufacturer;
		$info->description = $record->defect_summary;
		$info->recalledOn = date_create($record->recall_date);
		$info->recallUrl = $record->recall_url;
		return $info;
	}
} 

?>