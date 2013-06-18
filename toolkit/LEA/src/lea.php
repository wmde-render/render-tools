<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
require_once( 'inc/src/SVGGraph/SVGGraph.php' );
include( "inc/src/toolserver_sql_abfragen.inc" );
include( "inc/src/Languagecodes.inc" );
include( "inc/src/api_normalize_redirect.inc" );
?>
<div id="Ueberschrift">
	<div class="leaDescription">
		<h2><?php echo $leaLang["headline"]; ?></h2>
		<p id="Description"><?php echo $leaLang["description"]; ?></p>
		<h2 onclick="toggleDescription()" style="cursor: pointer;"><?php echo $leaLang["headline2"]; ?><img id="expandIcon" src="../img/expand-large-silver.png" style="width: 15px; height: 15px; padding-left: 10px;"></h2>
		<p id="Description2" class="displayNone">
			<?php echo $leaLang["description2"]; ?><br /><br />
			<img src="../img/lea-example-full.png" style="margin-left: auto; margin-right: auto; display: block;">
		</p>
	</div>
	<div class="formDiv" style="width: 580px; float: left;">
		<?php echo $leaLang["form_text"]; ?>
		<form action="" method="post">
			<span><?php echo $leaLang["form_title"]; ?></span>
			<input name="title" size="20" value="<?php echo isset( $_REQUEST["title"] ) ? htmlspecialchars( $_REQUEST["title"], ENT_QUOTES, "UTF-8" ) : ""; ?>" />
			<span><?php echo $leaLang["form_in"]; ?></span>
			<input name="lg" size="2" maxlength="16" value="<?php echo isset( $_REQUEST["lg"] ) ? htmlspecialchars( $_REQUEST["lg"], ENT_QUOTES, "UTF-8" ) : ""; ?>" />
			<span>.wikipedia.org</span>
			<input name="submit" type="submit" value="<?php echo $leaLang["form_button"]; ?>" />
		</form>
	</div>
</div>

