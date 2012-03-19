
<?php
include("cd2_abfrage_displ_text.php");
?>
<script type="text/javascript">


function getElementsByContainsClassName(class_name)
{
  var all_obj,ret_obj=new Array(),j=0;
  if(document.all)all_obj=document.all;
  else if(document.getElementsByTagName && !document.all)all_obj=document.getElementsByTagName("*");
  
  var Suche = new RegExp(class_name);
 
  for(i=0;i<all_obj.length;i++)
  {
	  if(Suche.test(all_obj[i].className))
	  {
		ret_obj[j]=all_obj[i];
		j++
	  }
    }
  return ret_obj;
}

function onoff (filter_name) {

  var obj=getElementsByContainsClassName(filter_name);

  var SucheMU = /m_u/;

  var SucheNB = /n_b/;
  var SucheOM = /o_m/;
  
  for(i=0;i<obj.length;i++)
  { 	  
	  
	if (obj[i].style.background == "#90ee90" || obj[i].style.backgroundColor == "rgb(144, 238, 144)" || obj[i].style.background == "green" )
	{ 
		
		if (SucheMU.test(obj[i].className) && document.getElementById("check_m_u").checked  ){ obj[i].style.background = "#90ee90"; continue; }

		if (SucheNB.test(obj[i].className) && document.getElementById("check_n_b").checked  ){ obj[i].style.background = "#90ee90"; continue; }
		if (SucheOM.test(obj[i].className) && document.getElementById("check_o_m").checked  ){ obj[i].style.background = "#90ee90"; continue; }
		
	obj[i].style.background = "#008000";
	} else 
	{
		
		
	obj[i].style.background = "#90ee90";	
	}	
  }

}



</script>
<style type="text/css">
tr#tabellenzeile:hover { background: lightblue; border:4px solid lightblue; }
</style>	
</head>
<body>

<?php
	setlocale(LC_TIME, 'de_DE');
	$timestamp_19_2_12 = '1329609660';
	$heute = time();
	$gestern = $heute - ( 1 * 24 * 60 * 60);
?>

<div id="Eingabe2" style="width: 98%; padding: 1em;">
<FORM ACTION="" METHOD=POST>	
<div id="Ueberschrift" style="float:left; vertical-align:middle; width:50%">
<h2><?php echo $Headline; ?></h2>
<p><?php echo $Formtext; ?></p>
 
  <div id="Eingabe">
	<SELECT NAME="TAG" SIZE=1 >
	<?php
		$temp_time = $gestern;
		for (;;){
			$temp_time = $temp_time - ( 1 * 24 * 60 * 60);
			if ($temp_time < $timestamp_19_2_12) break;
			echo "<option value=\"".$temp_time."\">".date('d-m-Y', $temp_time)."</option>";			
			}
	?>
	</SELECT>	
	<INPUT name="submit" TYPE=SUBMIT VALUE="<?php echo $Formbutton; ?>"/>
	
	</div>



</div>	


<div id="AdvancedSearch" style="float:right;   background: #e6e6e6; padding: 1px; ">
<div id="Ueberschrift">
<h2><?php echo $Einstellungen["Headline"] ?></h2>
<p><?php echo $Einstellungen["Langgroup"] ?>: EU <input type="radio" name="Langgroup" value="EU" checked="checked">  <?php echo $Einstellungen["Welt"] ?> <input type="radio" name="Langgroup" value="All" > </p>
<p><?php echo $Einstellungen["Referenzlang"] ?>: <input NAME="Reflang" SIZE="3" value="<?php echo $lang; ?>"></p>
</div>
</div>
</FORM>
</div>

<div id="Eingabe2" style="width: 98%; padding: 1em; clear:both;"/>




<?php

include("toolserver_sql_agfragen.inc");
include("cd2db_query.inc");

