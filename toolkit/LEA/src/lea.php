
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
 
 * 
 * 
 */ 

require_once('inc/src/SVGGraph/SVGGraph.php');
include("inc/src/toolserver_sql_abfragen.inc");
include("inc/src/Languagecodes.inc");
include("inc/src/api_normalize_redirect.inc");

?>

<body>

<div id="Ueberschrift">
	<div id="Introduction">
	<h2><?php echo $Headline; ?></h2>
	<p id="Description"><?php echo $Description; ?></p>
	<p><a href="info.php"><?php echo $MoreInfo; ?></a></p>
	</div>

 <FORM ACTION="" METHOD="GET">
	 <p><?php echo $FormText; ?></p>
  <div id="Eingabe">
	  <span><?php echo $FormTitle; ?></span>
  <INPUT NAME="title" SIZE=30 MAXLENGTH=60 value="<?php if (isset($_GET["title"])) {echo htmlentities($_GET["title"]);} ?>" >
	  <span><?php echo $FormIn; ?></span>
  <INPUT NAME="lg" SIZE=5 MAXLENGTH=10 value="<?php if (isset($_GET["lg"])) {echo htmlentities($_GET["lg"]);} else {echo htmlentities($lang);}?>">
		<span>.wikipedia.org</span>
	<INPUT name="submit" TYPE="SUBMIT"  VALUE="<?php echo $FormButton; ?>" >
 </FORM>
</div>
</div>


<div  style="width: 98%; padding: 1em; clear:both;"></div>

<?php



if(isset($_GET["submit"]))
{
// Get $_GET- parameters	

if(!$_GET["title"] || empty($_GET["title"])){
$articletitle = "Jerry_Siegel";

} else {
$articletitle = $_GET["title"];	
}

if(!$_GET["lg"] || empty($_GET["lg"])){
$LanguageVersion = "de";

} else {
$LanguageVersion = $_GET["lg"];	
}


$LanguageVersion_wiki = $LanguageVersion . "wiki_p";
$LanguageVersion_wiki_db = $LanguageVersion . "wiki-p.rrdb.toolserver.org";

// Open Database for the original article
$ts_pwd = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pwd['dir'] . "/.my.cnf");
$db = mysql_connect($LanguageVersion_wiki_db, $ts_mycnf['user'], $ts_mycnf['password']);

if (!$db) {
    die('Connection error (db1): ' . mysql_error());
}


mysql_select_db($LanguageVersion_wiki, $db);


$new_title = api_normalize_redirect($articletitle, $LanguageVersion);
	
	echo "<!-- Title:".$new_title."-->";
	
	if ($new_title != NULL) { 
		$articletitle = str_replace(" ","_",$new_title);	
}



$article_id = artikel_id_abfragen($articletitle);	

$Disclaim = "<div id=\"Disclaimer\" style=\"clear:both\">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Anselm Metzger)</p></span>
</div>";


// Errormessages for non-existing or not translated articles

if ($article_id == 0) {
	echo "<div id=\"Errormessage\"><span>";
	printf($Error["Notexists"], htmlentities($articletitle), $LanguageVersion);
	echo "</span></div>".$Disclaim;
	break;
}

$orig_langlinks = abfragen_langlinks($article_id, $LanguageVersion);

if ($orig_langlinks == 0) {
	echo "<div id=\"Errormessage\"><span>";
	printf($Error["NoTrans"], $articletitle);
	echo "</span></div>".$Disclaim;
	break;
}
	
// Collect LangLinks and internal WikiLinks

$orig_links = abfragen_links($article_id, $LanguageVersion);

$orig_links = array_flip($orig_links);


// Save result for LEA1 ( Intersection with all languages )
$result_links_lea1 = $orig_links;

foreach ( $result_links_lea1 as $k=>$v){
	$result_links_lea1[$k] = 0;
}

foreach ($orig_links as $link => $value){
	$link_id_tmp = artikel_id_abfragen($link);
	 $orig_links[$link] = abfragen_langlinks($link_id_tmp, $LanguageVersion);	
}


mysql_close($db);




// Collect Links for all LanguageLinks
foreach ($orig_langlinks as $Language => $transtitle){
	
	$db2 = mysql_connect($Language . "wiki-p.rrdb.toolserver.org", $ts_mycnf['user'], $ts_mycnf['password']);
	
	if (!$db2) {
		die('Connection error (db2): ' . mysql_error());
	}
	
	
	$test = mysql_select_db($Language . "wiki_p", $db2);
	
	if (!$test) {
		mysql_close($db2);
		continue;
		}
		

	$langlink_id = artikel_id_abfragen($transtitle);	

	$link_list_key_lang[$Language] = abfragen_links($langlink_id,$Language);
	
	mysql_close($db2);
}



foreach ($link_list_key_lang as $k => $v){
	$link_list_key_lang[$k] = array_flip($v);
}


