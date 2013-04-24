<?php
class Wikipedia_Categories extends Model {

	public function __construct() { }
	
	public function getCategoryNames( $term ) {
		$response = array();
		if ( $term ) {
			$dbConn = SingletonFactory::getInstance( 'Database' )
				->getDbConnection( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . 'wiki' );
		
			$sql = "SELECT * FROM category ".
					"WHERE cat_title LIKE ? ".
					"AND (cat_subcats > 0 OR cat_pages > 0) ".
					"ORDER BY cat_title ".
					"LIMIT 10";
			$statement = $dbConn->prepare( $sql );
			$statement->execute( array( $term . "%" ) );

			$result = $statement->fetchAll();
			foreach( $result as $row ) {
				$response[] = array( "label" => $row["cat_title"], "value" => $row["cat_title"] );
			}
			return $response;
		} else {
			echo "term not provided";
			die();
		}
	}
}