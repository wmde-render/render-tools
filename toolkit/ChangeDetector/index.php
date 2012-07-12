<?php
//CD index.php
$ts_pw = posix_getpwuid(posix_getuid());
include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/ChangeDetector/inc/lang/".$_SESSION["lang"]."/displ_txt.inc");
include($ts_pw['dir'] . "/public_html/toolkit/ChangeDetector/src/CD2_Abfrage.php");
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");