//LEA1 ( Intersection with of the original wikilinks with all language versions )
//LEA1 functionality is not used at the moment

foreach ($orig_links as $link => $link_trans_array){
	
	if (!$link_trans_array == 0){
		foreach ($link_trans_array as $Language => $title){
		
			if (array_key_exists($Language, $link_list_key_lang)){
		
				if (array_key_exists(str_replace(" ", "_", $title), $link_list_key_lang[$Language])){
					$result_links_lea1[$link] ++;
			
				}
			}
	
		}
	
	}
	
}	
arsort($result_links_lea1);

//LEA1 get the top 5 Links over all Languages
$result_top5_lea1 = NULL;
$i = 0;
foreach ($result_links_lea1 as $link => $Anzahl)
{	
	if ($Anzahl == 0) break;
	$result_top5_lea1[$link] = $Anzahl;	
	$i++;
	if ($i >= 5) break;
}

echo "<!-- The TOP5 of the links in this article intersectet with all language versions\n ";
print_r($result_top5_lea1);
echo "-->";


// End LEA1

// Sort language versions by link count
foreach ($link_list_key_lang as $Language => $link_array ){
	$greatest_trans[$Language] = count($link_array);
}
	
arsort($greatest_trans);


// Find the three or less biggest versions
$i = 0;
foreach ($greatest_trans as $Language => $link_Anzahl){
		$biggest_lang[$i] = $Language;
		$i++; 
		if ($i > 2) break;
}

$LangCount = count($biggest_lang);

$noticed_languages = array_flip($biggest_lang);
$noticed_languages[$LanguageVersion] = "3";



$RefLanguage = "";

$RefLanguage = $biggest_lang[$LangCount-1];
	
	$db3 = mysql_connect($RefLanguage . "wiki-p.rrdb.toolserver.org", $ts_mycnf['user'], $ts_mycnf['password']);
	
	if (!$db3) {
		die('Connection Error (db3): ' . mysql_error());
	}
	
	
	$test = mysql_select_db($RefLanguage . "wiki_p", $db3);

	if (!$test) {
		mysql_close($db3);
		continue;
		}

	
	$transtitle = $orig_langlinks[$RefLanguage];

	$trans_id = artikel_id_abfragen($transtitle);

	$trans_link_liste = abfragen_links($trans_id);
	$trans_link_liste = array_flip($trans_link_liste);
	
	
foreach ($trans_link_liste as $link => $value){
	$link_id_tmp = artikel_id_abfragen($link);
	 $greatest_link_liste_mit_translation[$link] = abfragen_langlinks_fuer_mit($link_id_tmp, $noticed_languages);	
}

	
	mysql_close($db3);



unset($ts_mycnf, $ts_pwd);

// The Intersection and Sorting 

foreach ($greatest_link_liste_mit_translation as $link => $translink_array){
	if (!$translink_array == 0){
		$ergebnis_linkliste[$link] = 0;
		foreach($translink_array as $Language => $link_trans){
			if ($Language == $LanguageVersion){
				if(array_key_exists(str_replace(" ", "_", $link_trans), $orig_links)){
					$ergebnis_linkliste[$link] = $ergebnis_linkliste[$link]+10;
					$Used_Art[$link] = $link_trans;
					}	
				else {
					$ergebnis_linkliste[$link] = $ergebnis_linkliste[$link]+100;	
					$Existing_Art[$link] = $link_trans;
			}
			}
			else {
			if (array_key_exists(str_replace(" ", "_", $link_trans), $link_list_key_lang[$Language]))
				$ergebnis_linkliste[$link]++;
			
			}
			}
	
	}
}

//Only three kinds of link-classes are relevant

foreach ($ergebnis_linkliste as $link => $Code){
	switch ($Code) {
		case $LangCount-1: 
			$Result_No_article[] = $link;
			break;
		case $LangCount+9: 
			$Result_article_linked[] = $link;
			break;		
		case $LangCount+99:
			$Result_Not_Linked[] = $link;	
		}	
	 
}
/*
$Result_Link_Classes[] =  count($Result_No_article);
$Result_Link_Classes[] =  count($Result_Not_Linked);
$Result_Link_Classes[] =  count($Result_article_linked);
*/




// OUTPUT

echo "<div id=\"chart\" >"; 
echo "<h3 >".$Charttitle."</h3>";
$Chart_Label = str_replace(" ","_",$Legend["red"])."*".str_replace(" ","_",$Legend["yellow"])."*".str_replace(" ","_",$Legend["green"]);
$Result_Link_Classes = count($Result_No_article)."*".count($Result_Not_Linked)."*".count($Result_article_linked);
echo "<embed src=\"./inc/src/piechart3pGET.php?labels=".$Chart_Label."&values=".$Result_Link_Classes."\" type=\"image/svg+xml\" width=\"250\" height=\"250\" pluginspage=\"http://www.adobe.com/svg/viewer/install/\" />";
/*
$Chart_Label = array ($Legend["red"], $Legend["yellow"], $Legend["green"]);
make_piechart($Result_Link_Classes, $Chart_Label);
*/
echo "</span>";
echo "</div>";

