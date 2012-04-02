<?php

$Headline = "Change Detector";
$Description = "Change Detector shows high editing activity in Wikipedia on a certain day and compares Wikipedias in different language versions. It enables the user to identify outdated articles in certain languages. The results are presented as a table. If a certain article was modified in almost every language version, but one or two languages were left out, you would be advised to have a closer look at the article. It could be outdated. ";

$MoreInfo = "More Information";

$errorMessage = "Due to technical reasons there is currently no data available for changes since March 30th. We are working on this issue and will provide the latest data as soon as possible."

$Formtext = "Please choose a day for the Change Detector search.";
$Formbutton = "Submit";


$Settings["Headline"] = "Additional Settings";

$Settings["Langgroup"] = "Language group";
$Settings["LanggroupTooltip"] = "";

$Settings["Referenzlang"] = "Reference language";
$Settings["ReferenzlangTooltip"] = "";
$Settings["World"] = "All languages";
$Settings["EU"] = "European languages";

$Settings["Half"] = "More than a half changes";
$Settings["HalfTooltip"] = "";

$Settings["Sorting"] = "Sorting";
$Settings["SortingTooltip"] = "";
$Settings["SortingNoChange"] = "No Change";
$Settings["SortingNews"] = "News";

// printf( $_ , $reflang, $Langgroup)
$Error["NotinGrp"] = "The reference language %s is not in the language group %s.";
$Error["NotinDay"] = "Due to technical issues, there is no data for the reference language '%s' on this day.";

$Statistik["Day"] = "Chosen date";
$Statistik["Langgroup"] = "Chosen language group";

$Filter["m_u"] = "Many editors";
$Filter["m_uTooltip"] = "";
$Filter["n_b"] = "No bot edits";
$Filter["n_bTooltip"] = "";
$Filter["o_m"] = "Only major edits";
$Filter["o_mTooltip"] = "";

$Articlename = "article";

$YesNo["Yes"] = "yes";
$YesNo["No"] = "no";

?>
