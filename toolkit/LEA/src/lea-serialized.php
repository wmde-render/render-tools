<?php
set_time_limit(0);
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

include( "../inc/src/toolserver_sql_abfragen.inc" );
include( "../inc/src/Languagecodes.inc" );
include( "../inc/src/api_normalize_redirect.inc" );

$paramTitle = $_GET['title'];
$paramLang = $_GET['lang'];
$languages = array();
$dbLink = array();

$arrParamLanguages = explode( "|", $_GET['languages'] );
foreach( $arrParamLanguages as $pLang ) {
	$languages[$pLang] = array();
}
#$languages = explode("|", $_GET['languages']);
unset($languages[$paramLang]);

function getDbLink( $lang ) {
	global $dbLink;
	$ts_pwd = posix_getpwuid( posix_getuid() );
	$ts_mycnf = parse_ini_file( $ts_pwd['dir'] . "/replica.my.cnf" );

	if ( !array_key_exists( $lang, $dbLink ) ) {
		$dbLink[$lang] = @mysql_connect( $lang . "wiki.labsdb", $ts_mycnf['user'], $ts_mycnf['password'] );
		@mysql_select_db( $lang . "wiki_p", $dbLink[$lang] );
	}
	return $dbLink[$lang];
}

function createDatabaseLinks() {
	return true;
}

function closeDbLinks() {
	global $dbLink;
	
	foreach ( $dbLink as $link ) {
		if ( $link ) {
			mysql_close( $link );
		}
	}
}

function getTime() {
	$a = explode( ' ',microtime() );
	return ( double ) $a[0] + $a[1];
}

function sortByElementCount( $a, $b ) {
	if( count( $a ) > count( $b ) ) return 1;
	return -1;
}

function getPageId( &$title, $lang ) {
	global $dbLink;
	
	$sql = "SELECT page_id, page_is_redirect FROM page WHERE page_namespace = 0 AND page_title = '" . mysql_escape_string( $title ) . "'";
	$result = mysql_query( $sql, getDbLink($lang) );
	
	if ( $result ) {
		$row = mysql_fetch_assoc( $result );
		if ( $row['page_is_redirect'] > 0 ) {
			$title = resolveRedirection( $title, getDbLink($lang) );
			$pageId = getPageId( $title, $lang );
		} else {
			$pageId = $row['page_id'];
		}
		
		return $pageId;
	}
	
	return false;
}

function getPageLinks( $pageId, $lang ) {
	global $dbLink;
	
	$sql = "SELECT pl_title, page_id
		FROM pagelinks
		LEFT JOIN page
		ON page.page_title = pagelinks.pl_title
		AND page.page_namespace = 0
		WHERE pl_from = " . $pageId . " 
		AND pl_namespace = 0";
	$result = mysql_query( $sql, getDbLink($lang) );

	$links = array();
	while ( $row = mysql_fetch_assoc( $result ) ) {
		if ( $row['page_id'] !== null ) {
			$links[] = $row['pl_title'];
		}
	}
	
	return $links;
}

function getPageLinksByTitle( $title, $lang ) {
	global $dbLink;
	
	$sql = "SELECT p2.page_title FROM page AS p1 
		LEFT JOIN pagelinks AS pl ON pl.pl_from = p1.page_id 
		LEFT JOIN page AS p2 ON pl.pl_title = p2.page_title AND p2.page_namespace = 0
		WHERE p1.page_title = '".$title."' AND p1.page_namespace = 0";
	$result = mysql_query( $sql, getDbLink($lang) );

	$links = array();
	while ( $row = mysql_fetch_assoc( $result ) ) {
		$links[] = $row['page_title'];
	}
	
	return $links;
}

