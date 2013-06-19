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

/*if ($language_code != '' && page_id != ''){
$query = 'SELECT title FROM pages WHERE language_code="' . $language_code . '" AND id="' . $page_id . '"';
$res = $mysqli->query($query);
if ($res->num_rows == 1) {
	$row = $res->fetch_assoc();
	$page_title = $row['title'];*/
	$load_graph = true;
/*}
}*/

//echo 'language_code=' . $language_code . ', page_id=' . $page_id . ', page_title=' . $page_title . '<br />';


?>





<?php
// Load and configure Highcharts only when it should be loaded
if ($load_graph) {
?>
		<script>
			var wikigini_data = [];
<?php
	echo "\t\t\tmode = '" . $mode . "';\n";
	echo "\t\t\tlanguage_code = '" . $language_code . "';\n";
	echo "\t\t\tpage_id = '" . $page_id . "';\n";
	echo file_get_contents('http://wikigini.fekepp.net/data.php?language=' . $language_code . '&pageid=' . $page_id);
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
