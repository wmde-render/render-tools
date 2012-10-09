<?php
abstract class SingletonFactory {

	protected static $instances = array();

	private function __construct() { }

	public final static function getInstance( $key ) {
		$reflectionClass = new ReflectionClass( $key );
		
		if( !$reflectionClass->isSubclassOf( __CLASS__ ) ) {
			throw Exception( $key . " does not extend __CLASS__" );
		}

		if( !array_key_exists( $key, self::$instances ) ) {
			self::$instances[$key] = $reflectionClass->newInstance();
		}

		return self::$instances[$key];
	}
}
