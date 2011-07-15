<?php 

require_once '../simpletest/autorun.php';
require_once '../../usa_search/Query.php';
require_once '../../ProductInfo.php';
require_once '../../usa_search/UsaSearchResultParser.php';

class UsaSearchResultParseSuccessTests extends UnitTestCase {
	private $result;
	
	private $success = <<<SUCC
{
	"success": {
		"results":[{
			"recall_url":"http://www-odi.nhtsa.dot.gov/recalls/recallresults.cfm?start=1&SearchType=QuickSearch&rcl_ID=10V035000&summary=true&PrintVersion=YES"
			,"defect_summary":"GULF STATES TOYOTA IS RECALLING CERTAIN MODEL YEAR 2006-2009 SIENNA PASSENGER VEHICLES FOR FAILING TO COMPLY WITH THE REQUIREMENTS OF FEDERAL MOTOR VEHICLE SAFETY STANDARD NO. 110, \"TIRE SELECTION AND RIMS.\"  THESE VEHICLES WERE SOLD BETWEEN SEPTEMBER 1, "
			,"manufacturer":"GULF STATES TOYOTA, INC."
			,"report_date":"20100203"
			,"recall_number":"10V035000"
			,"corrective_summary":"GULF STATES TOYOTA WILL MAIL OWNERS CORRECTED LABLES FREE OF CHARGE.  THE CAMPAIGN IS EXPECTED TO BEGIN ON OR ABOUT APRIL 30, 2010.  OWNERS MAY CONTACT GULF STATES TOYOTA AT 1-713-580-3300."
			,"recall_subject":"LOAD CARRYING CAPACITY MOFIFICATION LABELS"
			,"organization":"NHTSA"
			,"code":"V"
			,"consequence_summary":"THE MANUFACTURER HAS NOT YET PROVIDED A CONSEQUENCE FOR THIS CAMPAIGN."
			,"recall_date":"2010-02-04"
			,"component_description":"EQUIPMENT:OTHER:LABELS"
			,"notes":"OWNERS MAY ALSO CONTACT THE NATIONAL HIGHWAY TRAFFIC SAFETY ADMINISTRATION'S VEHICLE SAFETY HOTLINE AT 1-888-327-4236 (TTY 1-800-424-9153), OR GO TO <A HREF=HTTP://WWW.SAFERCAR.GOV>HTTP://WWW.SAFERCAR.GOV</A> ."
			,"federal_motor_vehicle_safety_number":"110"
			,"initiator":"MFR"
			,"records":[{"manufacturer":"GULF STATES TOYOTA, INC.","recalled_component_id":"000035017000828770000000349","make":"GULF TOYOTA","model":"SIENNA","component_description":"EQUIPMENT:OTHER:LABELS","year":2008,"manufacturing_begin_date":null,"manufacturing_end_date":null},{"manufacturer":"GULF STATES TOYOTA, INC.","recalled_component_id":"000035017000828771000000349","make":"GULF TOYOTA","model":"SIENNA","component_description":"EQUIPMENT:OTHER:LABELS","year":2009,"manufacturing_begin_date":null,"manufacturing_end_date":null}]
			,"part_number":"571"
		}
		,{
			"notification_date":"20060406"
			,"recall_url":"http://www-odi.nhtsa.dot.gov/recalls/recallresults.cfm?start=1&SearchType=QuickSearch&rcl_ID=06V096000&summary=true&PrintVersion=YES"
			,"defect_summary":"ON CERTAIN VEHICLES, DUE TO IMPROPER ASSEMBLY OF THE AIR BAG INFLATOR, WHICH IS USED IN THE SIDE AIR BAG, THE CURTAIN SHIELD AIR BAG, AND THE KNEE AIR BAG ASSEMBLY, SOME INFLATORS WERE PRODUCED WITH AN INSUFFICIENT AMOUNT OF THE HEATING AGENTS NECESSARY F"
			,"manufacturer":"TOYOTA MOTOR NORTH AMERICA, INC."
			,"report_date":"20060403"
			,"recall_number":"06V096000"
			,"corrective_summary":"DEALERS WILL REPLACE THE SPECIFIC SRS AIR BAG.  THE RECALL BEGAN ON APRIL 6, 2006.  OWNERS MAY CONTACT TOYOTA AT 1-888-270-9371, SCION AT 1-866-548-1851, OR LEXUS AT 1-800-255-3987."
			,"recall_subject":"AIR BAG INFLATOR"
			,"organization":"NHTSA"
			,"code":"V"
			,"consequence_summary":"THIS MAY INCREASE THE RISK OF INJURY TO THE OCCUPANT IN THE INVOLVED SEATING POSITION IN THE EVENT OF A CRASH."
			,"recall_date":"2006-04-06"
			,"component_description":"AIR BAGS"
			,"potential_units_affected":"133"
			,"notes":"TOYOTA RECALL NO. 60B AND LEXUS RECALL NO. 6LB.CUSTOMERS MAY CONTACT THE NATIONAL HIGHWAY TRAFFIC SAFETY ADMINISTRATION'S VEHICLE SAFETY HOTLINE AT 1-888-327-4236 (TTY: 1-800-424-9153); OR GO TO HTTP://WWW.SAFERCAR.GOV.","initiator":"MFR"
			,"manufacturer_campaign_number":"60B 6LB"
		}]
	,"total":2}
}
SUCC;
	
