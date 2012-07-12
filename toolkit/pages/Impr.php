<?php
$ts_pw = posix_getpwuid( posix_getuid() );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/" . $_SESSION["lang"] . "/impr_txt.inc" );
?>
<div id="Description">
	<div id="Ueberschrift">
		<h1><?php echo $Headline; ?></h1>
		<p><?php echo $Introduction; ?></p>
	</div>
	<div id="info">
		<p><?php echo $Adress["Title"]; ?></p>
		<p><?php echo $Adress["Postbox"]; ?></p>
		<p><?php echo $Adress["Zip"]; ?></p>
		<p>&nbsp;</p>
		<p><?php echo $Adress["Phone"]; ?></p>
		<p><?php echo $Adress["Fax"]; ?></p>
		<p><?php echo $Adress["Email"]; ?></p>
	</div>
	<div id="Ueberschrift">
		<h2><?php echo $Disclaimer["Head"]; ?></h2>
		<p><?php echo $Disclaimer["Text1"]; ?></p>
		<p><?php echo $Disclaimer["Text2"]; ?></p>
	</div>
</div>
<?php include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php"); ?>