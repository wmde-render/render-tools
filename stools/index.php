<?php
session_start();
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

define( 'APP_PATH', '/home/knissen/public_html/stools/application/' );
define( 'TEMPLATE_PATH', '/home/knissen/public_html/stools/templates/' );
define( 'BASE_PATH', '/~knissen/stools/' );
ini_set( 'include_path', APP_PATH . ':' . APP_PATH . 'controller/:' . APP_PATH . 'model/:' . APP_PATH . 'view/');

$basePath = "/test/Render/";
$appPath = $basePath . "application/";

include( 'application/AutoLoader.php' );
$autoLoader = new AutoLoader();

$sTools = new SupportingTools( SingletonFactory::getInstance( "Request" ) );
$sTools->run();
