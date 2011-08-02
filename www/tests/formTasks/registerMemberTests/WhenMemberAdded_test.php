<?php

require_once('RegisterMemberTaskTestBase.php');

class WhenMemberAdded extends RegisterMemberTaskTestBase {
	function testRepositoryAddIsCalledWithPostParameters() {
		$this->setValidInputValuesIntoPost();

		$this->repository->expectOnce('add', 
			array("Brian Chiasson", "myemail@domain.com", "who knew that this would be my password?"));
		$this->task->execute();
	}
	
	function testUserIsRedirectedToRegisterSuccessUrl() {
		$this->setValidInputValuesIntoPost();
		
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'registerSuccess.php');
	}
	
	function testRegistrationTokenSentViaEmailService() {
		$this->setValidInputValuesIntoPost();
		$this->repository->returns('add', 'registrationToken');
		
		$this->emailService->expectOnce('sendMemberConfirm', 
			array("myemail@domain.com", "registrationToken"));
		$this->task->execute();
	}
	
	function setValidInputValuesIntoPost() {
		$_POST["memberName"] = "Brian Chiasson";
		$_POST["memberEmail"] = "myemail@domain.com";
		$_POST["memberPassword"] = "who knew that this would be my password?";
		$_POST["memberPasswordConfirm"] = "who knew that this would be my password?";
	}
}

?>