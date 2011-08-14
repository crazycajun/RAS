<?php

// Automobile Search Form.
class AutoSearchForm implements SearchForm {
	
	private $query;
	
	public function echoForm($action) {
		echo "<p><form action=$action method=\"get\">";
		
		$query="";
		$make="";
		$model="";
		$year="";
		
		if(!empty($_GET)) {
			$query = $_GET['query'];
			$make = $_GET['make'];
			$model = $_GET['model'];
			$year = $_GET['year'];
		}
		
		echo rasLabel("Query:&nbsp;", "query");
		echo rasTextField(array(
				'name' => 'query',
				'id' => 'query',
				'value' => $query,
				'size' => 30
			)) . "<br />";
		
		echo rasRequiredLabel("Year:&nbsp;", "year");
		echo rasRequiredTextField(array(
				'name' => 'year',
				'id' => 'year',
				'value' => $year,
				'size' => '4',
				'maxlength' => '4'
			));
		
		echo rasLabel("Make:&nbsp;", "make");
		echo rasTextField(array(
				'name' => 'make',
				'id' => 'id',
				'value' => $make,
				'size' => '15'
			));
		
		echo rasLabel("Model:&nbsp;", "model");
		echo rasTextField(array(
				'name' => 'model',
				'id' => 'model',
				'value' => $model,
				'size' => '15'
			));
		
		echo rasInputField("hidden", array(
				'name' => 'page',
				'id' => 'page',
				'value' => (isset($this->query) ? $this->query->page : 1)
			));
		
		/*echo "Query:&nbsp;<input type=\"text\" name=\"query\" value=\"$query\" size=\"30\" /><br />";
		echo "Make:&nbsp;<input type=\"text\" name=\"make\" value=\"$make\" size=\"15\" />";
		echo "Model:&nbsp;<input type=\"text\" name=\"model\" value=\"$model\" size=\"15\" />";
		echo "Year:&nbsp;<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlength=\"4\" />";
		echo "<input type=\"hidden\" name=\"page\" value=\"" . (isset($this->query) ? $this->query->page : 1) . "\" />\n";
		*/echo "<br /><button type=\"submit\">Submit</button></form></p>";
	}
	
	public function buildSearchQuery() {
		if(empty($_GET)) {
			throw new Exception("No form submitted.");
		}
		
		$this->query = new Query();
		
		$autoDetails = new AutoDetails();
		$autoDetails->make = $_GET['make'];
		$autoDetails->model = $_GET['model'];
		$autoDetails->year = $_GET['year'];
		
		$this->query->autoDetails = $autoDetails;
		$this->query->searchTerm = $_GET['query'];
		$this->query->page = $_GET['page'];
		
		return $this->query;
	}
	
	public function echoResults($results) {
		if($results->totalMatches == 0) {
			echo "<h2>No Results!</h2>";
			echo "<p>Congradulations! We could find <em>no recalls</em> based on the search criteria.</p>\n";
			echo "<p>This could mean a few things:\n";
			echo "<ol><li>There actually are no recalls on the product you are searching for.</li>\n";
			echo "<li>Your search query is too specific and is hiding a result you <em>are</em> searching for.</li>\n";
			echo "<li>There is a recall, however, we have no record of it.</li></ol></p>\n";
		} else if($results->success) {
			echo "<h2>Results</h2>";
			echo "<p><table>\n";
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
			echo "</table></p>";
			$this->echoPagination($results);
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
	
	private function echoPagination($results) {
		if($results->totalMatches > 10) {
			$curPage = $_GET['page'];
			echo "<div align=\"center\"><p>\n";
			if($curPage == 1) {
				echo "&lt;&lt;First&nbsp;";
				echo "&lt;Prev&nbsp;";
			} else {
				echo "<a href=\"" . $this->getSearchURI(1) . "\">&lt;&lt;First&nbsp;";
				echo "<a href=\"" . $this->getSearchURI($curPage - 1) . "\">&lt;Prev&nbsp;";
			}
			
			$totalPages = ceil($results->totalMatches/10);
			
			
			for($page=1; $page<=$totalPages; $page++) {
				if($page == $curPage) {
					echo "$page&nbsp;";
				} else {
					echo "<a href=\"" . $this->getSearchURI($page) . "\">$page&nbsp;";
				}
			}
			
			if($curPage == $totalPages) {
				echo "Next&gt;&nbsp;";
				echo "Last&gt;&gt;&nbsp;";
			} else {
				echo "<a href=\"" . $this->getSearchURI($curPage + 1) . "\">Next&gt;&nbsp;";
				echo "<a href=\"" . $this->getSearchURI($totalPages) . "\">Last&gt;&gt;&nbsp;";
			}
			echo "</p></div>\n";
		}
	}
	
	private function getSearchURI($page) {
		return "search.php?query=" . $_GET['query'] . "&make=" . $_GET['make'] . 
						"&model=" . $_GET['model'] . "&year=" . $_GET['year'] .
						"&page=" . $page;
	}
}

?>