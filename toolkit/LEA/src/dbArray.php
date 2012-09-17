<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
$ts_pwd = posix_getpwuid( posix_getuid() );
$ts_mycnf = parse_ini_file( $ts_pwd['dir'] . "/.my.cnf" );
$db = mysql_connect( "sql", $ts_mycnf['user'], $ts_mycnf['password'] ) 
	or die( "could not connect to database server" );
mysql_select_db( "toolserver" ) or die( "could not select database" );

$dbArray = array();
$sql = "SELECT dbname, server FROM wiki WHERE family = 'wikipedia' ORDER BY server, dbname";
$result = mysql_query( $sql );
if ( $result ) {
	while ( $row = mysql_fetch_assoc( $result ) ) {
		$dbArray[$row['dbname']] = $row['server'];
	}
} else {
	exit();
}

mysql_close( $db );
