<?php
$ts_pw = posix_getpwuid( posix_getuid() );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php" );
include( $ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/about_txt.inc" );
?>
<script type="text/javascript">
$(function() {
	$( "#thumb-tlg" ).dialog({
		height: 478,
		width: 560,
		modal: true,
		autoOpen: false,
		title: "Mockup of the Task List Generator"
	});

	$( "#thumb-asqm" ).dialog({
		height: 550,
		width: 408,
		modal: true,
		autoOpen: false,
		title: "Mockup of the ASQM"
	});
});
</script>
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
<div id="thumb-tlg" style="font-size: 0.8em; text-align: center;">
	<img src="/<?php echo $tsAccount; ?>/toolkit/img/tlg_draft.png" /><br />
	<?php echo $imgCaptionTlg; ?>
</div>
<div id="thumb-asqm" style="font-size: 0.8em; text-align: center;">
	<img src="/<?php echo $tsAccount; ?>/toolkit/img/asqm_draft.png" /><br />
	<p><?php echo $imgCaptionAsqm; ?></p>
</div>
<?php
include( $ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php" );
