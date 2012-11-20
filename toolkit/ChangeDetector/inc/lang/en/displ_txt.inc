<?php
$Headline = "Change Detector";
$Description = "Change Detector shows high editing activity in Wikipedia on a certain day and compares Wikipedias in different language versions. It enables the user to identify outdated articles in certain languages. The results are presented as a table. If a certain article was modified in almost every language version, but one or two languages were left out, you would be advised to have a closer look at the article. It could be outdated. ";

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