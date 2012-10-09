<?php
class AutoLoader {

	public function __construct() {
		spl_autoload_register( array( $this, 'loadClass' ) );
	}


	public function loadClassFile( $className ) {
		$classPath = "application";
		echo $className . "<br />";
		
		if ( strpos( $className, '/' ) > 0 ) {
			$classPath = explode( '/', $className );
			$className = $classPath[1];
			$classPath = $classPath[0];
		}

		$className = str_replace( '_', '/', $className );
		include( $classPath . '/' . $className . '.php' );
	}


	public static function loadClass($class) {
		$files = array(
			$class . '.php',
			str_replace('_', '/', $class) . '.php',
		);

		foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $base_path) {
			foreach ($files as $file) {
				$path = "$base_path/$file";
				if (file_exists($path) && is_readable($path)) {
					include_once $path;
					return;
				}
			}
		}
	}
}
