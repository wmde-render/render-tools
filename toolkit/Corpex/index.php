<?php
// Corpex index.php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/Corpex/inc/lang/".$_SESSION["lang"]."/displ_txt.inc");
include($ts_pw['dir'] . "/public_html/toolkit/Corpex/src/corpex.php");
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
