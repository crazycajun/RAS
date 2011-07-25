<?php
require_once('../../simpletest/autorun.php');
require_once('../../../utils/forms.php');

class WhenOtherOptionsSuppliedToTextField extends UnitTestCase {
	
	function testAllAttributesAreAdded() {
		$output = rasTextField(array(
			'name' => 'username',
			'id' => 'username',
			'maxlength' => 25
		));
		
		$this->assertPattern('/name="username" id="username" maxlength="25"/', $output);
	}
	
}