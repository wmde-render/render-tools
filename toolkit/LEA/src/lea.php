<?php
/*
 * Name: lea.php
 * 
 * Description: shows the intersection of the links four wikipedia articles
 * 				and UI
 * 
 * Author: Anselm Metzger
 * 
 * includes: toolserver_sql_abfragen.inc : database query functions
 * 			 piechart3p.php : generates a piechart with SVGGraph
 * 			 Languagecodes.inc : translating Languagecodes
 * 
 *   
  Copyright (c) 2012, Wikimedia Deutschland (Anselm Metzger)
  All rights reserved.
 
  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:
      * Redistributions of source code must retain the above copyright
        notice, this list of conditions and the following disclaimer.
      * Redistributions in binary form must reproduce the above copyright
        notice, this list of conditions and the following disclaimer in the
        documentation and/or other materials provided with the distribution.
      * Neither the name of Wikimedia Deutschland nor the
        names of its contributors may be used to endorse or promote products
        derived from this software without specific prior written permission.
 
  THIS SOFTWARE IS PROVIDED BY WIKIMEDIA DEUTSCHLAND ''AS IS'' AND ANY
  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL WIKIMEDIA DEUTSCHLAND BE LIABLE FOR ANY
  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

  NOTE: This software is not released as a product. It was written primarily for
 Wikimedia Deutschland's own use, and is made public as is, in the hope it may
 be useful. Wikimedia Deutschland may at any time discontinue developing or
 supporting this software. There is no guarantee any new versions or even fixes
 for security issues will be released.
 */
require_once( 'inc/src/SVGGraph/SVGGraph.php' );
include( "inc/src/toolserver_sql_abfragen.inc" );
include( "inc/src/Languagecodes.inc" );
include( "inc/src/api_normalize_redirect.inc" );
?>
<div id="Ueberschrift">
	<h2><?php echo $leaLang["headline"]; ?></h2>
	<div class="leaDescription">
		<p id="Description"><?php echo $leaLang["description"]; ?></p>
		<p>
			<a href="info.php"><?php echo $leaLang["more_info"]; ?></a>
		</p>
		<div class="formDiv">
			<?php echo $leaLang["form_text"]; ?>
			<form action="" method="post">
				<span><?php echo $leaLang["form_title"]; ?></span>
				<input name="title" size="20" value="<?php echo isset( $_REQUEST["title"] ) ? htmlspecialchars( $_REQUEST["title"], ENT_QUOTES, "UTF-8" ) : ""; ?>" />
				<span><?php echo $leaLang["form_in"]; ?></span>
				<input name="lg" size="2" maxlength="5" value="<?php echo isset( $_REQUEST["lg"] ) ? htmlspecialchars( $_REQUEST["lg"], ENT_QUOTES, "UTF-8" ) : ""; ?>" />
				<span>.wikipedia.org</span>
				<input name="submit" type="submit" value="<?php echo $leaLang["form_button"]; ?>" />
			</form>
		</div>
	</div>
	<div class="leaDescription">
		<p class="image"><img src="/<?php echo $tsAccount; ?>/toolkit/img/lea-example-small.png" /></p>
	</div>
</div>

