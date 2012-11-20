<?php
$ts_pw = posix_getpwuid(posix_getuid());

require($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/src/db.inc.php");


// Will be set to true if language_code and page_id are valid and thus a graph can be generated
$load_graph = false;

$language_code = 'en';
$page_id = '534366';
$mode = 'datetime';

// Check required parameters
if (!empty($_GET['language_code']) && !empty($_GET['page_id'])) {
	$language_code = $_GET['language_code'];
	$page_id = $_GET['page_id'];
}

if (isset($_GET['mode']) && ($_GET['mode'] == 'datetime' || $_GET['mode'] == 'revisions')) {
	$mode = $_GET['mode'];
}

//echo 'language_code=' . $language_code . ', page_id=' . $page_id . '<br />';

if ($language_code != '' && page_id != ''){
$query = 'SELECT title FROM pages WHERE language_code="' . $language_code . '" AND id="' . $page_id . '"';
$res = $mysqli->query($query);
if ($res->num_rows == 1) {
	$row = $res->fetch_assoc();
	$page_title = $row['title'];
	$load_graph = true;
}
}

//echo 'language_code=' . $language_code . ', page_id=' . $page_id . ', page_title=' . $page_title . '<br />';


?>





<?php
// Load and configure Highcharts only when it should be loaded
if ($load_graph) {
?>
		<script>
<?php
	echo "\t\t\tmode = '" . $mode . "';\n";
	echo "\t\t\tlanguage_code = '" . $language_code . "';\n";
	echo "\t\t\tpage_id = '" . $page_id . "';\n";
	echo "\t\t\tpage_title = '" . $page_title . "';\n";

	echo "\t\t\twikigini_data = [['datetime'], ['revisions'], ['info']];\n";

	$query = "SELECT
id,
unix_timestamp(datetime) * 1000 AS datetime,
ROUND(gini_index, 12) AS gini
FROM revisions WHERE method_id=1 AND language_code=\"" . $language_code . "\" AND page_id=" . $page_id . " ORDER BY datetime ASC";

	$res = $mysqli->query($query);

	$counter = 0;
	$res->data_seek(0);
	echo "\t\t\twikigini_data['datetime'] = [\n\t\t\t\t";
	while ($row = $res->fetch_assoc()) {
		echo "[" . $row["datetime"] . "," . $row["gini"] . "]" . ($counter < $res->num_rows - 1 ? "," : "");
		$counter++;
	}
	echo "\n\t\t\t];\n";

	$counter = 0;
	$res->data_seek(0);
	echo "\t\t\twikigini_data['datetime_info'] = [\n\t\t\t\t";
	while ($row = $res->fetch_assoc()) {
		echo "[" . $row["datetime"] . "]" . ($counter < $res->num_rows - 1 ? "," : "");
		$counter++;
	}
	echo "\n\t\t\t];\n";

	$query = "SELECT
id,
unix_timestamp(datetime) * 1000 AS datetime,
ROUND(gini_index, 12) AS gini
FROM revisions WHERE method_id=1 AND language_code=\"" . $language_code . "\" AND page_id=" . $page_id . " ORDER BY id ASC";

	$res = $mysqli->query($query);

	$counter = 0;
	$res->data_seek(0);
	echo "\t\t\twikigini_data['revisions'] = [\n\t\t\t\t";
	while ($row = $res->fetch_assoc()) {
		echo "[" . $row["id"] . "," . $row["gini"] . "]" . ($counter < $res->num_rows - 1 ? "," : "");
		$counter++;
	}
	echo "\n\t\t\t];\n";

	$counter = 0;
	$res->data_seek(0);
	echo "\t\t\t";
	while ($row = $res->fetch_assoc()) {
	//echo "\t\t\twikigini_data['datetime_info'] = [[" . $row["datetime"] . "];";
	echo "wikigini_data['datetime_info'][" . $row["datetime"] . "] = [" . $row["id"] . ", " . $row["gini"] . "];";
	}

	$counter = 0;
	$res->data_seek(0);
	echo "\t\t\twikigini_data['revisions_info'] = [\n\t\t\t\t";
	while ($row = $res->fetch_assoc()) {
		echo "[" . $row["id"] . "]" . ($counter < $res->num_rows - 1 ? "," : "");
		$counter++;
	}
	echo "\n\t\t\t];\n";

	$counter = 0;
	$res->data_seek(0);
	echo "\t\t\t";
	while ($row = $res->fetch_assoc()) {
	//echo "\t\t\twikigini_data['datetime_info'] = [[" . $row["datetime"] . "];";
	echo "wikigini_data['revisions_info'][" . $row["id"] . "] = [" . $row["datetime"] . ", " . $row["gini"] . "];";
	}
echo "\n";
?>
		</script>
		<script type="text/javascript" src="/<?php echo $tsAccount; ?>/toolkit/WIKIGINI/js/highcharts.js"></script>
		<script type="text/javascript" src="/<?php echo $tsAccount; ?>/toolkit/WIKIGINI/js/wikigini.js"></script>
<?php
}
?>
		<script>
			$(function() {
				$('#page_switch').change(function() {
					$("select option:selected").each(function () {
						window.location.href  = '?' + $(this).val();
					});
				});
			});
		</script>
