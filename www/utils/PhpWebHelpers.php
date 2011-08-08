<?php

class PhpWebHelpers implements iPhpWebHelpers {
	function redirect($url) {
		header( 'Location: ' . $url );
	}
}