<?php
if( isset( $_REQUEST["submit"] ) ) {
	$Result_No_article = array();
	$Result_article_linked = array();
	$Result_not_linked = array();

	// Get $_REQUEST- parameters
	if( !$_REQUEST["title"] || empty( $_REQUEST["title"] ) ) {
		$articletitle = "Jerry_Siegel";
	} else {
		$articletitle = $_REQUEST["title"];
	}

	if( !$_REQUEST["lg"] || empty( $_REQUEST["lg"] ) ) {
		$LanguageVersion = "de";
	} else {
		$LanguageVersion = $_REQUEST["lg"];
	}

	$LanguageVersion_wiki = $LanguageVersion . "wiki_p";
	$LanguageVersion_wiki_db = $LanguageVersion . "wiki-p.rrdb.toolserver.org";

	// Open Database for the original article
	$ts_pwd = posix_getpwuid( posix_getuid() );
	$ts_mycnf = parse_ini_file( $ts_pwd['dir'] . "/.my.cnf" );
	$db = mysql_connect( $LanguageVersion_wiki_db, $ts_mycnf['user'], $ts_mycnf['password'] );

	if( !$db ) {
		die( 'Connection error (db1): ' . mysql_error() );
	}
	mysql_select_db( $LanguageVersion_wiki, $db );

	$new_title = api_normalize_redirect( $articletitle, $LanguageVersion );
	
	if( $new_title != NULL ) {
		$articletitle = str_replace( " ", "_", $new_title );
	}

	$article_id = artikel_id_abfragen( $articletitle );
	$orig_langlinks = abfragen_langlinks( $article_id, $LanguageVersion );

	if ( $article_id != 0 && $orig_langlinks != 0 ) {
		// Collect LangLinks and internal WikiLinks
		$orig_links = abfragen_links( $article_id, $LanguageVersion );
		$orig_links = array_flip( $orig_links );

		// Save result for LEA1 ( Intersection with all languages )
		$result_links_lea1 = $orig_links;
		foreach ( $result_links_lea1 as $k=>$v ) {
			$result_links_lea1[$k] = 0;
		}

		foreach ( $orig_links as $link => $value ) {
			$link_id_tmp = artikel_id_abfragen( $link );
			$orig_links[$link] = abfragen_langlinks( $link_id_tmp, $LanguageVersion );
		}
		mysql_close( $db );

		// Collect Links for all LanguageLinks
		foreach ( $orig_langlinks as $Language => $transtitle ) {
			$db2 = mysql_connect( $Language . "wiki-p.rrdb.toolserver.org", $ts_mycnf['user'], $ts_mycnf['password'] );
			if ( !$db2 ) {
				die( 'Connection error (db2 - ' . $Language . '): ' . mysql_error() );
			}

			$test = mysql_select_db( $Language . "wiki_p", $db2 );
			if ( !$test ) {
				mysql_close( $db2 );
				continue;
			}

			$langlink_id = artikel_id_abfragen( $transtitle );
			$link_list_key_lang[$Language] = abfragen_links( $langlink_id, $Language );
			mysql_close( $db2 );
		}

		foreach ( $link_list_key_lang as $k => $v ) {
			if ( is_array( $v ) ) {
				$link_list_key_lang[$k] = array_flip( $v );
			}
		}


		//LEA1 ( Intersection with of the original wikilinks with all language versions )
		//LEA1 functionality is not used at the moment
		foreach ( $orig_links as $link => $link_trans_array ) {
			if ( !$link_trans_array == 0 ) {
				foreach ( $link_trans_array as $Language => $title ) {
					if ( array_key_exists( $Language, $link_list_key_lang ) ) {
						if ( is_array( $link_list_key_lang[$Language]) && 
								array_key_exists( str_replace( " ", "_", $title ), $link_list_key_lang[$Language] ) ) {
							$result_links_lea1[$link] ++;
						}
					}
				}
			}
		}
		arsort( $result_links_lea1 );

		//LEA1 get the top 5 Links over all Languages
		$result_top5_lea1 = NULL;
		$i = 0;
		foreach ( $result_links_lea1 as $link => $Anzahl ) {	
			if ( $Anzahl == 0 ) {
				break;
			}
			
			$result_top5_lea1[$link] = $Anzahl;	
			$i++;
			if ( $i >= 5 ) {
				break;
			}
		}
		// End LEA1

		// Sort language versions by link count
		foreach ( $link_list_key_lang as $Language => $link_array ) {
			$greatest_trans[$Language] = count( $link_array );
		}
		arsort( $greatest_trans );

		// Find the three or less biggest versions
		$i = 0;
		foreach ( $greatest_trans as $Language => $link_Anzahl ) {
			$biggest_lang[$i] = $Language;
			$i++; 
			if ( $i > 2 ) {
				break;
			}
		}

		$LangCount = count( $biggest_lang );
		$noticed_languages = array_flip( $biggest_lang );
		$noticed_languages[$LanguageVersion] = "3";

		$RefLanguage = "";
		$RefLanguage = $biggest_lang[$LangCount-1];
		$db3 = mysql_connect( $RefLanguage . "wiki-p.rrdb.toolserver.org", $ts_mycnf['user'], $ts_mycnf['password'] );
		if ( !$db3 ) {
			die( 'Connection Error (db3): ' . mysql_error() );
		}

		$test = mysql_select_db( $RefLanguage . "wiki_p", $db3 );
		if ( !$test ) {
			mysql_close( $db3 );
			continue;
		}

		$transtitle = $orig_langlinks[$RefLanguage];
		$trans_id = artikel_id_abfragen( $transtitle );
		$trans_link_liste = abfragen_links( $trans_id );
		$trans_link_liste = array_flip( $trans_link_liste );

		foreach ( $trans_link_liste as $link => $value ) {
			$link_id_tmp = artikel_id_abfragen( $link );
			$greatest_link_liste_mit_translation[$link] = abfragen_langlinks_fuer_mit( $link_id_tmp, $noticed_languages );
		}
		mysql_close( $db3 );
		unset( $ts_mycnf, $ts_pwd );

		// The Intersection and Sorting 
		foreach ( $greatest_link_liste_mit_translation as $link => $translink_array ) {
			if ( !$translink_array == 0 ) {
				$ergebnis_linkliste[$link] = 0;
				foreach( $translink_array as $Language => $link_trans ) {
					if ( $Language == $LanguageVersion ) {
						if( array_key_exists( str_replace( " ", "_", $link_trans ), $orig_links ) ) {
							$ergebnis_linkliste[$link] = $ergebnis_linkliste[$link] + 10;
							$Used_Art[$link] = $link_trans;
						} else {
							$ergebnis_linkliste[$link] = $ergebnis_linkliste[$link] + 100;
							$Existing_Art[$link] = $link_trans;
						}
					} else {
						if ( array_key_exists( str_replace( " ", "_", $link_trans ), $link_list_key_lang[$Language] ) ) {
							$ergebnis_linkliste[$link] ++;
						}
					}
				}
			}
		}

		//Only three kinds of link-classes are relevant
		foreach ( $ergebnis_linkliste as $link => $Code ) {
			switch ( $Code ) {
				case $LangCount - 1:
					$Result_No_article[] = $link;
					break;
				case $LangCount + 9:
					$Result_article_linked[] = $link;
					break;		
				case $LangCount + 99:
					$Result_not_linked[] = $link;
			}
		}

		// OUTPUT
		$Chart_Label = str_replace( " ", "_", $Legend["red"] ) .
			"*" . str_replace( " ", "_", $Legend["yellow"] ) .
			"*" . str_replace( " ", "_", $Legend["green"] );
		$Result_Link_Classes = count( $Result_No_article ) . 
			"*" . count( $Result_not_linked ) .
			"*" . count( $Result_article_linked );
		$totalIntersection = count( $Result_No_article ) + count( $Result_not_linked ) + count( $Result_article_linked );
?>
<div id="info">
	<span>
		<p>
			<?php printf( $Info["langVersions1"], $LanguageVersion, $articletitle, str_replace( "_", " ", $articletitle ), count( $orig_langlinks ) ); ?>
		</p>
		<table id="resultSummary">
			<tr class="underline">
				<td><?php echo $Info["requested_lang"] . " (" . $LanguageVersion . ")"; ?></td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $orig_links ) ); ?></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php printf( $Info["langVersions2"], $LangCount ); ?>
				</td>
			</tr>
			<?php foreach ( $biggest_lang as $k => $v ): ?>
			<tr>
				<td>
					<a href="http://<?php echo $v; ?>.wikipedia.org/wiki/<?php echo $orig_langlinks[$v]; ?>">
						<?php echo $orig_langlinks[$v]; ?>
					</a> 
					(<?php echo $v; ?>)
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], $greatest_trans[$v] ); ?></td>
			</tr>
			<?php endforeach; ?>
			<tr class="doubleline">
				<td><?php echo $Info['intersection']; ?></td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], $totalIntersection ); ?></td>
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
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $Result_No_article ) ); ?></td>
			</tr>
			<tr>
				<td class="Legendenelement">
					<span style="border: 1px solid black; background: yellow;">&nbsp;&nbsp;</span>
					&nbsp;<?php echo $Legend["yellow"]; ?>
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $Result_not_linked ) ); ?></td>
			</tr>
			<tr>
				<td class="Legendenelement">
					<span style="border: 1px solid black; background: green;">&nbsp;&nbsp;</span>
					&nbsp;<?php echo $Legend["green"]; ?>
				</td>
				<td class="align-right"><?php printf( $Info["lang_link_count"], count( $Result_article_linked ) ); ?></td>
			</tr>
		</table>
		<p>
			<?php echo $analysisLink1; ?> 
			<a href="http://<?php echo $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"] . "?submit=1&title=" . htmlspecialchars( $_REQUEST['title'] ) . "&lg=" . htmlspecialchars( $_REQUEST['lg'] ) . "&lang=" . $_SESSION['lang']; ?>">
				<?php echo $analysisLink2; ?>
			</a>
		</p>
	</span>
