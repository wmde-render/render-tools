<?php

function query_change_db($date, $database_name ='u_philippze_change_detector_p' ){

if ($date != "20120219") $date = "20120219";

$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/.my.cnf");
$db = mysql_connect('sql.toolserver.org', $ts_mycnf['user'], $ts_mycnf['password']);
unset($ts_mycnf, $ts_pw);
if (!$db) {
    die('Verbindung schlug fehl: ' . mysql_error());
}

mysql_select_db($database_name, $db);

// Alle Einträge für einen Tag holen
// Variante changed_article
$result = mysql_query('SELECT * FROM changed_article WHERE day='.$date);

while($row = mysql_fetch_object($result)){

		if ($Ergebnis[$row->indentifier][$row->language] == NULL){	

		// Daten für einen Spracheintrag
		$subarray =  array ( "wiki_id" => $row->page_id ,
							 "titel" => $row->page_title, 
							"filter_only_major" => $row->only_major ,
							"filter_only_usual" => $row->only_usual ,
							"filter_non_bot" => $row->non_bot ,
							"filter_many_user" => $row->many_user  							
					);
		
		// Gespeichert im Ergebnis Array unter dem Identifizierer und der Sprache	
		$Ergebnis[$row->identifier][$row->language] = $subarray;			

// Richtige Verteilungsfunktion
		
		$Ergebnis[$row->identifier]["Changed"][$row->language] = $row->page_title;	
		
	}
/*
// Verteilung auf Changed/Unchanged per Zufall
		if ($row->page_id % 2 == 1) {
			$Ergebnis[$row->identifier]["Changed"][$row->language] = $row->page_title;	
		} else {
			$Ergebnis[$row->identifier]["Unchanged"][$row->language] = $row->page_title;
		}
*/


	
}				  	



foreach($Ergebnis as $id => $v_array) {
	
	if (count($v_array["Changed"]) > 3) {
	
	$result_noticed = mysql_query('SELECT * FROM noticed_article WHERE day='.$date.' AND identifier='.$id);
	
	while($row = mysql_fetch_object($result_noticed)){
		$temp_lang = $row->language;
		if (!array_key_exists($temp_lang, $v_array["Changed"])){
			
			// Version für nicht funktionierenden toolserver
			$v_array["Unchanged"][$temp_lang] = $row->page_id;
			$v_array[$temp_lang]["titel"] = $row->page_id;
			
			// Version für nicht korrekte page-Tabelle
			// $temp_titel = artikel_titel_mit_id_abfragen($row->page_id)
			//$v_array["Unchanged"][$temp_lang] = $temp_titel;
			//$v_array[$temp_lang] = $temp_titel;
			
		}
	}
	
	$Ergebnis2[$id] = $v_array;
	}
}


mysql_close($db);

return $Ergebnis2;
/* Der Ergebnis Array enthält für jeden Identifizierer 
die jeweiligen Sprachinformationen und zusätzlich die beiden Arrays Changed und Unchanged.
Sprachen die nicht aufgeführt werden, besitzen keinen entsprechenden Artikel.
*/

}

function search_other_article($Eintrag, $reflang){
	
	foreach ($Eintrag as $k => $v){
		if ($k != "Changed" AND $k != "Unchanged")
			$temp_Eintrag[$k] = $v;	
		}
	
	$such_lang = "fr";
	if ($reflang != "en") $such_lang = "en";
	
	
	foreach ($temp_Eintrag as $Sprache => $subarray){
		
		if ($Sprache == $such_lang) {
			$Ergebnis["Artikel"] = $subarray["titel"];
			$Ergebnis["lang"] = $Sprache;
			}
	}
	if (!isset($Ergebnis)) {
		$temp_lang = array_rand($temp_Eintrag);
			$Ergebnis["Artikel"] = $temp_Eintrag[$temp_lang]["titel"];
			$Ergebnis["lang"] = $temp_lang;
		
		} 
	
	return $Ergebnis;

}

?>
