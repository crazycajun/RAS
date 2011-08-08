<?php

require_once('../simpletest/autorun.php');
require_once('../../classes/FlashMessenger.php');

class FlashMessengerTests extends UnitTestCase {
	private $messenger;
	
	function setUp() {
		$_SESSION = array();
		$this->messenger = new FlashMessenger();
	}
	
	function testAddsMessagesToSession() {
		$messages = array('Message one', 'Message two');
		$this->messenger->addMessages($messages);
		$this->assertEqual($_SESSION[FlashMessenger::FLASH_SESSION_KEY], $messages);
	}
	
	function testReadingTheFlashRemovesItFromTheSession() {
		$this->messenger->addMessages(array('boo'));
		$this->messenger->flash();
		$this->assertFalse(array_key_exists(FlashMessenger::FLASH_SESSION_KEY, $_SESSION), 'The flash should no longer be in the session.');
	}
	
	function testReadingFlashReturnsEmptyArrayWhenNoMessages() {
		$this->assertEqual(count($this->messenger->flash()), 0);
	}
	
	function testReadingNullFlashReturnsEmptyArray() {
		$this->messenger->addMessages(null);
		$this->assertEqual(count($this->messenger->flash()), 0);
	}
	
	function testFlashReturnsSessionContents() {
		$messages = array('Message one', 'Message two');
		$this->messenger->addMessages($messages);
		$this->assertEqual($this->messenger->flash(), $messages);
	}
}