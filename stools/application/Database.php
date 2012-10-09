<?php
class Database extends SingletonFactory {
	
	private $_dbConn = array();
	private $_dbHost;
	private $_dbUser;
	private $_dbPass;
	private $_dbName;
	
	public function __construct() { }
	
	public function setDbConnection( $dbHost, $dbUser, $dbPass, $dbName ) {
		$this->_dbHost = $dbHost;
		$this->_dbUser = $dbUser;
		$this->_dbPass = $dbPass;
		$this->_dbName = $dbName;

		try {
			$this->_dbConn[$dbName] = new PDO(
				'mysql:host=' . $this->_dbHost . ';dbname=' . $this->_dbName,
				$this->_dbUser,
				$this->_dbPass
			);
			
			return $this->_dbConn[$dbName];
		} catch ( PDOException $e ) {
			print "Error!: " . $e->getMessage() . "<br/>";
			return false;
		}
	}
	
	public function getDbConnection( $dbName ) {
		if ( isset( $this->_dbConn[$dbName] ) ) {
			return $this->_dbConn[$dbName];
		} else {
			$userInfo = $this->_getUserCredentials();
			if ( $dbName == 'dewiki_p') {
				return $this->setDbConnection( 'sql-s2', $userInfo["user"], $userInfo["password"], 'dewiki_p' );
			} elseif ( $dbName == 'enwiki_p' ) {
				return $this->setDbConnection( 'sql-s1', $userInfo["user"], $userInfo["password"], 'enwiki_p' );
			} elseif ( $dbName == 'ts' ) {
				return $this->setDbConnection( 'sql-user-k', $userInfo["user"], $userInfo["password"], 'u_knissen_asqm_u' );
			}
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
