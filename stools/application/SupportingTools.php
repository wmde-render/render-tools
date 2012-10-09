<?php
class SupportingTools {

	private $_request;
	private $_response;
	

	public function __construct( $request ) {
		$this->_request = $request;
	}
	

	// TODO: make static
	public static function getRequest() {
		return self::$_request;
	}


	public function run() {
		$this->_response = SingletonFactory::getInstance( 'Request' )->process();
		echo $this->_response;
	}
}
