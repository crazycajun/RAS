<?php

// The class used for spying on input to the PHP web helpers
// wrapper. This is to make the unit tests less reliant upon
// execution in a "web" infrastructure.
class PhpWebHelpersSpy implements iPhpWebHelpers {
	public $redirectLocation;
	
	function redirect($location) {
		$this->redirectLocation = $location;
	}
}

?>