<?php

class UserAccountRepository implements iUserAccountRepository {
	
	const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
	const ACTIVATION_TOKEN_SIZE = 48;
	
	private $database;
	private $tokenGenerator;
	private $databaseInjected = false;
	private $systemClock;
	
	function __construct($tokenGenerator = null, $database = null, $systemClock = null) {
		$this->database = $database;
		$this->databaseInjected = $database != null;
		$this->tokenGenerator = $tokenGenerator;
		$this->systemClock = $systemClock == null ? new SystemClock() : $systemClock;
	}
	
	function add($name, $email, $password) {
		$db = $this->getDatabase();
		$createdOn = $this->systemClock->now();
		$token = $this->tokenGenerator->getToken(self::ACTIVATION_TOKEN_SIZE);
		
		$statement = $db->prepare("insert into user_accounts(name, password, email, created_on, activation_token) " 
			. "VALUES (:name, :password, :email, :createdOn, :activationToken)");
		$statement->bindValue(':name', $name);
		$statement->bindValue(':password', sha1($password));
		$statement->bindValue(':email', $email);
		$statement->bindValue(':createdOn', $createdOn->format(self::MYSQL_DATE_FORMAT));
		$statement->bindValue(':activationToken', $token);
		$statement->execute();
		
		$this->cleanUpDatabase();
		return $token;
	}
	
	// Returns the database or constructs a new one.
	private function getDatabase() {
		if ($this->databaseInjected) return $this->database;
		
		$config = new DbConfig();
		$this->database = new PDO($config->server, $config->user, $config->pwd);
		return $this->database;
	}
	
	// Cleans the database connection if it was not injected.
	private function cleanUpDatabase() {
		if ($this->databaseInjected) return;
		
		$this->database = null;
	}
}

?>