<?php
class Asqm_Model extends Model {

	public function __construct() { }
	
	public function getArticle( $id ) {
		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );
		
			$sql = "SELECT * FROM page WHERE page_id = ?";
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );

			return $statement->fetchAll();
		} else {
			echo "id not provided";
			die();
		}
	}
	
	
	public function getRevision( $id, $first = true ) {
		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );

			$sql = "SELECT * FROM revision ".
					"WHERE rev_page = ? " .
					"ORDER BY rev_timestamp ";
			if ($first) {
				$sql .= "ASC";
			} else {
				$sql .= "DESC";
			}

			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );

			return $statement->fetchAll();
		} else {
			echo "id not provided";
			die();
		}
	}
	
	public function getLinkCount( $id ) {
		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );

			$sql = "SELECT COUNT(*) FROM pagelinks ".
					"WHERE pl_from = ? ";

			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );

			return $statement->fetchAll();
		} else {
			echo "id not provided";
			die();
		}
	}
	
	public function getImageCount( $id ) {
		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );

			/*$sql = "SELECT COUNT(*) FROM imagelinks ".
					"WHERE il_from = ? AND NOT EXISTS (".
					"SELECT 1 FROM imagelinks WHERE il_to = src.il_to ".
						"AND il_from IN (".
						"SELECT page_id FROM page WHERE page_namespace=10)));";*/
			$sql = "SELECT COUNT(il_from) FROM imagelinks AS src ".
					"WHERE il_from = ? AND NOT EXISTS (".
					"SELECT 1 FROM imagelinks WHERE il_to = src.il_to AND il_from IN (".
					"SELECT page_id FROM page WHERE page_namespace = 10))";
			
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );

			return $statement->fetchAll();
		} else {
			echo "id not provided";
			die();
		}
	}
	
	public function getRealImageCount( $id ) {
		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );

			$sql = "SELECT * FROM imagelinks ".
					"WHERE il_from = ? ";
			
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );

			$count = 0;
			while( $row = $statement->fetch() ) {
				if ( !$this->_isTemplateImage( $row['il_to'], $dbConn ) ) {
					$count ++;
				}
			}
			
			return $count;
		} else {
			echo "id not provided";
			die();
		}
	}
	
	private function _isTemplateImage( $imgName, $dbConn ) {
		$sql = "SELECT COUNT(*) AS usagecount
			FROM imagelinks 
			INNER JOIN page 
			ON imagelinks.il_from = page.page_id 
			AND page.page_namespace = 10 
			WHERE il_to = ?";

		$statement = $dbConn->prepare( $sql );
		$statement->execute( array( $imgName ) );
		$result = $statement->fetchAll();
		
		if ( $result && $result[0]['usagecount'] > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getUniqueEditorCount( $id ) {
		$editorCount = array(
			"loggedin" => 0,
			"anonymous" => 0
		);

		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )
				->getVar( 'lang' ) . 'wiki' );

			$sql = "SELECT COUNT(DISTINCT rev_user) FROM revision WHERE rev_user > 0 AND rev_page = ?";
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );
			$result = $statement->fetchAll();
			if ( $result ) {
				$editorCount["loggedin"] = $result[0][0];
			}
			
			$sql = "SELECT COUNT(DISTINCT rev_user_text) FROM revision WHERE rev_user = 0 AND rev_page = ?";
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );
			$result = $statement->fetchAll();
			if ( $result ) {
				$editorCount["anonymous"] = $result[0][0];
			}
		}
		
		return $editorCount;
	}
	
	public function getArticleRating( $id ) {
		if ( $id ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );

			$sql = "SELECT AVG(aa_rating_value), aa_rating_id ".
					"FROM article_feedback ".
					"WHERE aa_page_id = ? ".
					"AND aa_rating_value > 0 ".
					"GROUP BY aa_rating_id ".
					"ORDER BY aa_rating_id";

			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id ) );

			return $statement->fetchAll();
		} else {
			echo "id not provided";
			die();
		}
	}
	
	public function getGiniScore( $id, $lang ) {
		if ( $id ) {
			// HACK
			$lang = SingletonFactory::getInstance('Request')->getVar('lang');
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( 'wikigini' );

			$sql = "SELECT gini_index ".
				"FROM revisions ".
				"WHERE page_id = ? ".
				"AND language_code = ? ".
				"AND method_id = 1 ".
				"ORDER BY id DESC ".
				"LIMIT 1";
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id, $lang ) );
			$result = $statement->fetchAll();
			if ( $result ) {
				return number_format( $result[0][0], 2 );
			}
		}
		return false;
	}

	public function logRequest() {
		$asqmId = SingletonFactory::getInstance( 'Request' )->getVar( 'asqmid' );
		$title = SingletonFactory::getInstance( 'Request' )->getVar( 'id' );
		$lang = SingletonFactory::getInstance( 'Request' )->getVar( 'lang' );
		if ( isset($asqmId) ) {
			if ( empty( $asqmId ) ) {
				$asqmId = "none";
			}
			
			$dbConn = SingletonFactory::getInstance( 'Database' )->getDbConnection( 'asqmRequestLog' );
			#$sql = "INSERT INTO asqm_request_log (asqm_id) VALUES (?) ON DUPLICATE KEY UPDATE requests = (requests + 1)";
			$sql = "INSERT INTO asqm_request_log (asqm_id, title, lang) VALUES (?, ?, ?)";
			try {
				$statement = $dbConn->prepare( $sql );
				$statement->execute( array( $asqmId, $title, $lang ) );
			} catch (Exception $e) {
				var_dump($e);
			}
		}
	}
}