function resolveRedirection( $title, $dbLink ) {
	
	$result = mysql_query( "SELECT p.page_id, r.rd_from, r.rd_title
		FROM page AS p
		LEFT JOIN redirect AS r
		ON p.page_id = r.rd_from
		WHERE p.page_title = '" . mysql_escape_string( $title ) . "' 
		AND p.page_namespace = 0", $dbLink );

	if ( $result ) {
		$rs = mysql_fetch_object( $result );
		if ( $rs->rd_from !== null ) {
			$title = resolveRedirection( $rs->rd_title, $dbLink );
		}
	}
	
	return $title;
}

function normalizeTitle( $title ) {
	$title = ucfirst( $title );
	$title = str_replace( " ", "_", $title );
	$title = str_replace( "'", "\'", $title );
	return $title;
}

function unnormalize( $title ) {
	$title = ucfirst( $title );
	$title = str_replace( "_", " ", $title );
	$title = str_replace( "\'", "'", $title );
	return $title;
}

function removeSlashes( $title ) {
	$title = str_replace( "\'", "'", $title );
	return $title;
}

$dbLink = array();
$dbError = "";

$startTotal = getTime();

$tries = 0;
$maxTries = 10;

// get page id
$id = getPageId( $paramTitle, $paramLang );
if ($id) {
	// get links on requested page
	$requestedLinks = getPageLinks( $id, $paramLang );
	$langVersionCount = count( $languages );

	// get all language links
	$sql = "SELECT ll_lang, ll_title FROM langlinks WHERE ll_from = " . $id;
	$result = mysql_query( $sql, getDbLink($paramLang) );

	while ( $row = mysql_fetch_assoc( $result ) ) {
		$langVersionCount ++;
		$linkedLang = $row['ll_lang'];
		if ( !array_key_exists( $linkedLang, $languages ) ) continue;
		$linkedTitle = normalizeTitle( $row['ll_title'] );
		if ( !getDbLink( $linkedLang ) ) {
			continue;
		}
		$languages[$linkedLang] = array();
		$linkedId = getPageId( $linkedTitle, $linkedLang );
		if ( !$linkedId ) {
			continue;
		}
		$languages[$linkedLang] = getPageLinks( $linkedId, $linkedLang );
		$titles[$linkedLang] = $linkedTitle;
	}

	// debug output: three language versions and number of page links
	$langResults = array();
	$langResults[$paramLang] = array();
	foreach ( $languages as $linkedLang => $linkedPage ) {
		$langResults[$linkedLang] = array();
	}

	$arrResult = array(
		'red' => array(),
		'yellow' => array(),
		'green' => array(),
	);

	$checkedPages = array();
	mysql_select_db( $paramLang . "wiki_p", getDbLink($paramLang) );
	foreach( $languages as $language => $links ) {
		mysql_select_db( $language . "wiki_p", getDbLink($language) );
		foreach( $links as $link ) {
			$language = str_replace( "-", "_", $language );

			$linkId = getPageId( $link, $language );

			if ( in_array( $linkId, $checkedPages ) ) continue;
			$checkedPages[] = $linkId;

			$sql = "SELECT * FROM langlinks WHERE ll_from = " . $linkId . " AND ll_lang IN ('" . implode( "','", array_keys( $langResults ) ) . "')";
			$result = mysql_query( $sql, getDbLink($language) );

			$reqLangExists = false;
			$linkResult = array();
			$linkResult[$paramLang] = null;

			foreach ( array_keys( $languages ) as $key ) {
				$linkResult[$key] = null;
			}
			$linkResult[$language] = normalizeTitle( $link );

			while ( $row = mysql_fetch_assoc( $result ) ) {
				if ( $row['ll_lang'] === $paramLang ) {
					$linkResult[$row['ll_lang']] = normalizeTitle( $row['ll_title'] );

					$reqLangExists = true;
				} elseif ( array_key_exists( $row['ll_lang'], $langResults ) ) {
					if ( in_array( str_replace( " ", "_", $row['ll_title'] ), $languages[$row['ll_lang']] ) ) {
						$linkResult[$row['ll_lang']] = normalizeTitle( $row['ll_title'] );
					}
				}
			}

			$missingLanguages = count( $languages ) + 1 - count( array_filter( $linkResult ) );
			if ( $missingLanguages < 2 ) {
				if ( !$reqLangExists ) {
					$arrResult['red'][] = $linkResult;
				} elseif ( $missingLanguages == 0 ) {
					if ( in_array( $linkResult[$paramLang], $requestedLinks ) ) {
						$arrResult['green'][] = $linkResult;
					} else {
						$arrResult['yellow'][] = $linkResult;
					}

				}
			}
		}

		break;
	}

	// close all database connections
	closeDbLinks();

	echo serialize($arrResult);
}

