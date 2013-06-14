<?php
/**
 * Name: cd2db_query.inc
 * 
 * Description: additional functions for CD2_Abfrage.php
 * 				- construct the db-query for the Change Detector
 * 				and enriching the datastructure
 * 				- search for an alternative article 
 * 
 * Autor: Anselm Metzger
 * 
 **/

function query_change_db( $date, $LangGroup, $cut = TRUE , 
		$No_Filter, $database_name ='p50380g50454__change_detector' ) {

	// Only usual
	$Query_filter = "AND only_usual = 0 ";

	// Only_major
	if( $No_Filter["o_m"] ) {
		$Query_filter .= "AND only_major = 1 ";
	}

	// Non_Bots
	if( $No_Filter["n_b"] ) {
		$Query_filter .= "AND non_bot = 1 ";
	}

	// Many_Users
	if( $No_Filter["m_u"] ) {
		$Query_filter .= "AND many_user = 1 ";
	}

	// Open database
	$ts_pw = posix_getpwuid( posix_getuid() );
	$ts_mycnf = parse_ini_file( $ts_pw['dir'] . "/replica.my.cnf" );
	$db = mysql_connect( 'tools-db', $ts_mycnf['user'], $ts_mycnf['password'] );

	if( !$db ) {
		die( 'Connection error (db-sql): ' . mysql_error() );
	}
	mysql_select_db( $database_name, $db );

	$res_lang_query = mysql_query( "SELECT * FROM lang_update WHERE day = " . $date );

	while( $lang_row = mysql_fetch_object( $res_lang_query ) ) {
		$LangGroupreal[] = $lang_row->lang;
	}

	$LangGroup = array_intersect( $LangGroup, $LangGroupreal );

	// Transform the LanguageGroup array
	$LangList = " '" . implode( "' , '", $LangGroup ) . "' ";

	// Query for all relvant entries from p_render_change_detector_p.changed_article
	$result = mysql_query( 'SELECT * FROM changed_article WHERE day=' . $date .
			' AND language in (' . $LangList . ')' . $Query_filter );

	while( $row = mysql_fetch_object( $result ) ) {
		if( !$Final_Result[$row->identifier][$row->language] == NULL ) {
			continue;
		}

		// Array for entry information
		$subarray =  array(
			"wiki_id" => $row->page_id,
			"title" => $row->page_title,
			"filter_only_major" => $row->only_major,
			"filter_only_usual" => $row->only_usual,
			"filter_non_bot" => $row->non_bot,
			"filter_many_user" => $row->many_user
		);

		$Final_Result[$row->identifier][$row->language] = $subarray;			

		// Adding language in Array Changed
		$Final_Result[$row->identifier]["Changed"][$row->language] = $row->page_title;	
	}				  	

	$threshold = 3;
	if( $cut ) {
		$threshold = floor( count( $LangGroup ) / 2 );
	}

	// Adding information for unchanged articles 
	// and removing entries with low change rates
	$query_noticed = mysql_query(
		'SELECT * FROM noticed_article WHERE day = ' . $date .
		' AND language in (' . $LangList . ')' );

	while( $row = mysql_fetch_object( $query_noticed ) ) {
		$noticed_result[$row->identifier][$row->language] = $row->page_id;
	}

	foreach( $Final_Result as $id => $v_array ) {
		if( count( $v_array["Changed"] ) >= $threshold ) {
			foreach( $noticed_result[$id] as $noticed_language => $noticed_page_id ) {
				if( !isset( $v_array["Changed"][$noticed_language] ) ) {
					$v_array["Unchanged"][$noticed_language] = $noticed_page_id;
				}
			}

			$Final_Result2[$id] = $v_array;
		}
	}

	mysql_close($db);
	$between_time = time();
	$temp_time = $between_time - $start_time;

	// Adding titles for Unchanged articles
	foreach( $LangGroup as $k => $language ) {
		$LangVers_wiki = $language . "wiki_p";
		$LangVers_wiki_db = $language . "wiki.labsdb";

		// Datenbank oeffnen
		$db2 = mysql_connect( $LangVers_wiki_db, $ts_mycnf['user'], $ts_mycnf['password'] );

		if( !$db2 ) {
			die( 'Connection error (db - Titel holen ' . $LangVers_wiki_db . '): ' . mysql_error() );
		}

		mysql_select_db( $LangVers_wiki, $db2 );

		foreach( $Final_Result2 as $id => &$v_array ) {
			if( array_key_exists( $language, $v_array["Unchanged"] ) ) {
				$search_id = $v_array["Unchanged"][$language];
				$exist = mysql_query( 'SELECT page_title FROM page WHERE page_id = "' . $search_id . '"' );
				$temp_title = mysql_fetch_object( $exist );
				$temp_title = $temp_title->page_title;
				$v_array[$language]["title"] = $temp_title;
			}
		}
		mysql_close( $db2 );
	}

	unset($ts_mycnf, $ts_pw);
	$Final_Result2['real_LangGroup'] = $LangGroup;
	return $Final_Result2;
	/** The resulting array contains for every Indentifier the information for
	 *  #the languageversion (title, wiki_id)
	 *  #the array of changed articles with title
	 *  #the array of unchanged articles with title  
	**/
}

// Additional function if reflang has no article and therefore no title
// In that case english (or french) is preferred.
// If no english article is present a random language is choosen
function search_other_article( $Entry, $reflang ) {
	foreach( $Entry as $k => $v ) {
		if( $k != "Changed" AND $k != "Unchanged" ) {
			$temp_Entry[$k] = $v;	
		}
	}
	
	$such_lang = "fr";
	if ($reflang != "en") {
		$such_lang = "en";
	}

	foreach( $temp_Entry as $Sprache => $subarray ) {
		if( $Sprache == $such_lang ) {
			$Final_Result["article"] = $subarray["title"];
			$Final_Result["lang"] = $Sprache;
		}
	}

	if( !isset( $Final_Result ) ) {
		$temp_lang = array_rand( $temp_Entry );
		$Final_Result["article"] = $temp_Entry[$temp_lang]["title"];
		$Final_Result["lang"] = $temp_lang;
	}

	return $Final_Result;
}
