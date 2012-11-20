<?php
// Corpex index.php
$ts_pw = posix_getpwuid(posix_getuid());

$load_wikigini = true;

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/lang/".$_SESSION["lang"]."/displ_txt.inc");
include($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/src/wikigini.php");
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
