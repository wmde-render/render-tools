<?php
class ChangeDetector_Model extends Model {
	private $languages = array(
		"de",
		"en",
		"fr",
		"pt",
		"it",
		"pl",
		"ru",
		"nl",
		"sv",
		"es",
	);

	public function __construct() { }

	public function checkDetected( $id, $lang ) {
		if ( $id ) {
			$yesterday = date( "Ymd", time() - 86400 );
			$dbConn = SingletonFactory::getInstance( 'Database' )->getDbConnection( 'changeDetector' );

			$sql = "SELECT identifier FROM noticed_article ".
					"WHERE page_id = ? ".
					"AND day = ? ".
					"AND language = ? "/*.
					"AND detected_by_cta = 0 ".
					"AND detected_by_cts = 0 ".
					"AND detected_by_mdf = 0"*/;

			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $id, $yesterday, $lang ) );
			$result = $statement->fetchAll();

			if( count( $result ) > 0 ) {
				$sql = "SELECT identifier FROM changed_article ".
					"WHERE identifier = ? AND day = ? ".
					"AND language IN ('" . implode( "', '", $this->languages ) . "') ".
					"AND only_major != 0 AND non_bot != 0 ".
					"AND many_user != 0 GROUP BY language";
				$statement = $dbConn->prepare( $sql );
				$statement->execute( array( $result[0][0], $yesterday ) );
				$result = $statement->fetchAll();
				if( count( $result ) >= 5 ) {
					return true;
				}
			}
		}

		return false;
	}
}
