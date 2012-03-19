
<?php
include("lea_displ_text.php");
?>

<div id="Ueberschrift"><h2><?php echo $Headline; ?></h2>
<p><?php echo $Description; ?></p>
<p><?php echo $Formtext; ?></p>
 <FORM ACTION="" METHOD="GET">
  <div id="Eingabe">
	  <span><?php echo $FormTitle; ?></span>
  <INPUT NAME="titel" SIZE=30 MAXLENGTH=60 value="<?php if (isset($_GET["titel"])) {echo $_GET["titel"];} ?>" >
	  <span><?php echo $FormIn; ?></span>
  <INPUT NAME="lang" SIZE=5 MAXLENGTH=10 value="<?php if (isset($_GET["lang"])) {echo $_GET["lang"];} else {echo $lang;}?>">
		<span>.wikipedia.org</span>
	<INPUT name="submit" TYPE="SUBMIT"  VALUE="<?php echo $Formbutton; ?>" >
 </FORM>
</div>
</div>

<?php

include("toolserver_sql_agfragen.inc");
include("create_chart.inc");

if(isset($_GET["submit"]))
{
	
// Gesuchter Artikel
if(!$_GET["titel"] || empty($_GET["titel"])){
$artikeltitel = "Jerry_Siegel";

} else {
$artikeltitel = $_GET["titel"];	
}

if(!$_GET["lang"] || empty($_GET["lang"])){
$Sprachversion = "de";

} else {
$Sprachversion = $_GET["lang"];	
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

if ($artikel_id == 0) die("<div id=\"Errormessage\"><span>Ein Artikel mit dem Titel existiert ". $artikeltitel ." nicht  in Wikipedia (". $Sprachversion.")</span></div>".$Disclaim);

$orig_langlinks = abfragen_langlinks($artikel_id, $Sprachversion);

if ($orig_langlinks == 0) die("<div id=\"Errormessage\"><span>Der Artikel hat keine Übersetzung</span></div>".$Disclaim);


	
// Lang und wikilinks abfragen

$orig_links = abfragen_links($artikel_id, $Sprachversion);

$orig_links = array_flip($orig_links);

// Für später als ergebnisliste LEA1 speichern
$ergebnis_links_lea1 = $orig_links;

foreach ( $ergebnis_links_lea1 as $k=>$v){
	$ergebnis_links_lea1[$k] = 0;
}

// Übersetzungen der Links erfragen LEA1
foreach ($orig_links as $link => $value){
	$link_id_tmp = artikel_id_abfragen($link);
	 $orig_links[$link] = abfragen_langlinks($link_id_tmp, $Sprachversion);	
}


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

//LEA1


foreach ($orig_links as $link => $link_trans_array){
	
	if (!$link_trans_array == 0){
		foreach ($link_trans_array as $Sprache => $titel){
		
			if (array_key_exists($Sprache, $link_liste_nach_lang)){
		
				if (array_key_exists(str_replace(" ", "_", $titel), $link_liste_nach_lang[$Sprache])){
					$ergebnis_links_lea1[$link] ++;
			
				}
			}
	
		}
	
	}
	
}	
arsort($ergebnis_links_lea1);
$ergebnis_wolke_lea1 = NULL;
$i = 0;
foreach ($ergebnis_links_lea1 as $link => $Anzahl)
{	
	if ($Anzahl == 0) break;
	$ergebnis_wolke_lea1[$link] = $Anzahl;	
	$i++;
	if ($i >= 5) break;
}
echo "<!--";
print_r($ergebnis_wolke_lea1);
echo "-->";
//LEA2

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
echo "<!--";
print_r ($greatest_link_liste_mit_translation);
echo "-->";

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

echo "<div id=\"chart\" class=\"tooltip\">"; 
echo "<h3 >".$Charttitel."</h3>";
$Tabellen_label = "Not_used*article_exists*Used";
echo "<span title=\"".$Tooltip["chart"]."\">";
echo "<img src=\"http://toolserver.org/~heimdall/LEA2/piechart3p.php?values=".$Links_als_Fakten[0]."*".$Links_als_Fakten[1]."*".$Links_als_Fakten[2]."&title=".str_replace(" ","_",$artikeltitel)."&lang=".$lang."\"/>";
echo "</span>";
echo "</div>";

echo "<div id=\"info\" >";

echo "<span>";
echo "<p>";
printf($Statistik_Einleitung, $Sprachversion, $artikeltitel, $artikeltitel, count($orig_langlinks), $SprachAnzahl);
echo "</p><ul>";
foreach ($biggest_lang as $k => $v){
	echo "<li>";
	printf($Listenelement_greatestSprachen , $orig_langlinks[$v], $v, $greatest_trans[$v] );
	echo "</li>";
	}	
	echo "<li>";
	printf($Listenelement_Ausgangssprache , $Sprachversion, count($orig_links) );
	echo "</li>";	
echo "</ul></p></span>";

echo "</div>";

echo "<div id=\"Legende\"><span class=\"Legendenelement\"><span style=\"border: 1px solid black; background: red;\">&nbsp;&nbsp;</span>&nbsp;".$Legende["red"]."</span><span class=\"Legendenelement\"><span style=\"border: 1px solid black; background: yellow;\">&nbsp;&nbsp;</span>&nbsp;".$Legende["yellow"]."</span><span class=\"Legendenelement\"><span style=\"border: 1px solid black; background: green;\">&nbsp;&nbsp;</span>&nbsp;".$Legende["green"]."</span></div>";
echo "<div id=\"Ergebnis\"><span>";

echo "<table class=\"Leatable\" border=\"0\"  >";
echo "<tr  align=\"center\"  style=\"background: #0047AB; color:white; \">";

echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\">".$Sprachversion."</th>";

echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\">".$VergleichsSprache."</th>";

foreach ($biggest_lang as $k => $v){
	if ($v != $VergleichsSprache)
	echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\">".$v."</th>";	
	
	}
	
echo "</tr>";


if (isset($Ergebnis_Fakten_fehlen)){
foreach ($Ergebnis_Fakten_fehlen as $k => $v){ 
	echo "<tr id=\"tabellenzeile\">";
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: red; text-align: center;\"><a title=\"".$Legende["red"]."\">-</a></td>";
	
	
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$VergleichsSprache.".wikipedia.org/wiki/".$v."\" target=\"_blank\">".str_replace("_", " ", $v)."</a></td>";
foreach ($biggest_lang as $key => $value){
	if ($value != $VergleichsSprache){
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$value.".wikipedia.org/wiki/".str_replace(" ","_",$greatest_link_liste_mit_translation[$v][$value])."\" target=\"_blank\">".$greatest_link_liste_mit_translation[$v][$value]."</a></td>";	
		}
	}

	echo "</tr>";
	}
}


if (isset($Ergebnis_Fakten_fehlen_artikel_existiert)){

foreach ($Ergebnis_Fakten_fehlen_artikel_existiert as $k => $v){
echo "<tr id=\"tabellenzeile\">";
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: yellow; text-align: center;\"><a href=\"http://".$Sprachversion.".wikipedia.org/wiki/".$Existing_Art[$v]."\" target=\"_blank\">".$Existing_Art[$v]."</a></td>";
	
	
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$VergleichsSprache.".wikipedia.org/wiki/".$v."\" target=\"_blank\">".str_replace("_", " ", $v)."</a></td>";
	
	foreach ($biggest_lang as $key => $value){
	if ($value != $VergleichsSprache){
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$value.".wikipedia.org/wiki/".str_replace(" ","_",$greatest_link_liste_mit_translation[$v][$value])."\" target=\"_blank\">".$greatest_link_liste_mit_translation[$v][$value]."</a></td>";	
		
		}
	}
	
echo "</tr>";
}
}

if (isset($Ergebnis_Fakten_vorhanden)){

foreach ($Ergebnis_Fakten_vorhanden as $k => $v){
echo "<tr id=\"tabellenzeile\">";
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; background: green; text-align: center;\"><a style=\"color: white;\" href=\"http://".$Sprachversion.".wikipedia.org/wiki/".$Used_Art[$v]."\" target=\"_blank\">".$Used_Art[$v]."</a></td>";
	
	
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$VergleichsSprache.".wikipedia.org/wiki/".$v."\" target=\"_blank\">".str_replace("_", " ", $v)."</a></td>";
	
	foreach ($biggest_lang as $key => $value){
	if ($value != $VergleichsSprache){
	echo "<td style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px; text-align: center;\"><a href=\"http://".$value.".wikipedia.org/wiki/".str_replace(" ","_",$greatest_link_liste_mit_translation[$v][$value])."\" target=\"_blank\">".$greatest_link_liste_mit_translation[$v][$value]."</a></td>";	
		
		}
	}
	
echo "</tr>";
}
}


echo "</table>";

/*
echo "<p>Aus dem Schnitt der betrachteten Sprachen ergibt sich, dass ".$Links_als_Fakten_Anzahl." Links besonders wichtig für den Artikel sein könnten.</p>";

if (isset($Ergebnis_Fakten_fehlen)){
echo "<p>Von diesen ".$Links_als_Fakten_Anzahl." Links werden ".count($Ergebnis_Fakten_fehlen)." in '".$Sprachversion."' nicht verwendet und besitzen keinen Artikel in '".$Sprachversion."':</p>";
echo "<ul>";
foreach ($Ergebnis_Fakten_fehlen as $k => $v){
	echo "<li>". str_replace("_", " ", $v) ."</li>";
		}		
echo "</ul>";
echo "<p><small>Links sind in '".$VergleichsSprache."' angegeben.</small></p>";
}
if (isset($Ergebnis_Fakten_fehlen_artikel_existiert)){
echo "<p>Von den ".$Links_als_Fakten_Anzahl." Links werden ".count($Ergebnis_Fakten_fehlen_artikel_existiert)." nicht verwendet, es existiert jedoch ein Artikel in '".$Sprachversion."':</p>";
echo "<ul>";
foreach ($Ergebnis_Fakten_fehlen_artikel_existiert as $k => $v){
	echo "<li>". $Existing_Art[$v] ."</li>";
		}
echo "</ul>";
}
if (isset($Ergebnis_Fakten_vorhanden)){
$alle_Sprachen = $SprachAnzahl +1;	
echo "<p>Folgende ".count($Ergebnis_Fakten_vorhanden)." Links werden in allen ". $alle_Sprachen." Versionen verwendet:</p>";
echo "<ul>";
foreach ($Used_Art as $k => $v){
	if ( array_key_exists($k, array_flip($Ergebnis_Fakten_vorhanden) )){
	echo "<li>". $v ."</li>";
		}
	}
echo "</ul>";
}
*/

echo "</span></div>";
}
?>









<div id="Disclaimer">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Anselm Metzger)</p></span>
</div>

</body>
</html>
