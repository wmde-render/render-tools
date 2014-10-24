<?php
require_once( APP_PATH . 'gpClient.php' );

class Alg_Model extends Model {
	private $_serviceUrl;
	
	public function __construct() {
		$this->_serviceUrl = ALG_SERVICE_URL_INTERNAL;
	}
	
	public function getFlawList() {
		$url = ALG_SERVICE_URL_INTERNAL . "/tlgwsgi.py";
		$url .= "?action=listflaws&i18n=" . $_SESSION['uilang'];

		$options  = array( 'http' => array( 'user_agent' => 'RENDER Article List Generator' ) );
		$context  = stream_context_create( $options );

		$result = @file_get_contents( $url, false, $context );
		
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
			$connInfo = $this->_getConnectionInfo();
			$gp = gpConnection::new_client_connection( 
					null, 
					$connInfo["graphserv-host"], 
					$connInfo["graphserv-port"] );
			$gp->connect();
			$runningGraphs = $gp->capture_list_graphs();
			if ( is_array( $runningGraphs ) ) {
				foreach( $runningGraphs as $graph ) {
					if ( preg_match( "/wiki$/", $graph[0] ) ) {
						$result[] = str_replace( "wiki", "", $graph[0] );
					}
				}
			}
			return $result;
		} catch(gpException $ex) {
			return false;
		}
	}
	
	private function _getConnectionInfo() {
		$defaultConnInfo = array(
			"graphserv-host" => "sylvester",
			"graphserv-port" => 6666
		);
		
		$result = @file_get_contents( ALG_SERVICE_URL_INTERNAL . "/tlgrc" );
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
