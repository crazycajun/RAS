<?php
require_once '../simpletest/autorun.php';
require_once '../../usa_search/RestApi.php';

class RestOptionsTests extends UnitTestCase {
	private $options = NULL;
	
	function setUp() {
		$this->options = new RestOptions('http://www.google.com');
	}
	
	function testUrlIsSet() {
		$this->assertEqual($this->options->url, 'http://www.google.com');
	}
	
	function testJsonArrayIsJson() {
		$input = array(
			'foo' => 'bar',
			'pie' => 'cherry',
			'v' => 6
		);
		
		$this->options->json($input);
		$this->assertEqual($this->options->params, '{"foo":"bar","pie":"cherry","v":6}');
	}
	
	function testJsonArrayReturnsRestOptionsInstance() {
		$instance = $this->options->json(array("a" => "b"));
		$this->assertIdentical($instance, $this->options);
	}
	
	function testJsonSetsContentType() {
		$this->assertEqual(RestOptions::JsonContentType, $this->options->json(array("c" => "d"))->contentType);
	}
}
?>