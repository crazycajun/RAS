<?php

require_once('../../simpletest/autorun.php');

class AllFormTests extends TestSuite {
    function __construct() {
        parent::__construct();
        $this->collect(dirname(__FILE__), new SimplePatternCollector('/_test.php/'));
    }
}
?>