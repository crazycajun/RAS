<?php

require_once('../simpletest/autorun.php');
require_once('../simpletest/mock_objects.php');
require_once('../../classes/FlashMessenger.php');
require_once('../../classes/Validator.php');
require_once('../../interfaces/iPhpWebHelpers.php');
require_once('../../interfaces/iUserAccountRepository.php');
require_once('../classes/PhpWebHelpersSpy.php');
require_once('../../classes/tasks/LoginMemberTask.php');

Mock::generate('FlashMessenger');
Mock::generate('iUserAccountRepository');

class LoginMemberTaskTests extends UnitTestCase {
	public $webHelpersSpy;
	public $task;
	public $flashMessenger;
	public $repository;
	const TEST_PWD = 'boooooo00';
   const AUTH_METHOD = 'authenticate';
	
	function setUp() {
		$_POST = array();
		$this->webHelpersSpy = new PhpWebHelpersSpy();
		$this->flashMessenger = new MockFlashMessenger();
		$this->repository = new MockiUserAccountRepository();
				
		$this->task = new LoginMemberTask(
			$this->repository, $this->flashMessenger, 
			$this->webHelpersSpy);
	}
	
	function testFlashMessengerIsUpdatedWithFailures() {
		$_POST["memberEmail"] = "myemail@domain.com";

		$this->flashMessenger->expect('addMessages', array(array("Please enter a password.")));
		$this->task->execute();
	}
	
	function testUserIsRedirectedToLoginPage() {
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'login.php?');
	}
	
	function testEmailIsInRedirectUrlWhenProvided() {
		$_POST["memberEmail"] = "myemail@domain.com";
				
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'login.php?e=myemail%40domain.com');
	}
	
	function testRepositoryAddIsCalledWithPostParameters() {
		$this->setValidInputValuesIntoPost();

		$this->repository->expectOnce(self::AUTH_METHOD, 
			array("myemail@domain.com", self::TEST_PWD));
		$this->task->execute();
	}
	
	function testUserIsRedirectedToRegisterSuccessUrl() {
		$this->setValidInputValuesIntoPost();
		
		$this->repository->returns(self::AUTH_METHOD, true);
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'loginSuccess.php');
	}
	
	function testUserIsRedirectedWhenActivationDoesNotSucceed() {
		$this->setValidInputValuesIntoPost();
		$this->repository->returns(self::AUTH_METHOD, false);
		$this->task->execute();
		$this->assertEqual($this->webHelpersSpy->redirectLocation, 'login.php?e=myemail%40domain.com');
	}
	
	function testFlashGetsLoginFailsMessage() {
		$this->setValidInputValuesIntoPost();
		$this->repository->returns(self::AUTH_METHOD, false);
		$this->flashMessenger->expect('addMessages', 
			array(array("Sorry, but we cannot verify the user and password combination.")));
		$this->task->execute();
	}
	
	function setValidInputValuesIntoPost() {
		$_POST["memberEmail"] = "myemail@domain.com";
		$_POST["memberPassword"] = self::TEST_PWD;
	}
}

?>