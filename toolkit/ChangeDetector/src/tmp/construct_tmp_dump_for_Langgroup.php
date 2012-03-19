#! /usr/bin/php
#$ -l h_rt=2:00:00
#$ -l virtual_free=20M
#$ -j y
#$ -wd /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/
<?php

include("../../inc/src/cd2db_query.inc");

// This programm is designed for constructing temporary-files for ChangeDetector
// target are 32 tmp*.dump files
// There are 5 Parameters with two values each.
// The prgram constructs 4 child processes with a combination of:
// 	LanguageGroup 
//	Filter Many_users
// In each child there are loops for:
//  Cuthalf
//  Filter Only Many
//  Filter non_bots
 
// to run the Program with qsub change the -wd (working-directory-PATH) 
//

if (!isset($argv[1])) die ("Not the right LanguageGroup parameter given as argument (EU or All).");	
if ($argv[1] == "EU") {
	$LangGroup = array("de","en", "fr", "pt","it","pl","ru","nl","sv","es");
	$LangGroupname = "EU";
	} 
if ($argv[1] == "All") {
	$LangGroup = array("de","en","fr", "pt","it","pl","ru","nl", "sv","es", "ja","zh");
	$LangGroupname = "All";
	} 
if (!isset($LangGroup)) die ("Not the right LanguageGroup parameter given as argument (EU or All).");	


if (!isset($argv[2])) die ("Not the right FilterMU parameter given as argument (on or off).");	
if ($argv[1] == "on") {
				$MU = "on";
				$No_Filter["m_u"] = TRUE;
	} 
if ($argv[1] == "off") {
				$MU = "";
				$No_Filter["m_u"] = FALSE;
	} 
if (!isset($argv[2])) die ("Not the right FilterMU parameter given as argument (on or off).");	


$now = time();
$yesterday = $now - ( 1 * 24 * 60 * 60);
$day = date('Ymd', $yesterday);


$CutHalv[] = "on";
$CutHalv[] = "";

$FilterNB[] = "on";
$FilterNB[] = "";


$FilterOM[] = "on";
$FilterOM[] = "";


				


foreach ($CutHalv as $k => $CutHlf){
	foreach ($FilterNB as $j => $NB){
		foreach ($FilterOM as $i => $OM){
						
				
			

			$No_Filter["n_b"] = TRUE;
			if ($NB != "on") $No_Filter["n_b"] = FALSE;

			$No_Filter["o_m"] = TRUE;
			if ($OM != "on") $No_Filter["o_m"] = FALSE;		
			
			$Cuthalf = TRUE;
			if ($CutHlf != "on") $Cuthalf = FALSE;

			$file_name = "tmp_".$day.$LangGroupname."1".$CutHlf."2".$MU."3".$NB."4".$OM.".dump";

			$db_result = query_change_db($day , $LangGroup, $Cuthalf, $No_Filter, 'p_render_change_detector_p');
	
			$uniqueID = uniqid("tmp").".tmp";
			file_put_contents($uniqueID , serialize($db_result));
			rename($uniqueID , $file_name);

			echo "\n Filename:".$file_name."\n";	
		}
	}	
}
			

		

?>
