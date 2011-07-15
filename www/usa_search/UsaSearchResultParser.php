<?php

class UsaSearchResultParser {
	function parse($apiResponse) {
		$jsonResponse = json_decode($apiResponse);
		
		if (property_exists($jsonResponse, 'success')) {
			return $this->parseSuccess($jsonResponse);
		}
		
		return $this->parseError($jsonResponse);
	}
	
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
	
	function parseError($json) {
		$searchResult = new SearchResult();
		$searchResult->success = false;
		$searchResult->failed = true;
		$searchResult->errors = array($json->error);
		
		return $searchResult;
	}
	
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