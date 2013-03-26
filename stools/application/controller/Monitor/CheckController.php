<?php
class Monitor_CheckController /*extends Controller*/ {
	private $_messages;

	public function __construct() {
		$this->_messages = array();
	}

	public function tlgAction() {
		$graphs = SingletonFactory::getInstance( "Tlg_Model" )->getGraphList();
		if ( !in_array( "de", $graphs ) ) {
			$this->_messages[] = "GraphServ: instance 'dewiki' not running\n";
		}
		if ( !in_array( "en", $graphs ) ) {
			$this->_messages[] = "GraphServ: instance 'enwiki' not running\n";
		}
		if ( !in_array( "fr", $graphs ) ) {
			$this->_messages[] = "GraphServ: instance 'frwiki' not running\n";
		}

		$testRequest = @file_get_contents( TLG_SERVICE_URL . "?action=query&format=json&chunked=true&lang=de&query=Astronomie&querydepth=2&i18n=de&flaws=Large" );
		if ( $testRequest !== false ) {
			$testRequest = explode( "\n", $testRequest );
			if ( is_array( $testRequest ) ) {
				foreach( $testRequest as $line ) {
					if ( $line !== '' ) {
						$resultRow = json_decode( $line );
						if ( $resultRow === null ) {
							$this->_messages[] = "TLG backend: json response could not be parsed\n";
						}
					}
				}
			} else {
				$this->_messages[] = "TLG backend: response is not multiline\n";
			}
		} else {
			$this->_messages[] = "TLG backend: no response\n";
		}
		
		$this->_sendMessages();
	}

	public function asqmAction() {
		$test = @file_get_contents( "http://toolserver.org/~render/stools/asqm/query/json/id/297666/lang/de/asqmid/monitor" );
		if ( $test !== false ) {
			$result = json_decode( $test );
			if ( $result === null ) {
				$this->_messages[] = "Article Monitor: error parsing json response\n";
			}
		} else {
			$this->_messages[] = "Article Monitor: no response\n";
		}
	}
	
	private function _sendMessages() {
		if ( is_array( $this->_messages ) && !empty( $this->_messages ) ) {
			$subject = "RENDER Supporting Tools Monitor detected a problem";
			$output = implode( "\n", $this->_messages );

			$headers   = array();
			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-type: text/plain; charset=UTF-8";
			$headers[] = "From: RENDER-Monitor <render@wikimedia.de>";
			$headers[] = "Subject: {$subject}";
			$headers[] = "X-Mailer: PHP/" . phpversion();
			$mailSent = mail( 'render@wikimedia.de', $subject,
				$output, implode( "\r\n", $headers ) );

			if ( $mailSent === false ) {
				echo "(ERR)" . $output;
			}
		}
	}
}
