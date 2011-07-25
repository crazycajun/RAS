<?php

require_once('../../simpletest/autorun.php');
require_once('../../../utils/forms.php');

class WhenValuesAreSuppliedToTextField extends UnitTestCase {
	const valueKey = 'value';
	
	function testSimpleTextValueIsWrittenToInputField() {
		$output = rasTextField(array(self::valueKey => 'my value'));
		$this->assertPattern("/value=\"my value\"/", $output);
	}
	
	function testHtmlValueIsWrittenToInputField() {
		$output = rasTextField(array(self::valueKey => '<p>Okay to output</p>'));
		$this->assertPattern("/value=\"<p>Okay to output<\/p>\"/", $output);
	}
	
	function testValueIsNotSusceptibleToInjectionForInputField() {
		$output = rasTextField(array(self::valueKey => '" /><script type="text\javascript">alert(\'We could be in trouble!\');</script><br'));
		$this->assertEqual($output, 
			'<input type="text" value="\" /><script type=\"text\\\\javascript\">alert(\\\'We could be in trouble!\\\');</script><br" />');
	}
}

?>