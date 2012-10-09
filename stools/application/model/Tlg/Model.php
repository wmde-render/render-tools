<?php
class Tlg_Model extends Model {
	private $_serviceUrl;
	
	public function __construct() {
		$this->_serviceUrl = "http://toolserver.org/~jkroll/tlgbe/tlgwsgi.py";
	}
	
	public function getFlawList() {
		$url = $this->_serviceUrl;
		$url .= "?action=listflaws&i18n=" . $_SESSION['lang'];
		$result = @file_get_contents( $url );
		
		if ( $result ) {
			$arrJson = json_decode( $result, true );
			$arrFlaws = array();
			foreach($arrJson as $flaw => $info) {
				$group = isset($info["group"]) ? $info["group"] : "nogroup";

				$arrFlaws[$info["group"]][$flaw] = array(
					"label" => $info["label"],
					"description" => $info["description"]
				);
			}
			return $arrFlaws;
		}
		
		return array();
	}
	
	/*public function query() {
		$req = SingletonFactory::getInstance( 'Request' );
		$url = $this->_serviceUrl;
		
		$url .= "?action=query";
		$url .= "&lang=" . urlencode($req->getVar( 'lang' ) );
		$url .= "&query=" . urlencode($req->getVar( 'query' ) );
		$url .= "&querydepth=" . urlencode($req->getVar( 'querydepth' ) );
		$url .= "&flaws=" . implode('%20', $req->getVar( 'flaw' ) );
		
		$response = @file_get_contents( $url );
		$response = str_replace("}{", "}\n{", $response);
		$retVal = array();
		
		if ( $response ) {
			$results = explode( '\u000a', $response );
			foreach( $results as $result ) {
				$retVal[] = json_decode( $result, true );
			}
		}
		
		var_dump( $retVal );
		return $retVal;
	}*/
}