	private $records;
	
	function setUp() {
		$parser = new UsaSearchResultParser();
		$this->result = $parser->parse($this->success);
		$this->records = $this->result->getRecords();
	}
	
	function testParsedTwoRecords() {
		$this->assertEqual(count($this->records), 2);
	}
	
	function testFirstRecordParsed() {
		$firstRecord = $this->records[0];
		$this->assertEqual($firstRecord->manufacturer, 'GULF STATES TOYOTA, INC.');
		$this->assertEqual($firstRecord->description, "GULF STATES TOYOTA IS RECALLING CERTAIN MODEL YEAR 2006-2009 SIENNA PASSENGER VEHICLES FOR FAILING TO COMPLY WITH THE REQUIREMENTS OF FEDERAL MOTOR VEHICLE SAFETY STANDARD NO. 110, \"TIRE SELECTION AND RIMS.\"  THESE VEHICLES WERE SOLD BETWEEN SEPTEMBER 1, ");
		$this->assertEqual($firstRecord->recalledOn, date_create('2010-02-04'));
		$this->assertEqual($firstRecord->recallUrl, "http://www-odi.nhtsa.dot.gov/recalls/recallresults.cfm?start=1&SearchType=QuickSearch&rcl_ID=10V035000&summary=true&PrintVersion=YES");
	}
	
	function testSecondRecordParsed() {
		$firstRecord = $this->records[1];
		$this->assertEqual($firstRecord->manufacturer, 'TOYOTA MOTOR NORTH AMERICA, INC.');
		$this->assertEqual($firstRecord->description, "ON CERTAIN VEHICLES, DUE TO IMPROPER ASSEMBLY OF THE AIR BAG INFLATOR, WHICH IS USED IN THE SIDE AIR BAG, THE CURTAIN SHIELD AIR BAG, AND THE KNEE AIR BAG ASSEMBLY, SOME INFLATORS WERE PRODUCED WITH AN INSUFFICIENT AMOUNT OF THE HEATING AGENTS NECESSARY F");
		$this->assertEqual($firstRecord->recalledOn, date_create('2006-04-06'));
		$this->assertEqual($firstRecord->recallUrl, "http://www-odi.nhtsa.dot.gov/recalls/recallresults.cfm?start=1&SearchType=QuickSearch&rcl_ID=06V096000&summary=true&PrintVersion=YES");
	}
	
	function testResultHasRecordCount() {
		$this->assertEqual($this->result->totalMatches, 2);
	}
	
	function testResultIndicatesSuccess() {
		$this->assertTrue($this->result->success, 'The parsed result did not indicate success');
	}
	
	function testResultDoesNotIndicateFailure() {
		$this->assertFalse($this->result->failed, 'The parsed result indicates failure');
	}
}

class UsaSearchResultParseFailedTests extends UnitTestCase {
	private $result;
	
	private $error = '{"error":"Invalid year"}';
	
	private $records;
	
	function setUp() {
		$parser = new UsaSearchResultParser();
		$this->result = $parser->parse($this->error);
		$this->records = $this->result->getRecords();
	}
	
	function testRecordsIsEmptyArray() {
		$this->assertNotNull($this->records);
		$this->assertTrue(empty($this->records), 'The records array should be empty');
	}
	
	function testResultDoesNotIndicatesSuccess() {
		$this->assertFalse($this->result->success, 'The parsed result should not indicate success');
	}
	
	function testResultIndicatesFailure() {
		$this->assertTrue($this->result->failed, 'The parsed result should have failed');
	}
	
	function testResultContainsErrors() {
		$this->assertEqual($this->result->errors, array('Invalid year'));
	}
}

?>