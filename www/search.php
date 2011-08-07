<?php

	// Include template header.
	$pageName="Search";
	include('templates/header.php');

	require_once('forms/AutoSearchForm.php');
	require_once('usa_search/UsaSearch.php');
	require_once('usa_search/Query.php');
	
	
	// Create the auto search form and display it.
	$autoSearchForm = new AutoSearchForm();
	$autoSearchForm->echoForm("search.php");
	
	// If the form has been submitted, generate the
	// results and display them.
	if(!empty($_GET)) {
		$query = $autoSearchForm->buildSearchQuery();
		$usaSearch = new UsaSearch();
		$results = $usaSearch->search($query);
		
		$autoSearchForm->echoResults($results);
	}
	
	// Include the footer.
	include('templates/footer.php');
?>