<?php
$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/.my.cnf");
$db = mysql_connect('sql.toolserver.org', $ts_mycnf['user'], $ts_mycnf['password']);

if (!$db) {
	die('Connection error (db-sql): ' . mysql_error());
}

mysql_select_db('u_knissen_wikigini', $db);
