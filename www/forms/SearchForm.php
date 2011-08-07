<?php

interface SearchForm {
	public function echoForm($action);
	
	public function buildSearchQuery();
}

?>