<?php

require_once('../../simpletest/autorun.php');
require_once('../../../utils/forms.php');

class PasswordFieldBuilderTests extends UnitTestCase {
	function testValueProvidedIsStrippedFromOutput() {
		$field = rasRequiredPassword(array('value' => 'boo'));
		$this->assertEqual($field, '<input type="password" class="required" />');
	}
	
	function testIdOtherAttributesAreRendered() {
		$field = rasRequiredPassword(array('id' => 'pwd'));
		$this->assertEqual($field, '<input type="password" id="pwd" class="required" />');
	}
	
	function testConfirmationField() {
		$field = rasPasswordConfirmation('pwd');
		$this->assertEqual($field, '<input type="password" id="pwdConfirm" name="pwdConfirm" equalTo="#pwd" />');
	}
}

?>