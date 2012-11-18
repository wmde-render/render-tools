<?php
$ts_pw = posix_getpwuid( posix_getuid() );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/LEA/inc/lang/" . $_SESSION["lang"] . "/info_txt.inc" );
?>
<div class="description">
	<h2><?php echo $Headline; ?></h2>
	<p><?php echo $Introduction; ?></p>
	<h3><?php echo $Settings["Headline"]; ?></h3>
	<p><?php echo $Settings["Text"]; ?></p>
	<h3><?php echo $Results["Headline"]; ?></h3>
	<p><?php echo $Results["Text"]; ?></p>
	<h3><?php echo $Uses["Headline"]; ?></h3>
	<p><?php echo $Uses["Text"]; ?></p>
</div>

<?php include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
