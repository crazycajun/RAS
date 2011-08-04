<?php
// This class is responsible for returning the current
// system date. This class is here to make testing dates
// easier in the unit tests.
class SystemClock {
	function now() {
		return new DateTime();
	}
}
?>