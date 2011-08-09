<?php

// This class is responsible for handling the interaction
// between system dependencies to activate a user account.
class ActivateMemberTask {
	private $messenger;
	private $phpHelpers;
	private $repository;
		
	const EMAIL_KEY = 'memberEmail';
	const TOKEN_KEY = 'activationToken';

	function __construct($repository, $messenger, $phpHelpers) {
		$this->repository = $repository;
		$this->messenger = $messenger;
		$this->phpHelpers = $phpHelpers;
	}
	
	// This is the method that does the work for the task. All 
	// RAS tasks implement this method.
	function execute() {
		$validator = new Validator();
		$result = $validator->required(self::TOKEN_KEY, "an activation token")
			->requiredEmail(self::EMAIL_KEY, "an email address")
			->run();
		
		if ($result->isValid()) {
			$activated = $this->repository->activate(
				 $_POST[self::EMAIL_KEY]
				,$_POST[self::TOKEN_KEY]);
			
			if ($activated)
				$this->phpHelpers->redirect('activateSuccess.php');
			else
				$this->failWithMessages(array('Activation failed, check the token and the account information.'));			
		}
		else {
			$this->failWithMessages($result->messages());
		}
	}
	
	// Sets the flash messsages and redirects to the appropriate page.
	function failWithMessages($messages) {
		$this->messenger->addMessages($messages);
		$urlParams = $this->buildParams();
		$this->phpHelpers->redirect('activate.php?' . http_build_query($urlParams));
	}
	
	// Creates the parameter array from the post data.
	function buildParams() {
		$params = array();
		if (array_key_exists(self::EMAIL_KEY, $_POST))
			$params['e'] = $_POST[self::EMAIL_KEY];
		
		if (array_key_exists(self::TOKEN_KEY, $_POST))
			$params['t'] = $_POST[self::TOKEN_KEY];
		
		return $params;
	}
}