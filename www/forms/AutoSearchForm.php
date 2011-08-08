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
			echo "<table>\n";
			echo "\t<tr>";
			echo "\t\t<th>Description</th>\n";
			echo "\t\t<th>Manufacture</th>\n";
			echo "\t\t<th>Date Recalled</th>\n";
			echo "\t\t<th>URL</th>\n";
			echo "\t</tr>\n";
			
			foreach($results->getRecords() as $rec) {
				echo "\t<tr>\n";
				if(isset($rec->description))
					echo "\t\t<td>" . $rec->description . "</td>\n";
				if(isset($rec->manufacturer))
					echo "\t\t<td>" . $rec->manufacturer . "</td>\n";
				if(isset($rec->recalledOn))
					echo "\t\t<td>" . $rec->recalledOn->format("n/j/Y") . "</td>\n";
				if(isset($rec->recallURL))
					echo "\t\t<td>" . $rec->recallURL . "</td>\n";
				echo "\t<tr>\n";
			}
			echo "</table>";
		} else {
			foreach($results->errors as $err) {
				if($err->succeeded()) {
					echo "<p>No results.</p>";
				} else {
					echo "<font color=\"red\"><h1>Error!</h1>\n";
					echo "<h3>HTTP Status Code " . $err->httpStatusCode . "</h3>\n";
					echo "<p>" . $err->responseBody . "</p></font>\n";
				}
			}
		}
	}
}

?>