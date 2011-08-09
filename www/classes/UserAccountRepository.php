<?php

// This is the class responsible for handling persistent interactions
// with the user account entity.
class UserAccountRepository implements iUserAccountRepository {
	
	// The date time format required to correctly insert dates into
	// the MySQL database engine.
	const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
	const ACTIVATION_TOKEN_SIZE = 48;
	
	private $database;
	private $tokenGenerator;
	private $databaseInjected = false;
	private $systemClock;
	
	function __construct($tokenGenerator = null, $database = null, $systemClock = null) {
		$this->database = $database;
		$this->databaseInjected = $database != null;
		$this->tokenGenerator = $tokenGenerator == null ? new TokenGenerator() : $tokenGenerator;
		$this->systemClock = $systemClock == null ? new SystemClock() : $systemClock;
	}
	
	// Adds a user account to the table and returns the corresponding activation
	// token for the account. This method does not verify parameters as this is
	// the requirement of the caller.
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
	
	// Activates the user account.
	function activate($email, $token) {
		$db = $this->getDatabase();
		$activatedOn = $this->systemClock->now();
		$statement = $db->prepare('update user_accounts set activated_on = :activatedOn '
			. 'where email = :email and activation_token = :activationToken');
		$statement->bindValue(':email', $email);
		$statement->bindValue(':activationToken', $token);
		$statement->bindValue(':activatedOn', $activatedOn->format(self::MYSQL_DATE_FORMAT));
		$statement->execute();
		$rowsAffected = $statement->rowCount();
		$this->cleanUpDatabase();
		return $rowsAffected > 0;
	}
	
	// Authenticates the user if possible.
	function authenticate($email, $password) {
		$db = $this->getDatabase();
		$now = $this->systemClock->now();
		$statement = $db->prepare('update user_accounts set login_on = :now '
			. 'where email = :email and password = :password');
		$statement->bindValue(':email', $email);
		$statement->bindValue(':password', sha1($password));
		$statement->bindValue(':now', $now->format(self::MYSQL_DATE_FORMAT));
		$statement->execute();
		$rowsAffected = $statement->rowCount();
		$this->cleanUpDatabase();
		return $rowsAffected > 0;
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