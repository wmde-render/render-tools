<?php
$Headline = "Change Detector";
$Description = "The aim of the Change Detector is to find articles that 
	may need to be updated by comparing editing activity in different 
	language versions. The Change Detector searches for articles with 
	high editing activity in most of the language versions on a certain 
	day. This may be an indication, that the articles in the unedited 
	language versions are outdated and need revision. Moreover the Change 
	Detector shows articles which may be missing in few language versions 
	while they exist in most of the other languages.<br /><br />
	The Change Detector is incorporated into the <a href=\"http://toolserver.org/~render/stools/tlg\" target=\"_blank\">Article List Generator</a>, 
	so it is possible to limit the result to specified categories. You 
	can also access the Change Detector from the display window of the 
	<a href=\"http://toolserver.org/~render/stools/asqm\" target=\"_blank\">Article Monitor.</a>";
$Headline2 = "Results";
$Description2 = "The results are presented as a table. Each row refers 
	to an article discovered by the Change Detector. The columns show 
	the state of the specified article for each language version headed 
	by the reference language. <br /><br />
	A green cell with the label \"changed\" means that in this article 
	and language there have been more changes than the last 50 days. The 
	label \"no change\" means there were less changes than the last 50 
	days. Cells with the label \"no article\" indicate that there is no 
	article in this language, though there are articles in other 
	languages.";

$MoreInfo = "More Information";

$errorMessage = "";

$Formtext = "Please choose a day for the Change Detector search.";
$Formbutton = "Submit";

$Settings = array(
	"Headline" => "Additional Settings",
	"Langgroup" => "Language group",
	"LanggroupTooltip" => "",
	"Referenzlang" => "Reference language",
	"ReferenzlangTooltip" => "",
	"World" => "All supported languages",
	"EU" => "European languages",
	"Half" => "More than a half changes",
	"HalfTooltip" => "",
	"Sorting" => "Sorting",
	"SortingTooltip" => "",
	"SortingNoChange" => "No Change",
	"SortingNews" => "News"
);

$Error = array(
	"NotinGrp" => "The reference language %s is not in the language group %s.",
	"NotinDay" => "Due to technical issues, there is no data for the reference 
		language '%s' on this day."
);

$Statistik = array(
	"Day" => "Chosen date",
	"Langgroup" => "Chosen language group"
);

$Filter = array(
	"m_u" => "Many editors",
	"m_uTooltip" => "",
	"n_b" => "No bot edits",
	"n_bTooltip" => "",
	"o_m" => "Only major edits",
	"o_mTooltip" => ""
);

$Articlename = "article";

$YesNo = array(
	"Yes" => "yes",
	"No" => "no"
);
