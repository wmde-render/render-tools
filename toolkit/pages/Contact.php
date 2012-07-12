<?php
$ts_pw = posix_getpwuid( posix_getuid() );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/" . $_SESSION["lang"] . "/contact_txt.inc" );
?>
<div id="Description">
	<div id="Ueberschrift">
		<h1><?php echo $Headline; ?></h1>
		<p><?php echo $Introduction; ?></p>
	</div>
	<div id="info">
		<h2><?php echo $Acknoledgments["Headline"]; ?></h2>
		<p><?php echo $Acknoledgments["Text"]; ?></p>
	</div>
</div>
<?php include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php"); ?>