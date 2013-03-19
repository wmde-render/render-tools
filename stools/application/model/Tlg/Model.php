<?php
require_once( APP_PATH . 'gpClient.php' );

class Tlg_Model extends Model {
	private $_serviceUrl;
	
	public function __construct() {
		$this->_serviceUrl = "http://toolserver.org/~jkroll/tlgbe/tlgwsgi.py";
	}
	
	public function getFlawList() {
		$url = $this->_serviceUrl;
		$url .= "?action=listflaws&i18n=" . $_SESSION['uilang'];
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
	
	public function getGraphList() {
		$result = array();
		try {
			$connInfo = $this->_getConnectionInfo(); var_dump($connInfo);
			$gp = gpConnection::new_client_connection( 
					null, 
					$connInfo["graphserv-host"] . ".toolserver.org", 
					$connInfo["graphserv-port"] );
			$gp->connect();
			$runningGraphs = $gp->capture_list_graphs();
			if ( is_array( $runningGraphs ) ) {
				foreach( $runningGraphs as $graph ) {
					$result[] = str_replace( "wiki", "", $graph[0] );
				}
			}
			return $result;
		} catch(gpException $ex) {
			return false;
		}
	}
	
	private function _getConnectionInfo() {
		$defaultConnInfo = array(
			"graphserv-host" => "ortelius",
			"graphserv-port" => 6666
		);
		
		$result = @file_get_contents( "http://toolserver.org/~jkroll/tlgbe/tlgrc" );
		if ( !$result ) {
			return $defaultConnInfo;
		}
		
		$connInfo = json_decode( $result, true );
		if ( !$connInfo ) {
			return $defaultConnInfo;
		}

		return $connInfo;
	}
}