echo "<div id=\"info\" >";

echo "<span>";
echo "<p>";
printf($Info["Introduction"], $LanguageVersion, $articletitle, str_replace("_"," ",$articletitle), count($orig_langlinks), $LangCount);
echo "</p><ul>";
foreach ($biggest_lang as $k => $v){
	echo "<li>";
	printf($Info["LIlanguages"] , $v, $orig_langlinks[$v], $orig_langlinks[$v], $v, $greatest_trans[$v] );
	echo "</li>";
	}	
	echo "<li>";
	printf($Info["LIsourcelang"] , $LanguageVersion, count($orig_links) );
	echo "</li>";	
echo "</ul></p></span>";

echo "</div>";

echo "<div id=\"Legende\"><span class=\"Legendenelement\"><span style=\"border: 1px solid black; background: red;\">&nbsp;&nbsp;</span>&nbsp;".$Legend["red"]."</span><span class=\"Legendenelement\"><span style=\"border: 1px solid black; background: yellow;\">&nbsp;&nbsp;</span>&nbsp;".$Legend["yellow"]."</span><span class=\"Legendenelement\"><span style=\"border: 1px solid black; background: green;\">&nbsp;&nbsp;</span>&nbsp;".$Legend["green"]."</span></div>";
echo "<div id=\"Ergebnis\"><span>";

echo "<table class=\"Leatable\" border=\"0\"  >";
echo "\n<tr  align=\"center\"  style=\"background: #0047AB; color:white; \">";

echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\"><span title=\"".langcode_in_en($LanguageVersion)."\">".langcode_in_local($LanguageVersion)."</span></th>";

   echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\"><span title=\"".langcode_in_en($RefLanguage)."\">".langcode_in_local($RefLanguage)."</span></th>";

foreach ($biggest_lang as $k => $v){
	if ($v != $RefLanguage)
	echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\"><span title=\"".langcode_in_en($v)."\">".langcode_in_local($v)."</span></th>";	
	
	}
	
echo "</tr>";


if (isset($Result_No_article)){
foreach ($Result_No_article as $k => $v){ 
	echo "\n<tr id=\"tabellenzeile\">";
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: red; text-align: center;\"><a title=\"".$Legend["red"]."\">-</a></td>";
	
	
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$RefLanguage.".wikipedia.org/wiki/".$v."\" target=\"_blank\">".str_replace("_", " ", $v)."</a></td>";
foreach ($biggest_lang as $key => $value){
	if ($value != $RefLanguage){
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$value.".wikipedia.org/wiki/".str_replace(" ","_",$greatest_link_liste_mit_translation[$v][$value])."\" target=\"_blank\">".$greatest_link_liste_mit_translation[$v][$value]."</a></td>";	
		}
	}

	echo "</tr>";
	}
}


if (isset($Result_Not_Linked)){

foreach ($Result_Not_Linked as $k => $v){
echo "\n<tr id=\"tabellenzeile\">";
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: yellow; text-align: center;\"><a href=\"http://".$LanguageVersion.".wikipedia.org/wiki/".$Existing_Art[$v]."\" target=\"_blank\">".$Existing_Art[$v]."</a></td>";
	
	
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$RefLanguage.".wikipedia.org/wiki/".$v."\" target=\"_blank\">".str_replace("_", " ", $v)."</a></td>";
	
	foreach ($biggest_lang as $key => $value){
	if ($value != $RefLanguage){
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$value.".wikipedia.org/wiki/".str_replace(" ","_",$greatest_link_liste_mit_translation[$v][$value])."\" target=\"_blank\">".$greatest_link_liste_mit_translation[$v][$value]."</a></td>";	
		
	}
	}
	
echo "</tr>";
}
}

if (isset($Result_article_linked)){

foreach ($Result_article_linked as $k => $v){
echo "\n<tr id=\"tabellenzeile\">";
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: green; text-align: center;\"><a style=\"color: white;\" href=\"http://".$LanguageVersion.".wikipedia.org/wiki/".$Used_Art[$v]."\" target=\"_blank\">".$Used_Art[$v]."</a></td>";
	
	
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$RefLanguage.".wikipedia.org/wiki/".$v."\" target=\"_blank\">".str_replace("_", " ", $v)."</a></td>";
	
	foreach ($biggest_lang as $key => $value){
	if ($value != $RefLanguage){
	echo "\n<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$value.".wikipedia.org/wiki/".str_replace(" ","_",$greatest_link_liste_mit_translation[$v][$value])."\" target=\"_blank\">".$greatest_link_liste_mit_translation[$v][$value]."</a></td>";	
		
		}
	}
	
echo "</tr>";
}
}


echo "</table>";



echo "</span></div>";
}




echo $Disclaim;

?>
</body>
