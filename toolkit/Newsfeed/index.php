<?php
$ts_pw = posix_getpwuid(posix_getuid());
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/" . $_SESSION["lang"] . "/newsfeed_txt.inc.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/Newsfeed/src/jsi-newsfeed.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablefoot.php" );
