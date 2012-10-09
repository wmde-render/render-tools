<?php
abstract class Singleton {

	protected static $instance = NULL;

	protected function __construct() {
		//self::$instance->init();
	}

	private function __clone() { }

	public final static function getInstance() {
		if( static::$instance === NULL ) {
			static::$instance = new static;
		}

		return static::$instance;
	}
	
	private function init() {
		
	}
}
