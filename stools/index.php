<?php
session_start();

# find local configuration
if ( !file_exists( "./local-config.php" ) ) {
	die( "Application failed to run, configuration not set" );
}

# parse command line arguments into GET-Array
if ( PHP_SAPI === 'cli' ) {
	parse_str( implode( '&', array_slice( $argv, 1 ) ), $_GET );
}

include( 'local-config.php' );
ini_set( 'include_path', APP_PATH . ':' . APP_PATH . 'controller/:' . APP_PATH . 'model/:' . APP_PATH . 'view/' );

include( 'application/AutoLoader.php' );
$autoLoader = new AutoLoader();

$sTools = new SupportingTools( SingletonFactory::getInstance( "Request" ) );
$sTools->run();
