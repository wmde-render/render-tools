<?php

/*
 * Name: cd2db_query.inc
 * 
 * Description: additional functions for CD2_Abfrage.php
 * 				- construct the db-query for the Change Detector
 * 				and enriching the datastructure
 * 				- search for an alternative article 
 * 
 * Autor: Anselm Metzger
 * 
*/




function query_change_db($date, $LangGroup, $cut = TRUE , $No_Filter, $database_name ='p_render_change_detector_p' ){

// Only usual
	$Query_filter = "AND only_usual=1 ";
// Only_major
if ($No_Filter["o_m"]) {
	$Query_filter .= "AND only_major=1 ";
	}
// Non_Bots
if ($No_Filter["n_b"]) {
	$Query_filter .= "AND non_bot=1 ";
	}
// Many_Users
if ($No_Filter["m_u"]) {
	$Query_filter .= "AND many_user=1 ";
	}

// Transform the LanguageGroup array

$LangList = "";
foreach ($LangGroup as $k => $language){
	if ($k != 0) $LangList .= ",";
	$LangList .= " '".$language."' ";
		
	}
echo "<!--";
echo $LangList;
echo "-->";

// Open database

$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/.my.cnf");
$db = mysql_connect('sql.toolserver.org', $ts_mycnf['user'], $ts_mycnf['password']);

if (!$db) {
    die('Connection error (db-sql): ' . mysql_error());
}

mysql_select_db($database_name, $db);

// Query for all relvant entries from p_render_change_detector_p.changed_article

$result = mysql_query('SELECT * FROM changed_article WHERE day='.$date.' AND language in ('.$LangList.')'.$Query_filter );

while($row = mysql_fetch_object($result)){

		if (!$Final_Result[$row->indentifier][$row->language] == NULL) continue;	

		// Array for entry information
		$subarray =  array ( "wiki_id" => $row->page_id ,
							 "title" => $row->page_title, 
							"filter_only_major" => $row->only_major ,
							"filter_only_usual" => $row->only_usual ,
							"filter_non_bot" => $row->non_bot ,
							"filter_many_user" => $row->many_user  							
					);
		
		$Final_Result[$row->identifier][$row->language] = $subarray;			


		// Adding language in Array Changed
		$Final_Result[$row->identifier]["Changed"][$row->language] = $row->page_title;	
		
	
}				  	



$threshold = 2;
if ($cut) $threshold = floor(count($LangGroup)/2);

// Adding information for unchanged articles and removing entries with low change rates

foreach($Final_Result as $id => $v_array) {
	
	if (count($v_array["Changed"]) > $threshold) {
	
	$result_noticed = mysql_query('SELECT * FROM noticed_article WHERE day='.$date.' AND identifier='.$id.' AND language in ('.$LangList.')' );
	
	while($row = mysql_fetch_object($result_noticed)){
		$temp_lang = $row->language;
		if (!array_key_exists($temp_lang, $v_array["Changed"])){
			
	
			$v_array["Unchanged"][$temp_lang] = $row->page_id;
				
			
		}
	}
	
	$Final_Result2[$id] = $v_array;
	}
}


mysql_close($db);


// Adding titles for Unchanged articles

foreach ($LangGroup as $k => $language) {
	
	
	$LangVers_wiki = $language . "wiki_p";
	$LangVers_wiki_db = $language . "wiki-p.rrdb.toolserver.org";

	// Datenbank oeffnen
	
	$db2 = mysql_connect($LangVers_wiki_db, $ts_mycnf['user'], $ts_mycnf['password']);

	if (!$db2) {
		die('Connection error (db - Titel holen): ' . mysql_error());
	}

	mysql_select_db($LangVers_wiki, $db2);
	
	foreach ($Final_Result2 as $id => &$v_array)	{
		
		if (array_key_exists($language, $v_array["Unchanged"])){ 	
		
			$search_id = $v_array["Unchanged"][$language];
		
			$exist = mysql_query('SELECT page_title FROM page WHERE page_id = "'.$search_id.'"');
	
			$temp_title = mysql_fetch_object($exist);
			$temp_title = $temp_title->page_title;
		
			
		
			
			$v_array[$language]["title"] = $temp_title;
		
			}
		}

	mysql_close($db2);

}


unset($ts_mycnf, $ts_pw);

return $Final_Result2;
/* The resulting array contains for every Indentifier the information for
 * 	#the languageversion (title, wiki_id)
 *  #the array of changed articles with title
 *  #the array of unchanged articles with title  
*/

}


// Additional function if reflang has no article and therefore no title
// In that case english (or french) is preferred.
// If no english article is present a random language is choosen

function search_other_article($Entry, $reflang){
	
	foreach ($Entry as $k => $v){
		if ($k != "Changed" AND $k != "Unchanged")
			$temp_Entry[$k] = $v;	
		}
	
	$such_lang = "fr";
	if ($reflang != "en") $such_lang = "en";
	
	
	foreach ($temp_Entry as $Sprache => $subarray){
		
		if ($Sprache == $such_lang) {
			$Final_Result["article"] = $subarray["title"];
			$Final_Result["lang"] = $Sprache;
			}
	}
	if (!isset($Final_Result)) {
		$temp_lang = array_rand($temp_Entry);
			$Final_Result["article"] = $temp_Entry[$temp_lang]["title"];
			$Final_Result["lang"] = $temp_lang;
		
		} 
	
	return $Final_Result;

}

?>
