<?php

require_once('../../simpletest/autorun.php');
require_once('../../../utils/forms.php');

class RasLabel extends UnitTestCase {
	function testLabelOutputWithProvidedInfo() {
		$label = rasLabel('User name:', 'username');
		$this->assertEqual($label, '<label for="username">User name:</label>');
	}
	
	function testLabelHasAsteriskWhenRequired() {
		$label = rasLabel('User name:', 'username', true);
		$this->assertEqual($label, '<label for="username">User name: <span class="rasRequiredAsterisk">*</span></label>', $label);
	}
	
	function testConvenienceMethodAddsRequiredAsterisk() {
		$label = rasRequiredLabel('A', 'b');
		$this->assertEqual($label, '<label for="b">A <span class="rasRequiredAsterisk">*</span></label>');
	}
}

?>