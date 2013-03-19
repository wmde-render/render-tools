<?php
session_start();

if ( !file_exists( "./local-config.php" ) ) {
	die( "Application failed to run, configuration not set" );
}

include( 'local-config.php' );
ini_set( 'include_path', APP_PATH . ':' . APP_PATH . 'controller/:' . APP_PATH . 'model/:' . APP_PATH . 'view/');

include( 'application/AutoLoader.php' );
$autoLoader = new AutoLoader();

$sTools = new SupportingTools( SingletonFactory::getInstance( "Request" ) );
$sTools->run();