</div>
<div id="chart">
	<h3><?php echo $Charttitle; ?></h3>
	<embed src="./inc/src/piechart3pGET.php?labels=<?php echo $Chart_Label; ?>&values=<?php echo $Result_Link_Classes; ?>"
		type="image/svg+xml" width="250" height="250" pluginspage="http://www.adobe.com/svg/viewer/install/" />
</div>

<div id="Ergebnis">
	<span>
		<table class="Leatable" border="0">
			<tr align="center" style="background: #0047AB; color:white;">
				<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;">
					<span title="<?php echo langcode_in_en($LanguageVersion); ?>">
						<?php echo langcode_in_local( $LanguageVersion ); ?>
					</span>
				</th>
				<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;">
					<span title="<?php echo langcode_in_en( $RefLanguage ); ?>">
						<?php echo langcode_in_local( $RefLanguage ); ?>
					</span>
				</th>

<?php foreach ( $biggest_lang as $k => $v ): ?>
	<?php if ( $v != $RefLanguage ): ?>
				<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;">
					<span title="<?php echo langcode_in_en( $v ); ?>">
						<?php echo langcode_in_local( $v ); ?>
					</span>
				</th>
	<?php endif; ?>
<?php endforeach; ?>
	
			</tr>

<?php if( isset( $Result_No_article ) ): ?>
	<?php foreach ( $Result_No_article as $k => $v ): ?>
			<tr id="tabellenzeile">
				<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: red; text-align: center;">
					<a title="<?php echo $Legend["red"]; ?>">-</a>
				</td>
				<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;">
					<a href="http://<?php echo $RefLanguage;?>.wikipedia.org/wiki/<?php echo $v; ?>" target="_blank">
						<?php echo str_replace( "_", " ", $v ); ?>
					</a>
				</td>
				<?php foreach ( $biggest_lang as $key => $value ): ?>
					<?php if ( $value != $RefLanguage ): ?>
						<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;">
							<a href="http://<?php echo $value; ?>.wikipedia.org/wiki/<?php echo str_replace( " ", "_", $greatest_link_liste_mit_translation[$v][$value] ); ?>" target="_blank">
								<?php echo $greatest_link_liste_mit_translation[$v][$value]; ?>
							</a>
						</td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		<?php endif; ?>


		<?php if ( isset( $Result_not_linked ) ): ?>
			<?php foreach ( $Result_not_linked as $k => $v ): ?>
				<tr id="tabellenzeile">
					<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: yellow; text-align: center;">
						<a href="http://<?php echo $LanguageVersion; ?>.wikipedia.org/wiki/<?php echo $Existing_Art[$v]; ?>" target="_blank">
							<?php echo $Existing_Art[$v]; ?>
						</a>
					</td>
					<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;">
						<a href="http://<?php echo $RefLanguage; ?>.wikipedia.org/wiki/<?php echo $v; ?>" target="_blank">
							<?php str_replace("_", " ", $v); ?>
						</a>
					</td>
				<?php foreach ( $biggest_lang as $key => $value ): ?>
					<?php if ( $value != $RefLanguage ): ?>
						<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;">
							<a href="http://<?php echo $value; ?>.wikipedia.org/wiki/<?php echo str_replace( " ", "_", $greatest_link_liste_mit_translation[$v][$value] ); ?>" target="_blank">
								<?php echo $greatest_link_liste_mit_translation[$v][$value]; ?>
							</a>
						</td>
					<?php endif; ?>
				<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( isset( $Result_article_linked ) ): ?>
			<?php foreach ( $Result_article_linked as $k => $v ): ?>
				<tr id="tabellenzeile">
					<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: green; text-align: center;">
						<a style="color: white;" href="http://<?php echo $LanguageVersion; ?>.wikipedia.org/wiki/<?php echo $Used_Art[$v]; ?>" target="_blank">
							<?php echo $Used_Art[$v]; ?>
						</a>
					</td>
					<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;">
						<a href="http://<?php echo $RefLanguage; ?>.wikipedia.org/wiki/<?php echo $v; ?>" target="_blank">
							<?php echo str_replace( "_", " ", $v ); ?>
						</a>
					</td>
				<?php foreach ( $biggest_lang as $key => $value ): ?>
					<?php if ( $value != $RefLanguage ): ?>
						<td style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;">
							<a href="http://<?php echo $value; ?>.wikipedia.org/wiki/<?php echo str_replace( " ", "_", $greatest_link_liste_mit_translation[$v][$value] ); ?>" target="_blank">
								<?php echo $greatest_link_liste_mit_translation[$v][$value]; ?>
							</a>
						</td>
					<?php endif; ?>
				<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
			</table>
		</span>
	</div>
<?php } else { ?>
	<?php if ($article_id == 0): ?>
	<div id="Errormessage">
		<span>
			<?php printf( $Error["Notexists"], htmlspecialchars( $articletitle ), $LanguageVersion); ?>
		</span>
	</div>
	<?php elseif ($orig_langlinks == 0): ?>
		<div id="Errormessage">
			<span>
				<?php echo printf( $Error["NoTrans"], $articletitle ); ?>
			</span>
		</div>
		<?php endif; ?>
	<?php } ?>
<?php } ?>