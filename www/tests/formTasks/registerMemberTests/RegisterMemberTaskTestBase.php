<?php

require_once('../../simpletest/autorun.php');
require_once('../../simpletest/mock_objects.php');
require_once('../../../classes/FlashMessenger.php');
require_once('../../../classes/Validator.php');
require_once('../../../classes/EmailService.php');
require_once('../../../interfaces/iPhpWebHelpers.php');
require_once('../../../interfaces/iUserAccountRepository.php');
require_once('../../classes/PhpWebHelpersSpy.php');
require_once('../../../classes/tasks/RegisterMemberTask.php');

Mock::generate('FlashMessenger');
Mock::generate('EmailService');
Mock::generate('iUserAccountRepository');

abstract class RegisterMemberTaskTestBase extends UnitTestCase {
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
		$this->emailService = new MockEmailService();
		
		$this->task = new RegisterMemberTask(
			$this->repository, $this->flashMessenger, 
			$this->webHelpersSpy, $this->emailService);
	}
}