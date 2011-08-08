<?php

// Accepts the attributes and returns a string to be
// used as output to build a password field with a corresponding
// confirm field.
function rasRequiredPassword($attrs = null) {
	$opts = rasEnsureArray($attrs);
	rasAddRequiredClass($opts);
	return rasPasswordField($opts);
}

// Builds a password input field based on the attrs specified.
function rasPasswordField($attrs = null) {
	$opts = rasEnsureArray($attrs);
	if(array_key_exists('value', $opts)) {
		unset($opts['value']);
	}
	return rasInputField('password', $opts);
}

// Renders a password field that will require confirmation with 
// the field identified as the parameter using JQuery.validate.
function rasPasswordConfirmation($comparisonField) {
	$attrs = array(
		 'id' => $comparisonField . "Confirm"
		,'name' => $comparisonField . "Confirm"
		,'equalTo' => '#' . $comparisonField
	);
	return rasPasswordField($attrs);
}

// Accepts the specified attributes as input and outputs a 
// text field with the meta data and adds meta data to make 
// the field required on the UI.
function rasRequiredTextField($attrs = null) {
	$opts = rasEnsureArray($attrs);		
	rasAddRequiredClass($opts);
	return rasTextField($opts);
}

// Adds the CSS class definition to make the element required
// with jQuery.validate plugin.
function rasAddRequiredClass(&$opts) {
	$class = array_key_exists('class', $opts) ? $opts['class'] . ' required' : 'required';
	$opts['class'] = $class;
}

// Accepts the specified attributes as input and outputs a 
// text field with the meta data.
function rasTextField($attrs = null) {
	$opts = rasEnsureArray($attrs);	
	return rasInputField('text', $opts);
}

// Builds the specified input type for output.
function rasInputField($type, $opts) {		
	$pairs = '';
	foreach($opts as $attr => $value) {
		$pairs .= $attr . '="';
		$pairs .= strtolower($attr) == 'value' ? addslashes($value) : $value;
		$pairs .= '" ';
	}
	return '<input type="' . $type . '" ' . $pairs . '/>';
}

// Outputs a form label for the specified field. Setting the optional
// value of isRequired to true will add an asterisk to the right of the
// text in the label.
function rasLabel($text, $fieldId, $isRequired = false) {
	return '<label for="' . $fieldId .'">' . $text 
		. ($isRequired ? ' <span class="rasRequiredAsterisk">*</span>' : '') . '</label>';
}

// Convenience method so that it's easy to spot what "true" means when calling rasLabel.
function rasRequiredLabel($text, $fieldId) {
	return rasLabel($text, $fieldId, true /* field is required */);
}

// Helper function to avoid null/type checks on array input values.
function rasEnsureArray($value) {
	$opts = array();
	if(isset($value) && is_array($value)) $opts = $value;
	return $opts;
}

// Reads the flash from the session and displays it if it has contents.
function rasFlash() {
	$flashMessenger = new FlashMessenger();
	$messages = $flashMessenger->flash();

	if (count($messages) > 0) {
$flashHeader = <<<FLASHHEADER
	<div class="ui-widget">
		<div class="ui-state-highlight ui-corner-all ras-ui-flash">
			<ul>
FLASHHEADER;
		echo $flashHeader;

		foreach($messages as $message) {
			echo '<li>' . $message . '</li>';
		}
		
$flashFooter = <<<FLASHFOOTER
			</ul>	
		</div>
	</div>
FLASHFOOTER;
		echo $flashFooter;
	}
}

?>