<?php

require_once('../../simpletest/autorun.php');
require_once('../../../utils/forms.php');

class WhenClassesAreAndAreNotSpecifiedForRequiredTextField extends UnitTestCase {
	function testClassIsAddedToAlreadySpecifiedClass() {
		$field = rasRequiredTextField(array('class' => 'foo'));
		$this->assertPattern('/class="foo required"/', $field);
	}
	
	function testClassIsAddedToMakeFieldRequired() {
		$field = rasRequiredTextField();
		$this->assertPattern('/class="required"/', $field);
	}
	
	function testInputTextFieldIsCreated() {
		$field = rasRequiredTextField();
		$this->assertPattern('/<input type="text"/', $field);
	}
	
	function testOtherAttributesAreAddedToField() {
		$field = rasRequiredTextField(array("name" => "beetle"));
		$this->assertPattern('/name="beetle"/', $field);
	}
}

?>