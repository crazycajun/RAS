<?php

function rasTextField($attrs = null) {
	if(isset($attrs) && is_array($attrs)) {
		$pairs = '';
		foreach($attrs as $attr => $value) {
			$pairs .= $attr . '="';
			$pairs .= strtolower($attr) == 'value' ? addslashes($value) : $value;
			$pairs .= '" ';
		}
		return '<input type="text" ' . $pairs . '/>';
	}
	
	return '<input type="text" />';
}

?>