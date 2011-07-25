<?php

function rasRequiredTextField($attrs = null) {
	$opts = rasEnsureArray($attrs);		
	$class = array_key_exists('class', $opts) ? $opts['class'] . ' required' : 'required';
	$opts['class'] = $class;
	return rasTextField($opts);
}

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

function rasEnsureArray($value) {
	$opts = array();
	if(isset($value) && is_array($value)) $opts = $value;
	return $opts;
}

?>