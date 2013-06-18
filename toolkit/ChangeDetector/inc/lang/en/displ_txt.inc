<?php
$Headline = "Change Detector";
$Description = "The aim of the Change Detector is finding Wikipedia 
	articles that may need to be updated. This is achieved by comparing 
	editing activity in different language versions. The Change Detector 
	searches for articles with high editing activity in most of the 
	language versions on a certain day. This may be an indication that 
	the respective articles in the unedited language versions are 
	outdated and in need of revision. Moreover, the Change Detector 
	shows articles which may be missing in one or a few language 
	versions even though they exist in most of the other language 
	versions.<br />
	<br />
	The Change Detector is incorporated into the 
	<a href=\"http://toolserver.org/~render/stools/tlg\" target=\"_blank\">Article List Generator</a>. 
	It is therefore possible to limit the search result to specific 
	categories. You can also access the Change Detector from the display 
	window of the <a href=\"http://toolserver.org/~render/stools/asqm\" target=\"_blank\">Article Monitor.</a>.";
$Headline2 = "Results";
$Description2 = "The results are illustrated in the form of a table. 
	Each row refers to an article spotted by the Change Detector. The 
	first row shows the selected reference language. The remaining 
	columns show the languages of the selected language family. <br />
	<br />
	A green cell labelled \"changed\" means that this article had been 
	changed more often on the selected day than in the previous fifty 
	days. The red label \"no change\" means that this article had not 
	been updated at all or less compared to the last fifty days. Cells 
	labelled \"no article\" indicate that there is no article in this 
	language version even though articles in other language versions 
	exist.";

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
