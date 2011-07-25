<?php

require_once('../../simpletest/autorun.php');
require_once('../../../utils/forms.php');

class WhenNoOptionsSuppliedToTextField extends UnitTestCase {
	
	function testAnInputFieldIsReturned() {
		$output = rasTextField();
		$this->assertEqual($output, '<input type="text" />');
	}
	
	function testAnInputFieldIsReturnedAndNotANullReferenceIssue() {
		$output = rasTextField(null);
		$this->assertEqual($output, '<input type="text" />');
	}
	
	function testAnInputFieldIsReturnedWithNoAttributes() {
		$output = rasTextField(array());
		$this->assertEqual($output, '<input type="text" />');
	}
	
}

?>