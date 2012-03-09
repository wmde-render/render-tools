
<?php
/*
 * Name: CD2_Abfrage.php
 * 
 * Description: UI for the Change Detector 
 * 
 * Author: Anselm Metzger
 * 
 * includes: cd2db_query.inc : database query 
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
 */

$start = time();


include("inc/src/cd2db_query.inc");
include("inc/src/Languagecodes.inc");

?>

<script type="text/javascript">

function openAdvancedSettings () {
	if (document.getElementById("AdvancedSearch").style.display == "block") {
		document.getElementById("AdvancedSearch").style.display = "none";
		}
	else {
		document.getElementById("AdvancedSearch").style.display = "block";
		}	

}


</script>

<body>

<?php
// Defining the Entryform

	setlocale(LC_TIME, 'de_DE');
	$timestamp_since = '1330849800'; // 4.3.2012 8:30 UTC
 	$today = time();
 	$yesterday = $today - ( 1 * 24 * 60 * 60);
 	// no new data till 8:30 am
 	if (date('Gi',$today) < 830) { 
	$yesterday = $yesterday - ( 1 * 24 * 60 * 60);
	} 
	
?>



<div id="Ueberschrift" style="float:left; vertical-align:middle;">
<div id="Introduction">	
<h2><?php echo $Headline; ?></h2>
<p id="Description"><?php echo $Description; ?></p>
<p><a href="info.php"><?php echo $MoreInfo; ?></a></p>
</div>
<FORM ACTION="" METHOD=GET>	
<p id="Description"><?php echo $Formtext; ?></p>
  <div id="Eingabe">
  
  
<div id="AdvancedSearch" >

<a onclick="javascript:openAdvancedSettings()"><?php echo $Settings["Headline"] ?></a>
<ul>
<li title="<?php echo $Settings["HalfTooltip"] ?>"><?php echo $Settings["Half"] ?>: <input type="checkbox" name="Cuthalf"<?php if (isset($_GET["Cuthalf"]) OR !isset($_GET["submit"])) {echo "checked=\"checked\"";} ?>></li>
<li title="<?php echo $Settings["SortingTooltip"] ?>"><?php echo $Settings["Sorting"] ?>: <?php echo $Settings["SortingNoChange"] ?> <input type="radio" name="Sorting" value="No_change" checked="checked">  <?php echo $Settings["SortingNews"] ?> <input type="radio" name="Sorting" value="News" <?php if ($_GET["Sorting"] == "News") {echo "checked=\"checked\"";} ?> > </li>
<li><span >Filter:</span>
<ul>
<li><input name="filterMU" type="checkbox"   <?php if (isset($_GET["filterMU"]) OR !isset($_GET["submit"])) {echo "checked=\"checked\"";} ?>> <?php echo "<span title=\"".$Filter["m_uTooltip"]."\">".$Filter["m_u"]."</span>";?></li>
<li><input name="filterNB" type="checkbox"   <?php if (isset($_GET["filterNB"]) OR !isset($_GET["submit"])) {echo "checked=\"checked\"";} ?>> <?php echo "<span title=\"".$Filter["n_bTooltip"]."\">".$Filter["n_b"]."</span>";?> </li>
<li><input name="filterOM" type="checkbox"   <?php if (isset($_GET["filterOM"]) OR !isset($_GET["submit"])) {echo "checked=\"checked\"";} ?>> <?php echo "<span title=\"".$Filter["o_mTooltip"]."\">".$Filter["o_m"]."</span>";?> </li>
</ul>
</li>
</ul>

</div>
  
  <div style="float:left;">
	<p>
	<SELECT NAME="DAY" SIZE=1 >
	<?php
		$temp_time = $yesterday;
		for (;;){
			echo "<option value=\"".date('Ymd',$temp_time)."\"";
			if ($_GET["DAY"] == date('Ymd',$temp_time)) echo "selected='selected'";
			echo ">".date('d-m-Y', $temp_time)."</option>";	
			$temp_time = $temp_time - ( 1 * 24 * 60 * 60);
			if ($temp_time < $timestamp_since) break;		
			}
	?>
	</SELECT></p>
	<p title="<?php echo $Settings["LanggroupTooltip"] ?>"><?php echo $Settings["Langgroup"] ?>: <?php echo $Settings["EU"] ?> <input type="radio" name="Langgroup" value="EU" checked="checked">  <?php echo $Settings["World"] ?> <input type="radio" name="Langgroup" value="All" <?php if ($_GET["Langgroup"] == "All") {echo "checked=\"checked\"";} ?> > </p>
	<p title="<?php echo $Settings["ReferenzlangTooltip"] ?>"><?php echo $Settings["Referenzlang"] ?>: <input NAME="Reflang" SIZE="3" value="<?php if (isset($_GET["Reflang"])) {echo $_GET["Reflang"];} else {echo $lang;} ?>"></p>
	<p><a id="AdvancedSetting" onclick="javascript:openAdvancedSettings()"><?php echo $Settings["Headline"] ?></a></p>
	<p>
	<INPUT name="submit" TYPE=SUBMIT VALUE="<?php echo $Formbutton; ?>"/>
	</p>
	
	</div>
	
  </div>

