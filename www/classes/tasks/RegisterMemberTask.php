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

	function __construct($repository, $messenger, $phpHelpers, $emailService) {
		$this->repository = $repository;
		$this->messenger = $messenger;
		$this->phpHelpers = $phpHelpers;
		$this->emailService = $emailService;
	}
	
	// This is the method that does the work for the task. All 
	// RAS tasks implement this method.
	function execute() {
		$validator = new Validator();
		$result = $validator->required("memberName", "a name")
			->requiredEmail("memberEmail", "an email address")
			->passwordWithConfirm("memberPassword", "a password")
			->run();
		
		if ($result->isValid()) {
			$token = $this->repository->add(
				$_POST["memberName"], $_POST["memberEmail"], 
				$_POST["memberPassword"]);
			
			$this->emailService->sendMemberConfirm($_POST["memberEmail"], $token);
			$this->phpHelpers->redirect('registerSuccess.php');
		}
		else {
			$this->messenger->addMessages($result->messages());
			$this->phpHelpers->redirect('register.php');
		}
	}
}

?>