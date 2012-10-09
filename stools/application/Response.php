<?php
class Response extends SingletonFactory {

	private $_fullPage;
	private $_header = array();

	public function __construct() {
		ob_start();
	}


	public function setFullPage( $value ) {
		if ($value) {
			$this->_fullPage = true;
		} else {
			$this->_fullPage = false;
		}
		
		return $this;
	}
	
	public function setHeader($header) {
		$this->_header[] = $header;
		return $this;
	}


	public function sendResponse() {
		foreach ( $headers as $header ) {
			header( $header );
		}

		if ($_wrap) {
			
		}
		
		ob_end_flush();
	}
}
