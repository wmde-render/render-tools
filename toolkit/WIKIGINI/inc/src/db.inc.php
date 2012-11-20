<?php
$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/.my.cnf");

$mysqli = new mysqli('sql.toolserver.org', $ts_mycnf['user'], $ts_mycnf['password'], "p_render_wikigini_p");
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
