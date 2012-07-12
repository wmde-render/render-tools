<?php
$leaLang = array(
	"headline" => "LEA - Link ExtrActor",
	"description" => "LEA helps discovering missing links in Wikipedia articles. 
		It compares different language versions, and shows missing link coverage.<br />
		The intersection of links from the three link-richest language 
		versions is compared to the requested language version.<br /><br />
		In this example the english article about 
		the city of Flensburg was requested. An article about the city's 
		district of Mørvig is not available yet. The article does not 
		contain a link to the article about 'Twin towns and sister cities'.",
	"form_text" => "Please enter an article title and choose a language version.",
	"more_info" => "More Information",
	"form_title" => "Title:",
	"form_in" => "in",
	"form_button" => "Submit",
);

$Info = array(
	"langVersions1" => "The article <a href=\"http://%s.wikipedia.org/wiki/%s\" target=\"_blank\" >%s</a> 
		exists in %d further language versions.",
	"langVersions2" => "The following %d language versions contain the most links 
		to other Wikipedia articles:",
	"LIlanguages" => "<a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> ( %s ) 
		contains %d internal links.",
	"lang_link_count" => "%d links",
	"intersection" => "Intersection",
	"requested_lang" => "Requested language version",
	"LIsourcelang" => "('%s' contains %d internal links)"
);

$Error = array(
	"Notexists" => "There is no article '%s' in Wikipedia (%s)",
	"NoTrans" => "There aren't any languages linked with the article %s."
);

$Charttitle = "distribution of common link usage";
$Tooltip["chart"] = "This chart displays the percentile distribution of the 
	usage of wikilinks in the chosen language.";

$Legend = array(
	"red" => "no article",
	"yellow" => "not linked",
	"green" => "linked"
);

$analysisLink1 = "Link to this";
$analysisLink2 = "analysis";
