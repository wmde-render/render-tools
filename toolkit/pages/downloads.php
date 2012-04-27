<?php
$ts_pw = posix_getpwuid(posix_getuid());
include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");
include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/downloads_txt.inc");
?>

<div id="Description" style="width: 75%">
	<div id="Ueberschrift">
		<h1><?php echo $Headline; ?></h1>
		<p><?php echo $Introduction; ?></p>
	</div>

	<table class="downloads">
		<thead>
			<tr>
				<th class="dl-name"><?php echo $dlHeadName; ?></th>
				<th class="dl-size"><?php echo $dlHeadSize; ?></th>
				<th class="dl-desc"><?php echo $dlHeadDesc; ?></th>
			</tr>
		</thead>
		<tbody>
<?php
foreach ($files as $file) {
	if (empty($fileLink)) {
		$fileLink = $file['link'];
	} else {
		$fileLink = "/" . $tsAccount . "/toolkit/downloads/" . $file['name'];
	}
?>
			<tr>
				<td class="dl-name">
					<a href="<?php echo $fileLink; ?>">
						<?php echo $file['name']; ?>
					</a>
				</td>
				<td class="dl-size"><?php echo $file['size']; ?></td>
				<td class="dl-desc"><?php echo $file['desc']; ?></td>
			</tr>
<?php } // end foreach ?>
		</tbody>
	</table>
</div>

<?php
include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