if (isset($_POST["submit"])){

	
	if ($_POST["Reflang"] != "") $reflang = $_POST["Reflang"];
	
	$Datum = date('Ymd',$_POST["TAG"]);

			
	if ($_POST["Langgroup"] == "EU" ) $Sprachgruppe = array("de","en","pt","it","pl","ru");			
	if ($_POST["Langgroup"] == "All" ) $Sprachgruppe = array("de","en","pt","it","pl","ru","ja","zh");


$Disclaim = "<div id=\"Disclaimer\">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Philipp Zedler)</p></span>
</div>";


if (!array_key_exists($reflang, array_flip($Sprachgruppe))) die("<div id=\"Errormessage\"><span>Die Referenzsprache '".$reflang."' ist nicht Teil der Sprachgruppe ".$_POST["Langgroup"]."</span></div>".$Disclaim);



echo "<div id=\"info\">";
echo "<p>".$Statistik["Day"].": ".date('j. n. y',$_POST["TAG"])."</p>";
echo "<p>".$Statistik["Langgroup"].": ".$_POST["Langgroup"]." : ";
foreach ($Sprachgruppe as $k => $v) {
	echo $v ." ";
}
echo "</p><p>".$Einstellungen["Referenzlang"].": ". $reflang ."</p></div>";



// Hier muss jetzt die Datenbank abfrage hin.


$Datenbank_Ergebnis = query_change_db($Datum , 'u_philippze_change_detector_p');



foreach ($Datenbank_Ergebnis as $id => $Eintrag){
	//kopiert alle Datenbankergebnisse
	$EndEintrag = $Eintrag;
	
	
	$EndEintrag["ChangedSum"] = count($Eintrag["Changed"]);
	
	$EndEintrag["UnchangedSum"] = count($Eintrag["Unchanged"]);
	$EndEintrag["Reflang"] = $reflang;

	if (array_key_exists($reflang,$Eintrag["Changed"])) $EndEintrag["Refchanged"] = "-1";

	$EndEintrag["Titellang"] = $reflang;
	if (array_key_exists($reflang, $Eintrag["Changed"])) {
		$EndEintrag["Artikel"] = $Eintrag["Changed"][$reflang];
		$EndEintrag["Refchanged"] = "1";
	} else if (array_key_exists($reflang, $Eintrag["Unchanged"])) { 
		$EndEintrag["Artikel"] = $Eintrag["Unchanged"][$reflang];
		$EndEintrag["Refchanged"] = "-1";
	} else {
	$otherTitle = search_other_article($Eintrag, $reflang);
	$EndEintrag["Artikel"] = $otherTitle["Artikel"];
	$EndEintrag["Titellang"] = $otherTitle["lang"];
	$EndEintrag["Refchanged"] = "0";
	}

	if (count(array_intersect_key($EndEintrag["Changed"], array_flip($Sprachgruppe))) < 3) continue; 
	
	$EndErgebnis[] = $EndEintrag;
	
} 

/// Sortierung des EndErgebnis

foreach ($EndErgebnis as $key => $row) {
    $Referenzchange[$key]   = $row["Refchanged"];
    $ChangedSum[$key] = $row["ChangedSum"];
    $Name[$key] = $row["Artikel"];
}


array_multisort($Referenzchange , SORT_ASC, $ChangedSum, SORT_DESC,  $Name, SORT_ASC, $EndErgebnis );

echo "<!--";
print_r($EndErgebnis[0]);
echo "-->";


foreach ($EndErgebnis as $k => $Eintrag){
	foreach ($Sprachgruppe as $key => $Sprache){
		
		if (!array_key_exists($Sprache, $Eintrag)){
			
			$Eintrag["fehlt"][] = $Sprache;
			$Eintrag[$Sprache]["titel"] = "x_blank_x";
			}
	}
}




?>
<div id="Legende">
<Form name"FILTERForm" action="">
<p>Filter:
<input id="check_m_u" type="checkbox"  onclick="javascript:onoff('m_u')"> <?php echo "<span title=\"".$Filter["m_uTooltip"]."\">".$Filter["m_u"]."</span>";?>
<input id="check_n_b" type="checkbox"  onclick="javascript:onoff('n_b')"> <?php echo "<span title=\"".$Filter["n_bTooltip"]."\">".$Filter["n_b"]."</span>";?> 
<!--input id="check_o_u" type="checkbox"  onclick="javascript:onoff('o_u')"> only_usual -->
<input id="check_o_m" type="checkbox"  onclick="javascript:onoff('o_m')"> <?php echo "<span title=\"".$Filter["o_mTooltip"]."\">".$Filter["o_m"]."</span>";?> 
</p>
</Form>
</div>

<div id="Ergebnis"><span>

<table border="0"  >
 <tr  align="center"  style="background: #0047AB; color:white">
 <th style="padding-left: 5px; padding-right: 5px; margin-bottom: 3px">Artikelname</th>
<?php

foreach ($Sprachgruppe as $key => $Sprache){ 
echo "<th style=\"height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;\">".$Sprache."</th>";
}
echo "</tr>";

foreach ( $EndErgebnis as $k => $v){
	echo "\n<tr id=\"tabellenzeile\"  >";
	echo "<td style=\"text-align: right; padding: 3px; padding-left: 6px; padding-right: 6px; border:4px solid white; width: 80px \">";
	echo "<a href=\"http://".$v["Titellang"].".wikipedia.org/wiki/".$v["Artikel"]."\" target=\"_blank\">".str_replace("_"," ",$v["Artikel"])."</a>";	
	echo "</td>";
	foreach ($Sprachgruppe as $key => $Sprache){
		$ampelcolor = "white"; $ampelfontcolor = "#ccc"; $ampeltext = "no article"; $existent = false;
		$ampelclass = "";
		
		if (array_key_exists($Sprache, $v["Changed"])) {$ampelcolor = "#008000"; $ampelfontcolor = "white"; $ampeltext = "changed"; $existent=true;
		
			if ( $v[$Sprache]["filter_only_major"] != 1 ) { $ampelclass .="o_m ";  } 

			if ( $v[$Sprache]["filter_many_user"] != 1 ) { $ampelclass .="m_u ";  }
			if ( $v[$Sprache]["filter_non_bot"] != 1 ) { $ampelclass .="n_b ";  }
		}
		
		if (array_key_exists($Sprache, $v["Unchanged"])) {$ampelclass = "unch"; $ampelcolor = "white"; $ampelfontcolor = "red"; $ampeltext = "no change"; $existent=true;}
		
		
		echo "<td  class=\"".$ampelclass."\" style=\"border:4px solid white; height: 50px; width: 60px; padding: 3px; padding-left: 6px; padding-right: 6px; background: ".$ampelcolor.";  text-align: center \">";
		if ($existent) {
		echo "<a  style=\"text-decoration: none; color: ".$ampelfontcolor."; \"   href=\"http://".$Sprache.".wikipedia.org/wiki/".$v[$Sprache]["titel"]."\" target=\"_blank\">".$ampeltext."</a> ";
		
		} else {
		echo "<span style=\"text-decoration: none;  color: ".$ampelfontcolor."\">no article</span>";	
		}	
		echo "</td>";
		}
	echo "</tr>";
}


echo "</table>";
echo "</span>";



}



?>



<div id="Disclaimer" style="clear:both">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Philipp Zedler)</p></span>
</div>

</body>
</html>