</FORM>

</div>







<div  style="width: 98%; padding: 1em; clear:both;"></div>




<?php

// Evaluating the parameter of the form

if (isset($_GET["submit"])){

	
	if ($_GET["Reflang"] != "") $reflang = $_GET["Reflang"];
	
	$Date = $_GET["DAY"];

	$SortingOption = 0;
	if ($_GET["Sorting"] == "News") $SortingOption = 1;
			
	if ($_GET["Langgroup"] == "EU" ) $LangGroup = array("de","en", "fr", "pt","it","pl","ru","nl","sv","es");			
	if ($_GET["Langgroup"] == "All" ) $LangGroup = array("de","en","fr", "pt","it","pl","ru","nl", "sv","es", "ja","zh");

	$Cuthalf = FALSE;
	if ($_GET["Cuthalf"] == "on") $Cuthalf = TRUE;

	$No_Filter["m_u"] = TRUE;
	if ($_GET["filterMU"] != "on") $No_Filter["m_u"] = FALSE; 

	$No_Filter["n_b"] = TRUE;
	if ($_GET["filterNB"] != "on") $No_Filter["n_b"] = FALSE;

	$No_Filter["o_m"] = TRUE;
	if ($_GET["filterOM"] != "on") $No_Filter["o_m"] = FALSE;

$Disclaim = "<div id=\"Disclaimer\">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Philipp Zedler)</p></span>
</div>";

$flipped_Langgroup = array_flip($LangGroup);

if (!array_key_exists($reflang, $flipped_Langgroup)) {
	echo "<div id=\"Errormessage\"><span>";
	printf($Error["NotinGrp"], $reflang, $_GET["Langgroup"]);
	echo "</span></div>".$Disclaim;
	break;
}

// Put the Reflang on first position of the Langgroup

array_splice($LangGroup,$flipped_Langgroup[$reflang],1);

array_unshift($LangGroup, $reflang);



// Database-query or load from file

// Unique filename 
$file_name = "tmp_".$Date.$_GET["Langgroup"]."1".$_GET["Cuthalf"]."2".$_GET["filterMU"]."3".$_GET["filterNB"]."4".$_GET["filterOM"].".dump";

if (file_exists("src/tmp/".$file_name) AND filesize("src/tmp/".$file_name) > 8 ) {
	$db_result = unserialize(file_get_contents("src/tmp/".$file_name));
	
} 
else {
	$db_result = query_change_db($Date , $LangGroup, $Cuthalf, $No_Filter, 'p_render_change_detector_p');
	
	$uniqueID = uniqid("tmp").".tmp";
	file_put_contents("src/tmp/".$uniqueID , serialize($db_result));
	rename("src/tmp/".$uniqueID , "src/tmp/".$file_name);

}


// Enriching the database result

foreach ($db_result as $id => $db_Entry){
	$result_Entry = $db_Entry;
	
	
	$result_Entry["ChangedSum"] = count($db_Entry["Changed"]);
	
	$result_Entry["UnchangedSum"] = count($db_Entry["Unchanged"]);
	$result_Entry["Reflang"] = $reflang;

	if (array_key_exists($reflang,$db_Entry["Changed"])) $result_Entry["Refchanged"] = "-1";

	$result_Entry["titlelang"] = $reflang;
	if (array_key_exists($reflang, $db_Entry["Changed"])) {
		$result_Entry["article"] = $db_Entry[$reflang]["title"];
		$result_Entry["Refchanged"] = "1";
	} else if (array_key_exists($reflang, $db_Entry["Unchanged"])) { 
		$result_Entry["article"] = $db_Entry[$reflang]["title"];
		$result_Entry["Refchanged"] = "-1";
	} else {
	$otherTitle = search_other_article($db_Entry, $reflang);
	$result_Entry["article"] = $otherTitle["article"];
	$result_Entry["titlelang"] = $otherTitle["lang"];
	$result_Entry["Refchanged"] = "0";
	}

	if (count(array_intersect_key($result_Entry["Changed"], array_flip($LangGroup))) < 3) continue; 
	
	$Final_Result[] = $result_Entry;
	
} 



// Sorting 

foreach ($Final_Result as $key => $row) {
    $Change_of_reflang[$key]   = $row["Refchanged"];
    $ChangedSum[$key] = $row["ChangedSum"];
    $Name[$key] = $row["article"];
}

if ( $SortingOption == 1 ){
	array_multisort( $ChangedSum, SORT_DESC, $Change_of_reflang , SORT_DESC, $Name, SORT_ASC, $Final_Result );
	} else {
	array_multisort($Change_of_reflang , SORT_ASC, $ChangedSum, SORT_DESC,  $Name, SORT_ASC, $Final_Result );
	}
	


// Adding Entrys for 'no article'

foreach ($Final_Result as $k => $Entry){
	foreach ($LangGroup as $key => $Language){
		
		if (!array_key_exists($Language, $Entry)){
			
			$Entry["missing"][] = $Language;
			$Entry[$Language]["title"] = "x_blank_x";
			}
	}
}




// Contruction of the result table

$overallWidth = "1396px";
if (sizeof($LangGroup) != 12) {
	$overallWidth = "1200px";
}
$count = sizeof($LangGroup);
?>
<div id="Ergebnis" style="width: <?php echo $overallWidth; ?>;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th style="width: 190px; height: 40px; background-color:#0047AB; color: white;"><span><?php echo $Articlename; ?></span></th>
<?php
foreach ($LangGroup as $key => $Language){
	$cellWidth = 90;
	if ($count == 1) {
		$cellWidth += 16;
	}
	echo "<th style='width: ".$cellWidth."px; height:40px; background-color:#0047AB; color: white; '><span title=\"".langcode_in_en($Language)."\" >".langcode_in_local($Language)."</span></th>";
	$count --;
}
echo "</tr>";
echo "</thead>";
echo "<tbody>\n<tr><td colspan='".(sizeof($LangGroup) + 1)."'><div class='innerDiv'><table>";

foreach ( $Final_Result as $k => $v) {
	echo "<tr id=\"tabellenzeile\"  >";
	echo "<td style=\"width: 190px; height:50px; text-align: right;\">";
	
	echo "<a href=\"http://".$v["titlelang"].".wikipedia.org/wiki/".$v["article"]."\" target=\"_blank\">".str_replace("_"," ",$v["article"])."</a>";	
	echo "</td>";
	foreach ($LangGroup as $key => $Language){
		$result_cell_color = "white"; $result_cell_fontcolor = "#ccc"; $result_cell_text = "no article"; $existent = false;
		$result_cell_class = "";
		
		if (array_key_exists($Language, $v["Changed"])) {$result_cell_color = "#008000"; $result_cell_fontcolor = "white"; $result_cell_text = "changed"; $existent=true;
		
		}
		
		if (array_key_exists($Language, $v["Unchanged"])) {$result_cell_class = "unch"; $result_cell_color = "white"; $result_cell_fontcolor = "red"; $result_cell_text = "no change"; $existent=true;}
		
		
		echo "<td class=\"".$result_cell_class."\" style=\"width: 90px; height: 50px; background: ".$result_cell_color.";  text-align: center \">";

		if ($existent) {
		echo "<a  style=\"text-decoration: none; color: ".$result_cell_fontcolor."; \"   href=\"http://".$Language.".wikipedia.org/wiki/".$v[$Language]["title"]."\" target=\"_blank\"><span title=\"".str_replace("_", " ",$v[$Language]["title"])."\" >".$result_cell_text."</span></a> ";
		
		} else {
		echo "<span style=\"text-decoration: none;  color: ".$result_cell_fontcolor."\"><span >no article</span></span>";	
		}	
		echo "</td>";
		}
	echo "</tr>";
}



echo "</table></div></td></tr></tbody>";
echo "</table>";


echo "</div>";


}
$end = time();


echo "\n<!--Durch! Von ".date('G:i:s',$start)." bis ".date('G:i:s',$end)."-->\n";


?>



<div id="Disclaimer" style="clear:both">
<span><p>Copyright: Wikimedia Deutschland, 2012 (written by Philipp Zedler)</p></span>
</div>

</body>
</html>
