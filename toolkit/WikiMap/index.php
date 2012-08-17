<?php
// WikiMap index.php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/WikiMap/inc/lang/".$_SESSION["lang"]."/displ_txt.inc");
include($ts_pw['dir'] . "/public_html/toolkit/WikiMap/src/map.php");
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
