<?php
class Database extends SingletonFactory {
	
	private $_dbConn = array();
	
	public function __construct() { }
	
	public function setDbConnection( $dbHost, $dbUser, $dbPass, $dbName, $port = "3306" ) {
		try {
			$this->_dbConn[$dbName] = new PDO(
				'mysql:host=' . $dbHost . ';dbname=' . $dbName . ";port=" . $port,
				$dbUser,
				$dbPass
			);
			
			return $this->_dbConn[$dbName];
		} catch ( PDOException $e ) {
			print "Error!: " . $e->getMessage() . "<br/>";
			return false;
		}
	}
	
	public function getDbConnection( $dbName ) {
		global $dbLinks;
		
		if ( isset( $this->_dbConn[$dbName] ) ) {
			return $this->_dbConn[$dbName];
		} else {
			$userInfo = $this->_getUserCredentials();
			if (array_key_exists( $dbName, $dbLinks ) ) {
				$serverName = $dbLinks[$dbName]["serverName"];
			} else {
				$serverName = $dbLinks["user"]["serverName"];
			}

			return $this->setDbConnection( $serverName, $userInfo["user"], $userInfo["password"], $dbLinks[$dbName]["dbName"] );
		}

		return false;
	}

	private function _getUserCredentials() {
		$userInfo = posix_getpwuid( posix_getuid() );
		$dbConf = parse_ini_file( $userInfo['dir'] . "/.my.cnf" );
		return $dbConf;
	}
	
	public function __destruct() {
		$this->_dbConn = null;
	}
}
