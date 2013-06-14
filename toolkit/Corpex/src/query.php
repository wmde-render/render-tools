<?php
$path_data = "/data/project/render-tests/datasets/corpex/data";
// $path_data = "/mnt/user-store/render/corpex/data";
// $path_data = "/home/fekepp/projects/corpex/data";

/*
 /<?php echo $tsAccount; ?>/
*/

ini_set( 'memory_limit', '512M' );
$query = $_GET["q"]; // TODO Sanitize, lower case
$lang = $_GET["lang"]; // TODO Sanitize, lower case
if ($query === '') {
	$lines = file($path_data . "/$lang/_.json");
	foreach($lines as $num => $line) {
		echo $line;
	}
	exit(0);
}
$index = file($path_data . "/" . $lang . "/_index.txt");
$hit = "";
foreach($index as $num => $cache) {
	$cache = trim($cache);
	if ($query === $cache) {
		$lines = file($path_data . "/$lang/$query.json");
		foreach($lines as $num => $line) {
			echo $line;
		}
		exit(0);
	}
	if ($hit === "") if (strpos($query, $cache) === 0) $hit = $cache;
}

if ($hit === "") $hit = "_";

echo "{\n";
echo ' "hit" : "' . $hit . "\",\n"; 
$lines = file($path_data . "/" . $lang . "/$hit.txt");
$frequency = 0;
$all = 0;
$others = 0;
$top = 10;
$words = "";
$next = array();

foreach($lines as $num => $line) {
	$tok = explode(' ', $line);
	$word = $tok[0];
	$count = intval($tok[1]);
	$all += $count;
	if (strpos($word, $query) === 0) {
		$frequency += $count;
		if($top-->0) {
			$words .= '"' . $word . '" : ' . $count . ", ";
		} else {
			$others += $count;
		}
		if (strlen($word) === strlen($query)) {
			$next['$'] = $count;
		} else {
			$char = $word[strlen($query)];
			if (array_key_exists($char, $next)) {
				$next[$char] = $next[$char] + $count;
			} else {
				$next[$char] = $count;
			}
		}
	}
}
ksort($next);
?>
 "query" : "<?php echo $query; ?>",
 "lang" : "<?php echo $lang; ?>",
 "freq" : <?php echo $frequency; ?>,
 "all" : <?php echo $all; ?>,
 "next" : { <?php
 $first = true;
 foreach($next as $char => $count) {
 	if ($first) { $first = false; } else { echo ", "; }
 	echo '"' . $char . '" : ' . $count; 
 }
 if ($first) echo '"$" : 0';
 ?> },
 "words" : { <?php
 echo $words; 
 echo '"..." : ' . $others;
 ?> } 
}
