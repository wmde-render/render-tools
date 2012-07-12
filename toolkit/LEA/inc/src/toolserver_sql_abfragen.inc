<?php
function artikel_id_abfragen( $artikeltitel ) {
	$artikeltitel = str_replace( " ", "_", $artikeltitel );
	$artikeltitel =  str_replace( '"', '\\"', $artikeltitel );
	$exist = mysql_query(
		'SELECT page_id FROM page WHERE page_title = "' . $artikeltitel .
		'" AND page_namespace = 0'
	);

	if ( !$exist ) {
		die( 'Artikel nicht existent: ' . mysql_error(). "\n" );
	}

	$artikel_id = mysql_fetch_object( $exist );
	$artikel_id = $artikel_id->page_id;
	return $artikel_id;
}

function artikel_titel_mit_id_abfragen( $id ) {
	$exist = mysql_query( 'SELECT page_title FROM page WHERE page_id = "' . $id . '"' );
	if ( !$exist ) {
		die( 'Artikel nicht existent: ' . mysql_error(). "\n" );
	}

	$artikel_titel = mysql_fetch_object( $exist );
	$artikel_titel = $artikel_titel->page_title;
	return $artikel_titel;
}

function abfragen_langlinks( $artikel_id, $Sprachversion ) {
	$result = mysql_query( 'SELECT * FROM langlinks WHERE ll_from = "' . $artikel_id . '"' );
	if ( !$result ) {
		die( 'DB FEHLER: ' . mysql_error(). "\n" );
	}

	while ( $row = mysql_fetch_object( $result ) ) {
		$erg[$row->ll_lang] = $row->ll_title;
	}
	
	if ( isset( $erg ) ) {
		return $erg;
	}

	return "0";
}

function abfragen_langlinks_fuer_mit( $artikel_id, array $betrachtete_Sprachen ) {
	foreach ( $betrachtete_Sprachen as $Sprache => $v ) {
		$result = mysql_query(
			'SELECT * FROM langlinks WHERE ll_from = "' . $artikel_id .
			'" AND ll_lang = "' . $Sprache . '"');

		if ( !$result ) {
			die( 'DB FEHLER: ' . mysql_error(). "\n" );
		}

		while ( $row = mysql_fetch_object( $result ) ) {
			$erg[$row->ll_lang] = $row->ll_title;
		}
	}

	if ( isset( $erg ) ) {
		return $erg;
	}

	return "0";				
}

function abfragen_links( $artikel_id, $Sprachversion ) {
	$result = mysql_query( 'SELECT * FROM pagelinks WHERE pl_from = "' . $artikel_id . '"' );
	if ( !$result ) {
		die( 'DB FEHLER: ' . mysql_error(). "\n" );
	}

	while ( $row = mysql_fetch_object( $result ) ) {
		$erg[] = $row->pl_title;
	}

	if ( isset( $erg ) ) {
		return $erg;
	}

	return "0";
}