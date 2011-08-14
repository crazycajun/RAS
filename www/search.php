<?php

	// Include template header.
	$pageName="search";
	include_once('templates/header.php');

	require_once('classes/FlashMessenger.php');
	require_once('utils/forms.php');
	require_once('ApplicationConfiguration.php');
	require_once('ProductInfo.php');
	require_once('usa_search/Query.php');
	require_once('forms/SearchForm.php');
	require_once('forms/AutoSearchForm.php');
	require_once('usa_search/RestApi.php');
	require_once('usa_search/CurlRestApi.php');
	require_once('usa_search/UsaSearchResultParser.php');
	require_once('usa_search/UsaSearch.php');
	
	echo "<div id=\"search\">\n";
	echo "<h1>Recall Search</h1>\n";
	echo "<p>Enter your search criteria below. You may either enter a search query, specific auto details, or a mixture of both.</p>\n";
	
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
	
	echo "</div>\n";
	
	// Include the footer.
	include_once('templates/footer.php');
?>