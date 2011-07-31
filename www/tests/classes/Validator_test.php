<?php
require_once '../simpletest/autorun.php';
require_once '../../classes/Validator.php';

class ValidatorTests extends UnitTestCase {
	function setUp() {
		$_POST = array();
	}
	
	function testResultFailsWhenRequiredItemNotInPost() {
		$validator = new Validator();
		$result = $validator->required("name", "member name")->run();
		$this->assertTrue($result->failed(), "The validator didn't fail!!");
		$this->assertEqual($result->messages(), array("Please enter member name."));
	}
	
	function testResultFailsWhenRequiredItemIsEmpty() {
		$validator = new Validator();
		$_POST["name"] = '';
		$result = $validator->required("name", "member name")->run();
		$this->assertTrue($result->failed(), "The validator didn't fail!!");
	}
	
	function testResultFailsWhenRequiredItemIsWhitespace() {
		$validator = new Validator();
		$_POST["name"] = '            ';
		$result = $validator->required("name", "member name")->run();
		$this->assertTrue($result->failed(), "The validator didn't fail!!");
	}
	
	function testResultPassesWhenRequiredItemInPost() {
		$validator = new Validator();
		$_POST["name"] = "Benjamin Franklin";
		$result = $validator->required("name", "member name")->run();
		$this->assertTrue($result->isValid(), "The validator should have passed.");
	}
	
	function testResultFailsWhenEmailNotInPost() {
		$validator = new Validator();
		$result = $validator->requiredEmail("email", "an email address")->run();
		$this->assertTrue($result->failed(), "The validator didn't fail!!");
		$this->assertEqual($result->messages(), array("Please enter an email address."));
	}
	
	function testResultFailsWhenEmailIsNotEmail() {
		$validator = new Validator();
		$_POST["email"] = 'no way man';
		$result = $validator->requiredEmail("email", "an email address")->run();
		$this->assertTrue($result->failed(), "The validator didn't fail!!");
		$this->assertEqual($result->messages(), array("Please enter a valid email address."));
	}
	
	function testResultIsValidWhenEmailProvided() {
		$validator = new Validator();
		$_POST["email"] = 'myemail@domain.com';
		$result = $validator->requiredEmail("email", "an email address")->run();
		$this->assertTrue($result->isValid(), "The validator should have passed!");
	}
	
	function testResultHasAllFailureMessages() {
		$validator = new Validator();
		$_POST["email"] = 'no way man';
		$result = $validator->required("name", "name")->requiredEmail("email", "an email address")->run();
		$this->assertEqual($result->messages(), array(
			"Please enter name.",
			"Please enter a valid email address."
		));
	}
	
	function testResultFailsWhenPostDoesNotHaveKey() {
		$validator = new Validator();
		$result = $validator->passwordWithConfirm("memberPassword", "a password")->run();
		$this->assertTrue($result->failed(), "The validator didn't fail!!");
		$this->assertEqual($result->messages(), array("Please enter a password."));
	}
	
	function testResultFailsWhenComparisonFieldNotInPost() {
		$validator = new Validator();
		$_POST["memberPassword"] = "p@ssw0rd";
		$result = $validator->passwordWithConfirm("memberPassword", "a password")->run();
		$this->assertEqual($result->messages(), array("The passwords do not match."));
	}
	
	function testResultFailsWhenComparisonFieldDoesNotMatch() {
		$validator = new Validator();
		$_POST["memberPassword"] = "p@ssw0rd";
		$_POST["memberPasswordConfirm"] = "password";
		$result = $validator->passwordWithConfirm("memberPassword", "a password")->run();
		$this->assertEqual($result->messages(), array("The passwords do not match."));
	}
	
	function testResultIsValidWhenComparisonFieldMatchesPassword() {
		$validator = new Validator();
		$_POST["memberPassword"] = "p@ssw0rd";
		$_POST["memberPasswordConfirm"] = "p@ssw0rd";
		$result = $validator->passwordWithConfirm("memberPassword", "a password")->run();
		$this->assertTrue($result->isValid(), "The passwords should have matched.");
	}
}

?>