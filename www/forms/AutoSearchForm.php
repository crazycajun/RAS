<?php

require_once('SearchForm.php');

class AutoSearchForm implements SearchForm {
	
	public function echoForm($action) {
		echo "<form action=$action method=\"get\">";
		
		$make="";
		$model="";
		$year="";
		
		if(!empty($_GET)) {
			$make = $_GET['make'];
			$model = $_GET['model'];
			$year = $_GET['year'];
		}
		
		echo "Make: <input type=\"text\" name=\"make\" value=\"$make\" /><br />";
		echo "Model: <input type=\"text\" name=\"model\" value=\"$model\" /><br />";
		echo "Year: <input type=\"text\" name=\"year\" value=\"$year\" /><br />";
		echo "<input type=\"submit\" value=\"Submit\" /></form>";
	}
	
	public function buildSearchQuery() {
		if(empty($_GET)) {
			throw new Exception("No form submitted.");
		}
		
		
	}
}

?>