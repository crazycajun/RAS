<?php

class FlashMessenger {
	const FLASH_SESSION_KEY = 'rasFlash';
	
	function addMessages($messages) {
		return $_SESSION[self::FLASH_SESSION_KEY] = $messages;
	}
	
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