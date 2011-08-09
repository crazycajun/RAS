<?php
require_once '../simpletest/autorun.php';
require_once 'TestSystemClock.php';
require_once '../../classes/Db.php';
require_once '../../interfaces/iUserAccountRepository.php';
require_once '../../classes/UserAccountRepository.php';

class UserAccountRepositoryTests extends UnitTestCase {
	
	private $repository;
	private $database;
	private $tokenGen;
	private $systemClock;
	
	function setUp() {
		$config = new DbConfig();
		$this->tokenGen = new FakeTokenGenerator();
		$this->database = new PDO($config->server, $config->user, $config->pwd);
		$this->database->beginTransaction();
		$this->systemClock = new TestSystemClock();
		$this->repository = new UserAccountRepository($this->tokenGen, $this->database, $this->systemClock);
	}
	
	function testDateFormat() {
		$date = new DateTime('2011-1-1 1:01:01');
		$this->assertEqual($date->format(UserAccountRepository::MYSQL_DATE_FORMAT), '2011-01-01 01:01:01');
	}
	
	function testUserAccountIsAdded() {
		$pwdHash = sha1('p@$$w0rd');
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		
		$statement = $this->database->query(
			"select * from user_accounts where name = 'Brian C.' and email = 'brian@domain.com' and password = '" . $pwdHash . "';");
		$this->assertEqual($statement->rowCount(), 1, "There should have been a row inserted for the data provided.");
		$row = $statement->fetch();
		$this->assertEqual($row['name'], "Brian C.");
		$this->assertEqual($row['email'], "brian@domain.com");
		$this->assertEqual($row['password'], $pwdHash);
		$this->assertEqual($row['activation_token'], $this->tokenGen->getToken(UserAccountRepository::ACTIVATION_TOKEN_SIZE));
		$this->assertEqual($row['created_on'], $this->systemClock->now()->format(UserAccountRepository::MYSQL_DATE_FORMAT));
	}
	
	function testAccountAddReturnsTheActivationToken() {
		$pwdHash = sha1('p@$$w0rd');
		$activationToken = $this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$this->assertEqual($activationToken, $this->tokenGen->getToken(UserAccountRepository::ACTIVATION_TOKEN_SIZE));
	}
	
	function testAccountIsActivated() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$token = $this->tokenGen->getToken(UserAccountRepository::ACTIVATION_TOKEN_SIZE);
		$this->repository->activate("brian@domain.com", $token);
		
		$statement = $this->database->query(
			"select activated_on from user_accounts where email = 'brian@domain.com' and activation_token = '" . $token . "';");
		$row = $statement->fetch();
		$this->assertEqual($row['activated_on'], $this->systemClock->now()->format(UserAccountRepository::MYSQL_DATE_FORMAT));
	}
	
	function testActivateIndicatesActivationForValidEmailAndToken() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$token = $this->tokenGen->getToken(UserAccountRepository::ACTIVATION_TOKEN_SIZE);
		$result = $this->repository->activate("brian@domain.com", $token);
		$this->assertTrue($result, 'The activate method did not indicate success.');		
	}
	
	function testActivateIndicatesFailureForInvalidEmail() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$token = $this->tokenGen->getToken(UserAccountRepository::ACTIVATION_TOKEN_SIZE);
		$result = $this->repository->activate("brian@notthedomain.com", $token);
		$this->assertFalse($result, 'The member should not have been activated.');		
	}
	
	function testActivateIndicatesFailureForInvalidToken() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$result = $this->repository->activate("brian@domain.com", "boo");
		$this->assertFalse($result, 'The member should not have been activated.');		
	}
	
	function testAccountIsNotActivatedForInvalidInput() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$result = $this->repository->activate("brian@domain.com", "boo");
		$statement = $this->database->query("select activated_on from user_accounts where email = 'brian@domain.com';");
		$row = $statement->fetch();
		$this->assertEqual($row['activated_on'], null);
	}
	
	function testActivateIndicatesFailureWhenNoAccountExists() {
		$result = $this->repository->activate("brian@domain.com", "boo");
		$this->assertFalse($result, 'The member should not have been activated because s/he does not exist.');		
	}
	
	function testUserIsAuthenticated() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$result = $this->repository->authenticate("brian@domain.com", 'p@$$w0rd');
		$this->assertTrue($result, 'The user should have been auth\'ed.');
	}
	
	function testUserIsNotAuthenticatedForInvalidEmail() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$result = $this->repository->authenticate("frank@domain.com", 'p@$$w0rd');
		$this->assertFalse($result, 'The user should NOT be auth\'ed.');
	}
	
	function testUserIsNotAuthenticatedForInvalidPassword() {
		$this->repository->add("Brian C.", "brian@domain.com", 'p@$$w0rd');
		$result = $this->repository->authenticate("brian@domain.com", 'jimmydean');
		$this->assertFalse($result, 'The user should NOT be auth\'ed.');
	}
	
	function tearDown() {
		// This can help with troubleshooting instead of rolling back.
		//$this->database->commit();
		
		// Rollback any changes made during testing.
		$this->database->rollBack();
	}
}

class FakeTokenGenerator {
	function getToken($size) {
		return substr('areallylongtokenreturnedthatisparsedusingsubstringbasedonthestartandsizepassedinhopingthatthisstringislongenough', 0, $size);
	}
}

?>