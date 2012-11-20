<?php
$ts_pw = posix_getpwuid(posix_getuid());

require($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/src/db.inc.php");
?>

<div id="Ueberschrift" style="float: left; vertical-align: middle;">
	<div id="Introduction">
		<h2>
			<?php echo $Headline; ?>
		</h2>
		<p id="Description">
			<?php echo $Description; ?>
		</p>
<!-- 		<p>
			<a href="info.php"><?php echo $MoreInfo; ?> </a>
		</p> -->
	</div>
		<div style="padding:5px;">
			<select id="page_switch" name="page_switch" style="border:1px black solid;">
<?php
	$query = 'SELECT * FROM top50_page_revision_count';;
	$res = $mysqli->query($query);
	while ($row = $res->fetch_assoc()) {
		$top50_language_code = $row['language_code'];
		$top50_page_id = $row['id'];
		$top50_page_title = $row['title'];
		$top50_revision_count = $row['revision_count'];
//&#61;
		echo "\t\t\t\t<option value=\"language_code=" . $top50_language_code . "&page_id=" . $top50_page_id . "\""
			. ($language_code == $top50_language_code && $page_id == $top50_page_id ? " selected=\"selected\"" : "") . ">"
			. $top50_page_title . " (ID: ". $top50_page_id . ", L: " . $top50_language_code . ", R: " . $top50_revision_count . ")</option>\n";
	}
?>
			</select>
			Select page (top revision count)
		</div>
		<div id="wikigini_graph" style="min-width:400px; height:400px; margin:0 auto"></div>
		<div style="padding:5px;">
			<input id="mode_switch" type="button" name="mode_switch" value="Switch mode" style="border:1px black solid;">
			<input id="batch_last" type="button" name="batch_last" value="&lt;&lt; 0-0" style="border:1px black solid;min-width:125px;">
			<input id="batch_current" type="button" name="batch_current" value="0-0" style="border:1px black solid;min-width:125px;">
			<input id="batch_next" type="button" name="batch_next" value="0-0 &gt;&gt;" style="border:1px black solid;min-width:125px;">
		</div>