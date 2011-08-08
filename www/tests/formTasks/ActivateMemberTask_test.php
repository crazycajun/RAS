<?php

require_once('../simpletest/autorun.php');
require_once('../simpletest/mock_objects.php');
require_once('../../classes/FlashMessenger.php');
require_once('../../classes/Validator.php');
require_once('../../interfaces/iPhpWebHelpers.php');
require_once('../../interfaces/iUserAccountRepository.php');
require_once('../classes/PhpWebHelpersSpy.php');
require_once('../../classes/tasks/ActivateMemberTask.php');

Mock::generate('FlashMessenger');
Mock::generate('iUserAccountRepository');

class ActivateMemberTaskTests extends UnitTestCase {
	public $webHelpersSpy;
	public $task;
	public $flashMessenger;
	public $repository;
	public $emailService;
	
	function setUp() {
		$_POST = array();
		$this->webHelpersSpy = new PhpWebHelpersSpy();
		$this->flashMessenger = new MockFlashMessenger();
		$this->repository = new MockiUserAccountRepository();
				
		$this->task = new ActivateMemberTask(
			$this->repository, $this->flashMessenger, 
			$this->webHelpersSpy);
	}
	
	function testFlashMessengerIsUpdatedWithFailures() {
		$_POST["memberEmail"] = "myemail@domain.com";

		$this->flashMessenger->expect('addMessages', array(array("Please enter an activation token.")));
		$this->task->execute();
	}
	
	function testUserIsRedirectedToRegisterPage() {
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'activate.php?');
	}
	
	function testEmailIsInRedirectUrlWhenProvided() {
		$_POST["memberEmail"] = "myemail@domain.com";
				
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'activate.php?e=myemail%40domain.com');
	}
	
	function testTokenIsInRedirectUrlWhenProvided() {
		$_POST["activationToken"] = "mytoken";
				
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'activate.php?t=mytoken');
	}
	
	function testRepositoryAddIsCalledWithPostParameters() {
		$this->setValidInputValuesIntoPost();

		$this->repository->expectOnce('activate', 
			array("myemail@domain.com", "activation token"));
		$this->task->execute();
	}
	
	function testUserIsRedirectedToRegisterSuccessUrl() {
		$this->setValidInputValuesIntoPost();
		
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'activateSuccess.php');
	}
	
	function setValidInputValuesIntoPost() {
		$_POST["memberEmail"] = "myemail@domain.com";
		$_POST["activationToken"] = "activation token";
	}
}

?>