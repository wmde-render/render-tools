<?php
//LEA index.php
$ts_pw = posix_getpwuid(posix_getuid());
include( "../pages/Tablehead.php" );
include( "inc/lang/" . $_SESSION["lang"] . "/displ_txt.inc" );
include( "src/lea.php" );
include( "../pages/Tablefoot.php" );