<?php
if( isset( $_REQUEST["submit"] ) ) {
$dbLink = array();
$dbError = "";

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
	/*global $dbLink, $dbError;
	
	$ts_pwd = posix_getpwuid( posix_getuid() );
	$ts_mycnf = parse_ini_file( $ts_pwd['dir'] . "/.my.cnf" );

	for( $i = 1; $i < 8; $i ++ ) {
		$dbLink[$i] = @mysql_connect( "sql-s" . $i, $ts_mycnf['user'], $ts_mycnf['password'] );
		if (!$dbLink[$i]) {
			closeDbLinks();
			$dbError = "sql-s" . $i;
			return false;
		}
	}*/
	
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
	global $dbArray, $dbLink;
	
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
	global $dbArray, $dbLink;
	
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
	global $dbArray, $dbLink;
	
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

if ( $_REQUEST['title'] && !empty( $_REQUEST['title'] ) ) $reqTitle = normalizeTitle( $_REQUEST['title'] );
else $reqTitle = "Jerry_Siegel";
if ( $_REQUEST['lg'] && !empty( $_REQUEST['lg'] ) ) $reqLang = $_REQUEST['lg'];
else $reqLang = "de";

$languages = array();

$startTotal = getTime();

$tries = 0;
$maxTries = 10;
/*while( !createDatabaseLinks() && $tries < $maxTries) {
	sleep(1);
	$tries ++;
	
	if ($tries == $maxTries) {
		?>
	<div id="Errormessage" style="clear:both;">
		<span>
			<?php printf( $Error["dbError"], $dbError ); ?>
		</span>
	</div>
		<?php
		exit();
	}
}*/

// get page id
#mysql_select_db( $reqLang . "wiki_p", getDbLink($reqLang) );
$id = getPageId( $reqTitle, $reqLang );
if ($id) {
	// get links on requested page
	$requestedLinks = getPageLinks( $id, $reqLang );

	// get all language links
	$sql = "SELECT ll_lang, ll_title FROM langlinks WHERE ll_from = " . $id;
	$result = mysql_query( $sql, getDbLink($reqLang) );

	$langVersionCount = 0;
	// get all page links from other languages to determine the three link richest
	while ( $row = mysql_fetch_assoc( $result ) ) {
		$langVersionCount ++;
		$linkedLang = $row['ll_lang'];
		$linkedTitle = normalizeTitle( $row['ll_title'] );

		if ( !getDbLink( $linkedLang ) ) {
			continue;
		}
		$languages[$linkedLang] = array();

		#mysql_select_db( $linkedLang . "wiki_p", getDbLink($linkedLang) );
		$linkedId = getPageId( $linkedTitle, $linkedLang );
		if ( !$linkedId ) {
			continue;
		}

		#mysql_select_db( $linkedLang . "wiki_p", getDbLink($linkedLang) );
		$languages[$linkedLang] = getPageLinks( $linkedId, $linkedLang );
		$titles[$linkedLang] = $linkedTitle;
	}
	
	if ($langVersionCount > 0) {

		// sort by element count and cut off to have three remaining
		uasort( $languages, "sortByElementCount" );
		$languages = array_slice( $languages, -3 );

		// debug output: three language versions and number of page links
		$langResults = array();
		$langResults[$reqLang] = array();
		foreach ( $languages as $linkedLang => $linkedPage ) {
			$langResults[$linkedLang] = array();
		}


		$arrResult = array(
			'red' => array(),
			'yellow' => array(),
			'green' => array(),
		);

		$checkedPages = array();
		mysql_select_db( $reqLang . "wiki_p", getDbLink($reqLang) );
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
				$linkResult[$reqLang] = null;

				foreach ( array_keys( $languages ) as $key ) {
					$linkResult[$key] = null;
				}
				$linkResult[$language] = normalizeTitle( $link );

				while ( $row = mysql_fetch_assoc( $result ) ) {
					if ( $row['ll_lang'] === $reqLang ) {
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
						if ( in_array( $linkResult[$reqLang], $requestedLinks ) ) {
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

		// log request
		$asqmId = ( isset( $_SESSION['asqmId'] ) && !empty( $_SESSION['asqmId'] ) ) ? $_SESSION['asqmId'] : "";
		
		$userInfo = posix_getpwuid( posix_getuid() );
		$dbCred = parse_ini_file( $userInfo['dir'] . "/replica.my.cnf" );
		mysql_connect( 'tools-db', $dbCred['user'], $dbCred['password'] );
		mysql_select_db( $dbCred['user'] . '__request_logs' );

		$serializedResult = base64_encode( serialize( $arrResult ) );
		$sql = "INSERT INTO request_log ".
				"(asqm_id, title, lang, action_type, result, request_time) ".
				"VALUES ('" . $asqmId . "', '" . $reqTitle . "', '" . $reqLang . "', 'lea-usage', '" . $serializedResult . "', NOW())";
		mysql_query( $sql );
		mysql_close();

		// preparing output
		$intersection = count($arrResult['red']) + count($arrResult['yellow']) + count($arrResult['green']);
		$Chart_Label = str_replace( " ", "%20", $Legend["red"] ) .
			"*" . str_replace( " ", "%20", $Legend["yellow"] ) .
			"*" . str_replace( " ", "%20", $Legend["green"] );
?>
<div id="info">
	<span>
		<p>
			<?php printf( $Info["langVersions1"], $reqLang, $reqTitle, str_replace( "_", " ", $reqTitle ), $langVersionCount ); ?>
		</p>
		<table id="resultSummary">
			<tr class="underline">
				<td><?php echo $Info["requested_lang"] . " (" . $reqLang . ")"; ?></td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $requestedLinks ) ); ?></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php printf( $Info["langVersions2"], count( $languages ) ); ?>
				</td>
			</tr>

			<?php foreach ( $languages as $linkedLang => $linkedPage ): ?>
			<tr>
				<td>
					<a href="http://<?php echo $linkedLang; ?>.wikipedia.org/wiki/<?php echo $titles[$linkedLang]; ?>">
						<?php echo unnormalize($titles[$linkedLang]); ?>
					</a> 
					(<?php echo $linkedLang; ?>)
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $linkedPage ) ); ?></td>
			</tr>
			<?php endforeach; ?>
			<tr class="doubleline">
				<td><?php echo $Info['intersection']; ?></td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], $intersection ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="Legendenelement">
					<span style="border: 1px solid black; background: red;">&nbsp;&nbsp;</span>
					&nbsp;<?php echo $Legend["red"]; ?>
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $arrResult['red'] ) ); ?></td>
			</tr>
			<tr>
				<td class="Legendenelement">
					<span style="border: 1px solid black; background: yellow;">&nbsp;&nbsp;</span>
					&nbsp;<?php echo $Legend["yellow"]; ?>
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $arrResult['yellow'] ) ); ?></td>
			</tr>
			<tr>
				<td class="Legendenelement">
					<span style="border: 1px solid black; background: green;">&nbsp;&nbsp;</span>
					&nbsp;<?php echo $Legend["green"]; ?>
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $arrResult['green'] ) ); ?></td>
			</tr>
		</table>
		<p>
			<?php echo $analysisLink1; ?> 
			<a href="http://<?php echo $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"] . "?submit=1&title=" . htmlspecialchars( $_REQUEST['title'] ) . "&lg=" . htmlspecialchars( $reqLang ) . "&lang=" /*. $_SESSION['lang']*/; ?>">
				<?php echo $analysisLink2; ?>
			</a>
		</p>
	</span>
