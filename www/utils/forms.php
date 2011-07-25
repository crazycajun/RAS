<?php

// Accepts the specified attributes as input and outputs a 
// text field with the meta data and adds meta data to make 
// the field required on the UI.
function rasRequiredTextField($attrs = null) {
	$opts = rasEnsureArray($attrs);		
	$class = array_key_exists('class', $opts) ? $opts['class'] . ' required' : 'required';
	$opts['class'] = $class;
	return rasTextField($opts);
}

// Accepts the specified attributes as input and outputs a 
// text field with the meta data.
function rasTextField($attrs = null) {
	$opts = rasEnsureArray($attrs);
		
	$pairs = '';
	foreach($opts as $attr => $value) {
		$pairs .= $attr . '="';
		$pairs .= strtolower($attr) == 'value' ? addslashes($value) : $value;
		$pairs .= '" ';
	}
	
	return '<input type="text" ' . $pairs . '/>';
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

?>