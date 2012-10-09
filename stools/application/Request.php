<?php
class Request extends SingletonFactory {

	private $_module = "";
	private $_controller = "";
	private $_action = "";
	private $_requestVars = array();


	public function __construct() {
		$uriParts = explode('?', $_SERVER['REQUEST_URI']);
		$reqUri = $uriParts[0];
		# TODO: hack
		if ($reqUri == '/~knissen/stools') $reqUri .= '/';
		$requestUri = str_ireplace( BASE_PATH, '', $reqUri );
		$requestUri = array_filter( explode( '/', $requestUri ) );

		if( isset($requestUri[0]) ) {
			$this->_module = $requestUri[0];
		} else {
			$this->_module = "default";
		}
		
		if( isset($requestUri[1]) ) {
			$this->_controller = $requestUri[1];
		} else {
			$this->_controller = "index";
		}
		
		if( isset($requestUri[2]) ) {
			$this->_action = $requestUri[2];
		} else {
			$this->_action = "index";
		}
		
		unset( $requestUri[0] );
		unset( $requestUri[1] );
		unset( $requestUri[2] );
		$requestUri = array_values($requestUri);

		$varCount = sizeof($requestUri);
		for( $i = 0; $i < $varCount; $i ++ ) {
			if( array_key_exists( $i + 1, $requestUri ) ) {
				$this->_requestVars[$requestUri[$i]] = $requestUri[$i + 1];
				$i++;
			}
		}

		foreach( $_REQUEST as $key => $value ) {
			$this->_requestVars[$key] = $value;
		}

		if ( $this->_module === 'backend' && $this->_controller !== "user" && $this->_action !== "login" && !isset( $_SESSION['authenticated'] ) ) {
			$this->_controller = "user";
			$this->_action = "form";
		}
	}


	public function getVar( $key ) {
		if ( is_array( $this->_requestVars ) && array_key_exists( $key, $this->_requestVars ) ) {
			return $this->_requestVars[$key];
		} else {
			return false;
		}
	}
	
	
	public function __get( $key ) {
		if ( isset($this->$key) ) {
			return $this->$key;
		}

		return false;
	}
	
	
	public function getVars() {
		return $this->_requestVars;
	}
	

	public function getModule() {
		return $this->_module;
	}
	

	public function getController() {
		return $this->_controller;
	}
	

	public function getAction() {
		return $this->_action;
	}


	public function process() {
		$controllerName = ucfirst( $this->_module ) . '_' . ucfirst( $this->_controller ) . "Controller";
		# TODO: is $controllerPath needed somewhere?
		# $controllerPath = 'controller/' . ucfirst( $this->_module ) . '_' . ucfirst( $this->_controller ) . "Controller";

		$actionName = strtolower( $this->_action ) . 'Action';
		
		if ( class_exists($controllerName) ) {
			$processor = new $controllerName();
			$processor->$actionName();
		} else echo "controller ".$controllerName." name not valid";
	}
}
