<?php

// This class is responsible for handling the interaction
// between system dependencies to login a member.
class LoginMemberTask {
	private $messenger;
	private $phpHelpers;
	private $repository;
		
	const EMAIL_KEY = 'memberEmail';
	const PWD_KEY = 'memberPassword';

	function __construct($repository, $messenger, $phpHelpers) {
		$this->repository = $repository;
		$this->messenger = $messenger;
		$this->phpHelpers = $phpHelpers;
	}
	
	// This is the method that does the work for the task. All 
	// RAS tasks implement this method.
	function execute() {
		$validator = new Validator();
		$result = $validator->required(self::PWD_KEY, "a password")
			->requiredEmail(self::EMAIL_KEY, "an email address")
			->run();
		
		if ($result->isValid()) {
			$autheticated = $this->repository->authenticate(
				 $_POST[self::EMAIL_KEY]
				,$_POST[self::PWD_KEY]);
			
			if ($autheticated)
				$this->phpHelpers->redirect('loginSuccess.php');
			else
				$this->failWithMessages(array('Sorry, but we cannot verify the user and password combination.'));			
		}
		else {
			$this->failWithMessages($result->messages());
		}
	}
	
	// Sets the flash messsages and redirects to the appropriate page.
	function failWithMessages($messages) {
		$this->messenger->addMessages($messages);
		$urlParams = $this->buildParams();
		$this->phpHelpers->redirect('login.php?' . http_build_query($urlParams));
	}
	
	// Creates the parameter array from the post data.
	function buildParams() {
		$params = array();
		if (array_key_exists(self::EMAIL_KEY, $_POST))
			$params['e'] = $_POST[self::EMAIL_KEY];
		
		return $params;
	}
}