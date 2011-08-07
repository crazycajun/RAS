<?php

	require_once('forms/AutoSearchForm.php');
	require_once('usa_search/UsaSearch.php');
	require_once('usa_search/Query.php');
	
	
	$autoSearchForm = new AutoSearchForm();
	$autoSearchForm->echoForm("search.php");
	
	if(!empty($_GET)) {
		$query = new Query();
		
		$autoDetails = new AutoDetails();
		$autoDetails->make = $_GET['make'];
		$autoDetails->model = $_GET['model'];
		$autoDetails->year = $_GET['year'];
		
		$query->autoDetails = $autoDetails;
		$usaSearch = new UsaSearch();
		$results = $usaSearch->search($query);
		
		if($results->success) {
			echo "<h1>Results</h1>";
			foreach($results->getRecords() as $rec) {
				echo "<p>$rec</p><hr />";
			}
		} else {
			echo "<font color=\"red\"><h1>Errors</h1>";
			foreach($results->errors as $err) {
				echo "<p>$err</p>";
			}
			echo "</font>";
		}
	}
	
?>