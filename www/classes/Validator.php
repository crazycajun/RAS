<?php

// The validation builder used in all form tasks to perform input validation.
class Validator {
	private $validators = array();
	
	// Ensures that the specified field is in the POST collection and has a
	// non-whitespace value.
	function required($field, $friendlyName) {
		$this->validators[] = new NotEmptyValidator($field, $friendlyName);
		return $this;
	}
	
	// Verifies that the password field is in the POST collection and has 
	// a non-whitespace value. It also compares the password with a 
	// a comparison field that should be in the POST. The field name is 
	// based on the site's convention that password confirmation fields 
	// have "Confirm" appended to the end of the password field's POST name. 
	function passwordWithConfirm($field, $friendlyName) {
		$this->validators[] = new NotEmptyValidator(
			$field, 
			$friendlyName,
			new ValuesMatchValidator($field . "Confirm", $field, "The passwords do not match."));
		return $this;
	}
	
	// Verifies that the email field is in the POST and is in the email
	// format using PHP's built-in filtering mechanism.
	function requiredEmail($field, $friendlyName) {
		$this->validators[] = new NotEmptyValidator(
			$field, 
			$friendlyName, 
			new EmailFilterValidator($field, $friendlyName));
		return $this;
	}
	
	// Executes the constructed validators and returns a ValidationResult
	// that indicates valid or invalid.
	function run() {
		$messages = array();
		foreach($this->validators as $validator) {
			$validator->validate($messages);
		}
		return new ValidationResult($messages);
	}
}

// The class that is used to ensure that a field has a value in the
// POST collection.
class NotEmptyValidator {
	private $field;
	private $friendlyName;
	private $customValidator;
	
	function __construct($field, $friendlyName, $customValidator = null) {
		$this->field = $field;
		$this->friendlyName = $friendlyName;
		$this->customValidator = $customValidator;
	}
	
	function validate(&$messages) {
		if (array_key_exists($this->field, $_POST) && strlen(trim($_POST[$this->field])) > 0) {
			if (isset($this->customValidator)) $this->customValidator->validate($messages);
			return;
		}
		
		$messages[] = "Please enter " . $this->friendlyName . ".";
	}
}

// The class ensures that the field in the POST collection is in the 
// email format using the PHP filter_var method.
class EmailFilterValidator {
	private $field;
	private $friendlyName;
	private $customValidator;
	
	function __construct($field, $friendlyName, $customValidator = null) {
		$this->field = $field;
		$this->friendlyName = $friendlyName;
		$this->customValidator = $customValidator;
	}
	
	function validate(&$messages) {
		if (array_key_exists($this->field, $_POST) && filter_var($_POST[$this->field], FILTER_VALIDATE_EMAIL)) {
			if (isset($this->customValidator)) $this->customValidator->validate($messages);
			return;
		}
		
		$messages[] = "Please enter a valid " . ltrim($this->friendlyName, "an ") . ".";
	}
}

// The class that is used to compare two fields in the POST collection. 
// Note that the comparison will not occur if the primary field is not 
// in the POST collection.
class ValuesMatchValidator {
	private $field;
	private $comparisonField;
	private $errorMessage;
	private $customValidator;
	
	function __construct($field, $comparisonField, $errorMessage, $customValidator = null) {
		$this->field = $field;
		$this->comparisonField = $comparisonField;
		$this->errorMessage = $errorMessage;
		$this->customValidator = $customValidator;
	}
	
	function validate(&$messages) {
		// We don't validate if the comparison field is not found.
		if (! array_key_exists($this->comparisonField, $_POST)) {
			return;
		}
		
		if (array_key_exists($this->field, $_POST) && $_POST[$this->comparisonField] == $_POST[$this->field]) {
			if (isset($this->customValidator)) $this->customValidator->validate($messages);
			return;
		}
		
		$messages[] = $this->errorMessage;
	}
	
}

// The class returned from the validator.
class ValidationResult {
	private $messages;
	
	function __construct($messages) {
		$this->messages = $messages;
	}
	
	function failed() {
		return count($this->messages) > 0;
	}
	
	function isValid() {
		return !$this->failed();
	}
	
	function messages() {
		return $this->messages;
	}
}

?>