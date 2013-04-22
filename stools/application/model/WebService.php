<?php
class WebService extends Model {

	const STATUS_ERROR = -1;
	const STATUS_UNINITIALIZED = 0;
	const STATUS_INITIALIZED = 1;
	const STATUS_STARTED = 2;
	const STATUS_SUCCESS = 3;

	protected $_protocol;
	protected $_serviceUrl;
	protected $_method;
	protected $_params;
	protected $_response;
	
	private $_status = WebService::STATUS_UNINITIALIZED;

	/**
	 * Initialize object by passing request parameters
	 * 
	 * @param string $serviceUrl URL of the service to be requested
	 * @param mixed $params array of parameters that need to be passed
	 * @param string $protocol protocol, "http" by default
	 * @param string $method request method, "GET" by default
	 */
	protected function init( $serviceUrl, $params = array(), $protocol = "http", $method = "GET" ) {
		$this->_serviceUrl = $serviceUrl;
		$this->_params = $params;
		$this->_protocol = $protocol;
		$this->_method = $method;
		
		$this->_status = WebService::STATUS_INITIALIZED;
	}

	public function getStatus() {
		return $this->_status;
	}
	
	protected function sendRequest() {
		if( $this->_status > WebService::STATUS_UNINITIALIZED ) {
			$this->_status = WebService::STATUS_STARTED;
			$qString = http_build_query( $this->_params );
			$this->_response = @file_get_contents( $this->_protocol . "://" . $this->_serviceUrl . "?" . $qString );
			if( $this->_response === false ) {
				$this->_status = WebService::STATUS_ERROR;
			} else {
				$this->_status = WebService::STATUS_SUCCESS;
			}
		} else {
			// TODO:
			// exception of uninitialized status
			return false;
		}
	}
	
	protected function parseJsonResponse() {
		# return associative array as result object
		return json_decode( $this->_response, true );
	}
}
