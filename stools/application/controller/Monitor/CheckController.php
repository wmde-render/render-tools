<?php
class Monitor_CheckController /*extends Controller*/ {

	public function __construct() {

	}

	public function tlgAction() {
		$graphs = SingletonFactory::getInstance( "Tlg_Model" )->getGraphList();
		if ( !in_array( "de", $graphs ) ) {
			echo "GraphServ: instance 'dewiki' not running\n";
		}
		if ( !in_array( "en", $graphs ) ) {
			echo "GraphServ: instance 'enwiki' not running\n";
		}
		if ( !in_array( "fr", $graphs ) ) {
			echo "GraphServ: instance 'frwiki' not running\n";
		}

		$testRequest = @file_get_contents( TLG_SERVICE_URL . "?action=query&format=json&chunked=true&lang=de&query=Astronomie&querydepth=2&i18n=de&flaws=Large" );
		if ( $testRequest !== false ) {
			$testRequest = explode( "\n", $testRequest );
			if ( is_array( $testRequest ) ) {
				foreach( $testRequest as $line ) {
					if ( $line !== '' ) {
						$resultRow = json_decode( $line );
						if ( $resultRow === null ) {
							echo "TLG backend: json response could not be parsed\n";
						}
					}
				}
			} else {
				echo "TLG backend: response is not multiline\n";
			}
		} else {
			echo "TLG backend: no response\n";
		}
	}

	public function asqmAction() {
		$test = @file_get_contents( "http://toolserver.org/~render/stools/asqm/query/json/id/297666/lang/de/asqmid/monitor" );
		if ( $test !== false ) {
			$result = json_decode( $test );
			if ( $result === false) {
				echo "Article Monitor: error parsing json response\n";
			}
		} else {
			echo "Article Monitor: no response\n";
		}
	}
}
