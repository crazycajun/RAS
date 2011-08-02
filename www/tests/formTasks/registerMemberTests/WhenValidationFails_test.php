<?php

require_once('RegisterMemberTaskTestBase.php');

class WhenValidationFails extends RegisterMemberTaskTestBase {
	function testFlashMessengerIsUpdatedWithFailures() {
		$_POST["memberName"] = "Brian Chiasson";
		$_POST["memberEmail"] = "myemail@domain.com";

		$this->flashMessenger->expect('addMessages', array(array("Please enter a password.")));
		$this->task->execute();
	}
	
	function testUserIsRedirectedToRegisterPage() {
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'register.php');
	}
}



?>