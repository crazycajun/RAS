<?php

// A class that wraps common PHP, web specific methods to help facilitate
// unit testing.
class PhpWebHelpers implements iPhpWebHelpers {
	
	// Writes the appropriate php header to cause the user's browser to
	// redirect to the specific page. Note that this method will fail if any
	// output has been sent to the server.
	function redirect($url) {
		header( 'Location: ' . $url );
	}
}