<?php
$ts_pw = posix_getpwuid(posix_getuid());

require($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/src/db.inc.php");

$aid = intval($_GET['aid']);
$query = "SELECT * FROM `sa_html` WHERE `article_id` = '".$aid."' AND `method_id` = '1' ORDER BY `index`";
$result = mysql_query($query) or die(mysql_error());

#echo $result;

$resulthtml = '';
while($row = mysql_fetch_array($result)) {
	#echo stripslashes($row['html']);
	#echo stripslashes($row['html']);
	$resulthtml .= stripslashes($row['html']);
}

echo $resulthtml;
?>