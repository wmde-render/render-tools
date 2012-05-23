<?php
$ts_pw = posix_getpwuid(posix_getuid());
include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/about_txt.inc");
?>
<div class="description">
	<h2><?php echo $Headline; ?></h2>
	<?php echo $Introduction; ?>
	<h3><?php echo $heading1; ?></h3>
	<?php echo $Text1; ?>
	<h3><?php echo $heading2; ?></h3>
	<?php echo $Text2; ?>
	<h3><?php echo $heading3; ?></h3>
	<?php echo $Text3; ?>
</div>

<?php
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
