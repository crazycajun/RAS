<?php
// Interface for wrapping common PHP web methods.
interface iPhpWebHelpers {
	// Encapsulates the behavior required to redirect the user to 
	// another page.
	public function redirect($url);
}
?>