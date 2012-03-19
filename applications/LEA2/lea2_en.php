

<div id="Ueberschrift"><h2>LEA2 Development</h2>
<p>Enter an article title and choose a Wikipedia language version.</p>
 <FORM ACTION="" METHOD=POST>
  <div id="Eingabe">
	  <span>Title:</span>
  <INPUT NAME="titel" SIZE=30 MAXLENGTH=60>
	  <span>in:</span>
  <INPUT NAME="lang" SIZE=5 MAXLENGTH=60 value="en"><span>.wikipedia.org</span>
	<INPUT name="submit" TYPE=SUBMIT VALUE="Submit">
 </FORM>
</div>
</div>

<?php

include("toolserver_sql_agfragen.inc");
include("create_chart.inc");

if(isset($_POST["submit"]))
{
	
// Gesuchter Artikel
if(!$_POST["titel"] || empty($_POST["titel"])){
$artikeltitel = "Jerry_Siegel";

} else {
$artikeltitel = $_POST["titel"];	
}

if(!$_POST["lang"] || empty($_POST["lang"])){
$Sprachversion = "en";

} else {
$Sprachversion = $_POST["lang"];	
}


$Sprachvers_wiki = $Sprachversion . "wiki_p";
$Sprachvers_wiki_db = $Sprachversion . "wiki-p.rrdb.toolserver.org";

// Datenbank oeffnen
$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/.my.cnf");
$db = mysql_connect($Sprachvers_wiki_db, $ts_mycnf['user'], $ts_mycnf['password']);

if (!$db) {
    die('Verbindung schlug fehl (db1): ' . mysql_error());
}


mysql_select_db($Sprachvers_wiki, $db);

$artikel_id = artikel_id_abfragen($artikeltitel);	

$Disclaim = "<div id=\"Disclaimer\">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Anselm Metzger)</p></span>
</div>";

if ($artikel_id == 0) die("<div id=\"Errormessage\"><span>An Article with title ". $artikeltitel ." doesn't exists in Wikipedia (". $Sprachversion.")</span></div>".$Disclaim);

$orig_langlinks = abfragen_langlinks($artikel_id, $Sprachversion);

if ($orig_langlinks == 0) die("<div id=\"Errormessage\"><span>This article doesn't have any translations.</span></div>".$Disclaim);


	
// Lang und wikilinks abfragen

$orig_links = abfragen_links($artikel_id, $Sprachversion);

$orig_links = array_flip($orig_links);


// Datensammlung in Sprache abgeschlossen
mysql_close($db);



// Für jede Übersetzung die Links erfragen
foreach ($orig_langlinks as $Sprache => $transtitel){
	
	$db2 = mysql_connect($Sprache . "wiki-p.rrdb.toolserver.org", $ts_mycnf['user'], $ts_mycnf['password']);
	
	if (!$db2) {
		die('Verbindung schlug fehl (db2): ' . mysql_error());
	}
	
	
	$test = mysql_select_db($Sprache . "wiki_p", $db2);
	//echo "\n".$Sprache . "wiki_p";
	if (!$test) {
		mysql_close($db2);
		//echo ":  wurde ausgelassen";
		continue;
		}
	
	

	$langlink_id = artikel_id_abfragen($transtitel);	

	$link_liste_nach_lang[$Sprache] = abfragen_links($langlink_id,$Sprache);
	
	mysql_close($db2);
}



foreach ($link_liste_nach_lang as $k => $v){
	$link_liste_nach_lang[$k] = array_flip($v);
}

// Für jede Sprache die Linkanzahl berechnen und danach sortieren
foreach ($link_liste_nach_lang as $Sprache => $link_array ){
	$greatest_trans[$Sprache] = count($link_array);
}
	
arsort($greatest_trans);


// Die drei größten, oder weniger, Sprachen herausfiltern 
$i = 0;
foreach ($greatest_trans as $Sprache => $link_Anzahl){
		$biggest_lang[$i] = $Sprache;
		$i++; 
		if ($i > 2) break;
}

$SprachAnzahl = count($biggest_lang);

$betrachtete_Sprachen = array_flip($biggest_lang);
$betrachtete_Sprachen[$Sprachversion] = "3";

if (array_key_exists("en", array_flip($biggest_lang))) $en = true;

echo "<div id=\"info\"><span>";
echo "<p>Article <a href=\"http://".$Sprachversion.".wikipedia.org/wiki/".$artikeltitel."\" >". $artikeltitel ."</a> found in Wikipedia (". $Sprachversion."). "; 
echo "The article_id is: " .$artikel_id."</p>";
echo  "<p>This article has ".count($orig_langlinks)." translations. The ".$SprachAnzahl." biggest versions (without '". $Sprachversion."') are:<ul>";
foreach ($biggest_lang as $k => $v){
	echo "<li>".$orig_langlinks[$v]." (".$v.") with ".$greatest_trans[$v]." used links in total</li>";
	}	
	echo "<li>('".$Sprachversion ."' uses ".count($orig_links)." links )</li>";
echo "</ul></p></span></div>";

$VergleichsSprache = "";

foreach ($biggest_lang as $k => $AusgangsSprache){
	if ($en and $AusgangsSprache != "en") continue;
	$VergleichsSprache = $AusgangsSprache;
	$db3 = mysql_connect($AusgangsSprache . "wiki-p.rrdb.toolserver.org", $ts_mycnf['user'], $ts_mycnf['password']);
	
	if (!$db3) {
		die('Verbindung schlug fehl (db3): ' . mysql_error());
	}
	
	
	$test = mysql_select_db($AusgangsSprache . "wiki_p", $db3);
	//echo "\n".$Sprache . "wiki_p";
	if (!$test) {
		mysql_close($db3);
		//echo ":  wurde ausgelassen";
		continue;
		}

	// mit diesen titeln kann die ID gefunden werden und danach alle links und alle ihre Übersetzungen.
	$transtitel = $orig_langlinks[$AusgangsSprache];

	$trans_id = artikel_id_abfragen($transtitel);

	$trans_link_liste = abfragen_links($trans_id);
	$trans_link_liste = array_flip($trans_link_liste);
	
	
foreach ($trans_link_liste as $link => $value){
	$link_id_tmp = artikel_id_abfragen($link);
	 $greatest_link_liste_mit_translation[$link] = abfragen_langlinks_fuer_mit($link_id_tmp, $betrachtete_Sprachen);	
}

	
	mysql_close($db3);
	break;
}


//print_r($greatest_link_liste_mit_translation);

unset($ts_mycnf, $ts_pw);

// Schnittberechnung

foreach ($greatest_link_liste_mit_translation as $link => $translink_array){
	if (!$translink_array == 0){
		$ergebnis_linkliste[$link] = 0;
		foreach($translink_array as $Sprache => $link_trans){
			if ($Sprache == $Sprachversion){
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
			if (array_key_exists(str_replace(" ", "_", $link_trans), $link_liste_nach_lang[$Sprache]))
				$ergebnis_linkliste[$link]++;
			
			}
			}
	
	}
}


foreach ($ergebnis_linkliste as $link => $Code){
	switch ($Code) {
		case $SprachAnzahl-1: 
			$Ergebnis_Fakten_fehlen[] = $link;
			break;
		case $SprachAnzahl+9: 
			$Ergebnis_Fakten_vorhanden[] = $link;
			break;		
		case $SprachAnzahl+99:
			$Ergebnis_Fakten_fehlen_artikel_existiert[] = $link;	
		}	
	 
}
$Links_als_Fakten[] =  count($Ergebnis_Fakten_fehlen);
$Links_als_Fakten[] =  count($Ergebnis_Fakten_fehlen_artikel_existiert);
$Links_als_Fakten[] =  count($Ergebnis_Fakten_vorhanden);
$Links_als_Fakten_Anzahl = array_sum($Links_als_Fakten);


echo "<div id=\"Ergebnis\"><span>";



echo "<p>If you intersect these translations it follows that ".$Links_als_Fakten_Anzahl." links have notably importance for this article.</p>";
echo "<div id=\"chart\">"; 
$Tabellen_label = array ("Not used","article exists","Used");
echo create_hor_chart($Tabellen_label, $Links_als_Fakten);
echo "</div>";
if (isset($Ergebnis_Fakten_fehlen)){
echo "<p>Of the ".$Links_als_Fakten_Anzahl." links are ".count($Ergebnis_Fakten_fehlen)." links not used in '".$Sprachversion."' and they haven't any article in '".$Sprachversion."':</p>";
echo "<ul>";
foreach ($Ergebnis_Fakten_fehlen as $k => $v){
	echo "<li>". $v ."</li>";
		}		
echo "</ul>";
echo "<p><small>links are given in '".$VergleichsSprache."'.</small></p>";
}
if (isset($Ergebnis_Fakten_fehlen_artikel_existiert)){
echo "<p>Of the ".$Links_als_Fakten_Anzahl." links are ".count($Ergebnis_Fakten_fehlen_artikel_existiert)." links not used, but there are articles with this title in '".$Sprachversion."':</p>";
echo "<ul>";
foreach ($Ergebnis_Fakten_fehlen_artikel_existiert as $k => $v){
	echo "<li>". $Existing_Art[$v] ."</li>";
		}
echo "</ul>";
}
if (isset($Ergebnis_Fakten_vorhanden)){
$alle_Sprachen = $SprachAnzahl +1;	
echo "<p>".count($Ergebnis_Fakten_vorhanden)." links are used in all ". $alle_Sprachen." language versions of this article:</p>";
echo "<ul>";
foreach ($Used_Art as $k => $v){
	if ( array_key_exists($k, array_flip($Ergebnis_Fakten_vorhanden) )){
	echo "<li>". $v ."</li>";
		}
	}	
echo "</ul>";
}

echo "</span>";



echo "</div>";



}
?>

<div id="Disclaimer">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Anselm Metzger)</p></span>
</div>

