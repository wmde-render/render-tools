<?php
# settings for error reporting, deactivate for production mode
ini_set( 'display_errors', 1 );
ini_set( 'log_errors', 1 );
ini_set( 'error_log', dirname(__FILE__) . '/error_log.txt' );
error_reporting( E_ALL );

# absolute path to application folder
define( 'APP_PATH', '' );
# absolute path to template folder
define( 'TEMPLATE_PATH', '' );
# relative path to index.php
define( 'BASE_PATH', '' );

# task list generator backend service url
define( 'TLG_SERVICE_URL', '' );

# database names and server names
$dbLinks = array(
	"dbName" => "serverName",
);
