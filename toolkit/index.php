<?php
// index.php
$ts_pw = posix_getpwuid(posix_getuid());
include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/home_txt.inc");
?>

<div id="Description">
	<div id="Ueberschrift">
		<h1><?php echo $Headline; ?></h1>
		<p><?php echo $Introduction; ?></p>
	</div>
	<div id="info">
		<p><?php echo $Text1; ?></p>
		<p><?php echo $Text2; ?></p>
		<p><?php echo $Text3; ?></p>
	</div>
</div>

<?php
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
