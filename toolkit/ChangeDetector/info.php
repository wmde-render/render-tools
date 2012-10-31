<?php
$ts_pw = posix_getpwuid(posix_getuid());
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/ChangeDetector/inc/lang/" . $_SESSION["lang"] . "/info_txt.inc" );
?>

<div id="Description">
	<div id="Ueberschrift">
		<h1><?php echo $Headline; ?></h1>
		<p><?php echo $Introduction; ?></p>
		<h2><?php echo $Settings["Headline"]; ?></h2>
		<p><?php echo $Settings["Text"]; ?></p>
		<h2><?php echo $Results["Headline"]; ?></h2>
		<p><?php echo $Results["Text"]; ?></p>
		<h2><?php echo $Uses["Headline"]; ?></h2>
		<p><?php echo $Uses["Text"]; ?></p>
	</div>
	<!-- <div id="info">&nbsp;</div> -->
	<div id="Lizenz"><?php echo $Lizenz; ?></div>
</div>
<?php include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablefoot.php" );