</div>
<div id="chart">
	<h3><?php echo $Charttitle; ?></h3>
	<embed src="./inc/src/piechart3pGET.php?labels=<?php echo $Chart_Label; ?>&values=<?php echo count($arrResult['red']) . "*" . count($arrResult['yellow']) . "*" . count($arrResult['green']); ?>"
		type="image/svg+xml" width="250" height="250" pluginspage="http://www.adobe.com/svg/viewer/install/" />
</div>

<div id="Ergebnis">
	<span>
		<table class="Leatable" border="0">
			<tr align="center" style="background: #0047AB; color:white;">
				<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;">
					<span title="<?php echo langcode_in_en( $reqLang ); ?>">
						<?php echo langcode_in_local( $reqLang ); ?>
					</span>
				</th>

<?php foreach ( array_keys( $languages ) as $key ): ?>
				<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;">
					<span title="<?php echo langcode_in_en( $key ); ?>">
						<?php echo langcode_in_local( $key ); ?>
					</span>
				</th>
<?php endforeach; ?>
	
			</tr>

	<?php foreach ( $arrResult as $color => $pages ): ?>
		<?php foreach ( $pages as $count => $links ): ?>
				<tr id="tabellenzeile">
			<?php foreach ( $links as $language => $title ): ?>
					<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background-color: <?php echo $language == $reqLang ? $color : "none"; ?>; text-align: center;">
				<?php if ( $language == $reqLang && $color == 'red' ): ?>
						-
				<?php else: ?>
						<a <?php echo ($color == 'green' && $language == $reqLang) ? "style=\"color: white;\"" : ""; ?> href="http://<?php echo $language; ?>.wikipedia.org/wiki/<?php echo removeSlashes($title); ?>" target="_blank">
							<?php echo unnormalize( $title ); ?>
						</a>
				<?php endif; ?>
					</td>
			<?php endforeach; ?>
				</tr>
		<?php endforeach; ?>
	<?php endforeach; ?>
			</table>
		</span>
	</div>
		<?php } else { ?>
		<div id="Errormessage" style="clear:both;">
			<span>
				<?php printf( $Error["NoTrans"], $reqTitle ); ?>
			</span>
		</div>
		<?php } ?>
	<?php } else { ?>
	<div id="Errormessage" style="clear:both;">
		<span>
			<?php printf( $Error["Notexists"], htmlspecialchars( $reqTitle ), $reqLang); ?>
		</span>
	</div>
	<?php } ?>
<?php } ?>
