<?php 

require_once('../simpletest/autorun.php');
require_once('../../classes/TokenGenerator.php');

class TokenGeneratorTest extends UnitTestCase {
	private $generator;
	
	function setUp() {
		$this->generator = new TokenGenerator();
	}
	
	function testTokenIsOfExpectedSize() {
		$this->assertEqual(strlen($this->generator->getToken(12)), 12);
	}
	
	function testTokenGeneratedIsNotAllWhitespace() {
		$this->assertNotEqual(strlen(trim($this->generator->getToken())), 0);
	}
	
	function testMultipleTokensAreNotTheSame() {
		$token1 = $this->generator->getToken();
		$token2 = $this->generator->getToken();
		$this->assertNotEqual($token1, $token2, 'Should not get duplicate tokens.');
	}
}

?>