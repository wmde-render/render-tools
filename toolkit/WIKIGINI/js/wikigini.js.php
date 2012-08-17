<?php
// DEBUG
ini_set( 'display_errors', 'stdout' );

$ts_pw = posix_getpwuid(posix_getuid());

require($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/src/db.inc.php");


# 1. reading available methods

$query = "SELECT * FROM sa_method";
$result = mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)) {
	$method[$row['id']] = $row['method_name'];
}
// print_r($method);

# 2. reading analyzed articles

$query = "SELECT * FROM sa_article ORDER BY article_name";
$result = mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)) {
	$article[$row['id']] = $row['article_name'];
}

// print_r($article);

# 3. for each of existing methods read revisions to the choosed article

// $article_id = $_POST['article_id'];
$article_id = $_GET['article_id'];

foreach($method as $key => $value) {
	$query = "SELECT * FROM sa_revision WHERE article_id = '".$article_id."' AND method_id = '".$key."' ORDER BY revision_id";
	$result = mysql_query($query) or die(mysql_error());

	#$dateseries[$key] = array();

	$i = 0;
	while($row = mysql_fetch_array($result)) {
		$dateseries[$key][$i] 		= array(strtotime($row['revision_datetime']) * 1000, $row['gini_index']);
		#$revisionidseries[$key][$i] 	= array($row['revision_id'], $row['gini_index']);
		$revisionidseries[$key][$i] 	= array($i, $row['gini_index']);
		$index_revisionid[$key][$i] 	= $row['revision_id'];
		#echo $dateseries[$key][$i];
		$i++;
	}
}
?>


var chartd;
var chartr;
var categories;

<?php
$numberofrevisions = count($index_revisionid[1]);
// $page = $_POST['page'];
$page = $_GET['page'];
$endpage = intval($numberofrevisions / 1000) + 1;

if(!$page) {
	$page = 1;
}

foreach($method as $key => $value) {
	$i = 0;
	foreach(range(($page - 1) * 1000, $page * 1000) as $index) {
		if($index < $numberofrevisions) {
			$dateseries_toshow[$key][$i] = $dateseries[$key][$index];
			$revisionidseries_toshow[$key][$i] = $revisionidseries[$key][$index];
			#$index_revisionid_toshow[$key][$i] = $index_revisionid[$key][$index];
			$i++;
		}
	}
}

$dateseries = $dateseries_toshow;
$revisionidseries = $revisionidseries_toshow;
#$index_revisionid = $index_revisionid_toshow;

echo "categories = ".json_encode($index_revisionid[1]).";\n";

?>


			$(document).ready(function() {
				chartd = new Highcharts.Chart({
					<?php
// 					if($_POST['haxis'] == 'datetime') {
					?>
					<?php
					if($_GET['haxis'] == 'datetime') {
					?>
						chart: {renderTo: 'container', zoomType: 'x', defaultSeriesType: 'area'},
						title: {text: 'Gini-Index Time Development for Wikipedia-Article "<?php echo $article[$article_id] ?>"'},
						subtitle: {text: 'Developed by: <b>Andriy Rodchenko</b> (AIFB, KIT)'},
						xAxis: {type: 'datetime'},
						yAxis: {title: {text: 'Gini-Index'}, min: 0, max: 1.0},
						tooltip: {formatter: function() {return '<b>' + this.series.name + '</b><br/><b>Date</b>: ' + Highcharts.dateFormat('%A, %b %e, %Y', this.x) + '<br/><b>Gini-Index</b>: ' + this.y;}, crosshairs: true},
						plotOptions: {
							area: {fillOpacity: 0.3, marker: {enabled: true, symbol: 'circle', radius: 3, states: {hover: {enabled: true}}}}
						},
						<?php # generate data for each of analysis methods separately
							foreach($method as $key => $value) {
								$seriesintextarray[] = "{name: '".$value."', data: ".json_encode($dateseries[$key])."}";
							}
							$seriesstring = implode(",", $seriesintextarray);
						?>
						series: [<?php echo $seriesstring; ?>]
					<?php 
// 					} elseif($_POST['haxis'] == 'revisionid') {
					?>
					<?php 
					} elseif($_GET['haxis'] == 'revisionid') {
					?>
						chart: {renderTo: 'container', zoomType: 'x', defaultSeriesType: 'area'},
						title: {text: 'Gini-Index Time Development for Wikipedia-Article "<?php echo $article[$article_id]; ?>"'},
						subtitle: {text: 'Developed by: <b>Andriy Rodchenko</b> (AIFB, KIT)'},
						xAxis: {type: 'number'},
						yAxis: {title: {text: 'Gini-Index'}, min: 0, max: 1.0},
						tooltip: {formatter: function() {return '<b>' + this.series.name + '</b><br/><b>Revision ID</b>: ' + categories[this.x] + '<br/><b>Gini-Index</b>: ' + this.y;}, crosshairs: true},
						plotOptions: {
							area: {fillOpacity: 0.3, marker: {enabled: true, symbol: 'circle', radius: 3, states: {hover: {enabled: true}}}}
						},
						<?php # generate data for each of analysis methods separately
							foreach($method as $key => $value) {
								$seriesintextarray[] = "{name: '".$value."', data: ".json_encode($revisionidseries[$key])."}";
							}
							$seriesstring = implode(",", $seriesintextarray);
						?>
						series: [<?php echo $seriesstring; ?>]
					<?php } ?>
				})
			})
