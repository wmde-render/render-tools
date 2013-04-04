<?php
class Controller {
	public function __construct() {
		
	}
	
	protected function forceSecure() {
		if( !isset( $_SERVER['HTTP_X_TS_SSL'] ) || empty( $_SERVER['HTTP_X_TS_SSL'] ) ) {
			$redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			header("Location: $redirect", true, 301);
			exit();
		}
	}
}