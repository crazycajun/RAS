<?php

// Automobile Search Form.
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
		
		$query = new Query();
		
		$autoDetails = new AutoDetails();
		$autoDetails->make = $_GET['make'];
		$autoDetails->model = $_GET['model'];
		$autoDetails->year = $_GET['year'];
		
		$query->autoDetails = $autoDetails;
		
		return $query;
	}
	
	public function echoResults($results) {
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
}

?>