<?php

// This class is responsible for handling the interaction
// between system dependencies to add a user to the RAS
// system. Once the user has been added, the task sends the
// user an account activation email.
class RegisterMemberTask {
	private $messenger;
	private $phpHelpers;
	private $repository;
	private $emailService;
	
	const NAME_KEY = 'memberName';
	const EMAIL_KEY = 'memberEmail';
	const PWD_KEY = 'memberPassword';

	function __construct($repository, $messenger, $phpHelpers, $emailService) {
		$this->repository = $repository;
		$this->messenger = $messenger;
		$this->phpHelpers = $phpHelpers;
		$this->emailService = $emailService;
	}
	
	// This is the method that does the work for the task. All 
	// RAS tasks implement this method.
	function execute() {
		echo 'validating...<br />';
		$validator = new Validator();
		$result = $validator->required(self::NAME_KEY, "a name")
			->requiredEmail(self::EMAIL_KEY, "an email address")
			->passwordWithConfirm(self::PWD_KEY, "a password")
			->run();
		
		if ($result->isValid()) {
			$token = $this->repository->add(
				$_POST[self::NAME_KEY], $_POST[self::EMAIL_KEY], 
				$_POST[self::PWD_KEY]);
			
			$this->emailService->sendMemberConfirm($_POST[self::EMAIL_KEY], $token);
			$this->phpHelpers->redirect('registerSuccess.php');
		}
		else {
			$this->messenger->addMessages($result->messages());
			$urlParams = $this->buildParams();
			$this->phpHelpers->redirect('register.php?' . http_build_query($urlParams));
		}
	}
	
	// Creates the parameter array from the post data.
	function buildParams() {
		$params = array();
		if (array_key_exists(self::NAME_KEY, $_POST))
			$params['n'] = $_POST[self::NAME_KEY];
		
		if (array_key_exists(self::EMAIL_KEY, $_POST))
			$params['e'] = $_POST[self::EMAIL_KEY];
		
		return $params;
	}
}

?>