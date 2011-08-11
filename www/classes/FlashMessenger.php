<?php

// This class encapsulates interaction with the messaging system to allow
// communication between FORM task handler scripts and the FORM pages.
class FlashMessenger {
	const FLASH_SESSION_KEY = 'rasFlash';
	
	function addMessages($messages) {
		return $_SESSION[self::FLASH_SESSION_KEY] = $messages;
	}
	
	// Returns the messages in the flash as an array of strings. If nothing
	// is found in the flash an empty array is returned.
	function flash() {
		if (array_key_exists(self::FLASH_SESSION_KEY, $_SESSION)) {
			$messages = $_SESSION[self::FLASH_SESSION_KEY];
			unset($_SESSION[self::FLASH_SESSION_KEY]);
			return isset($messages) ? $messages : array();
		}
		
		return array();
	}
}

?>