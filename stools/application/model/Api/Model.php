<?php
class Api_Model extends Model {

	public function __construct() { }

	public function getFeaturedArticle( $title ) {
		ini_set( 'user_agent', 'RENDER-Bot' );
		$articleId = SingletonFactory::getInstance( "Request" )->getVar( "id" );

		$revisions = unserialize( file_get_contents( "http://" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . ".wikipedia.org/w/api.php?action=query&prop=revisions&titles=" . $title . "&rvprop=content&format=php" ) );
		$revision = $revisions["query"]["pages"][$articleId]["revisions"][0]["*"];
		//var_dump($test);
		
		if ( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) == 'de' ) {
			preg_match( "/\{\{([E|e]xzellent)\|([0-9a-zA-ZÄÖÜäöüß\.\s].*)\|[0-9]+\}\}/", $revision, $regResult );
			if( empty($regResult) ) {
				preg_match( "/\{\{([L|l]esenswert)\|([0-9a-zA-ZÄÖÜäöüß\.\s].*)\|[0-9]+\}\}/", $revision, $regResult );
			}
		} else {
			preg_match( "/\{\{([F|f]eatured article)\}\}/", $revision, $regResult );
			if( empty($regResult) ) {
				preg_match( "/\{\{([G|g]ood article)\}\}/", $revision, $regResult );
			}
		}
		
		if( !empty($regResult) ) {
			if (array_key_exists(1, $regResult)) {
				$output = ucfirst( $regResult[1] );
			}
			
			if (array_key_exists(2, $regResult)) {
				$output .= " seit " . $regResult[2];
			}
		} else {
			$output = "";
		}
		
		ini_set( 'user_agent', '' );
		return $output;
	}
	
	public function getArticleFeedback() {
		$aId = SingletonFactory::getInstance( 'Request' )->getVar( 'id' );
		$lang = SingletonFactory::getInstance( 'Request' )->getVar( 'lang' );
		
		ini_set( 'user_agent', 'RENDER-Bot' );
		
		$result = unserialize( file_get_contents( "http://" . $lang . ".wikipedia.org/w/api.php?format=php&action=query&list=articlefeedback&afpageid=" . $aId ) );
		$ratings = @$result['query']['articlefeedback'][0]['ratings'];
		
		$retVal = array();
		if( is_array($ratings) ) {
			foreach($ratings as $key => $rating) {
				if( $rating['count'] > 0 ) {
					$retVal[$key] = $rating['total'] / $rating['count'];
				} else {
					$retVal[$key] = 0;
				}
			}
		}
		
		ini_set( 'user_agent', '' );
		return $retVal;
	}
	
	public function getCurrentRevision() {
		$aId = SingletonFactory::getInstance( 'Request' )->getVar( 'id' );
		$lang = SingletonFactory::getInstance( 'Request' )->getVar( 'lang' );
		ini_set( 'user_agent', 'RENDER-Bot' );
		
		$result = unserialize( file_get_contents( "http://" . $lang . ".wikipedia.org/w/api.php?format=php&action=query&prop=revisions&rvprop=content&pageids=" . $aId ) );
		$revision = $result["query"]["pages"][$aId]["revisions"][0]["*"];
		preg_match_all( "/<ref[ |>]/", $revision, $regResult );
		
		ini_set( 'user_agent', '' );

		return sizeof($regResult[0]);
	}
}
