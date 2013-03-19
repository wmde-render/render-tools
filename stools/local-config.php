<?php
# settings for error reporting, deactivate for production mode
ini_set( 'display_errors', 1 );
ini_set( 'log_errors', 1 );
ini_set( 'error_log', dirname(__FILE__) . '/error_log.txt' );
error_reporting( E_ALL );

# absolute path to application folder
define( 'APP_PATH', '/home/knissen/public_html/stools/application/' );
# absolute path to template folder
define( 'TEMPLATE_PATH', '/home/knissen/public_html/stools/templates/' );
# relative path to index.php
define( 'BASE_PATH', '/~knissen/stools/' );

# database names and server names
$dbLinks = array(
	"dewiki" => array(
		"serverName" => "sql-s5",
		"dbName" => "dewiki_p"
	),
	"enwiki" => array(
		"serverName" => "sql-s1",
		"dbName" => "enwiki_p"
	),
	"changeDetector" => array(
		"serverName" => "sql-user-k",
		"dbName" => "u_knissen_changedetector"
	),
	"wikigini" => array(
		"serverName" => "sql-user-k",
		"dbName" => "u_knissen_wikigini"
	),
	"asqmRequestLog" => array(
		"serverName" => "sql-user-k",
		"dbName" => "u_knissen_asqm_u"
	)
);
