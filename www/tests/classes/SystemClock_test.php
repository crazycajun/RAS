<?php 

require_once('../simpletest/autorun.php');
require_once('../../classes/SystemClock.php');

class SystemClockTests extends UnitTestCase {
	const ACCEPTABLE_MARGIN_IN_SECS = 15;
	
	function testNowReturnsDateWithinFewSecondsOfCurrentDateTime() {
		$now = new DateTime(); // This initializes an object with the current date/time.
		$clock = new SystemClock();
		
		$intervalDifference = $now->diff($clock->now());
		$this->assertTrue($intervalDifference->s <= self::ACCEPTABLE_MARGIN_IN_SECS, 
			'The time difference in seconds is outside the acceptable margin!');
	}
}

?>