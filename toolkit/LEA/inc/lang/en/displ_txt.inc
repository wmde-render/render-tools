<?php
$leaLang = array(
	"headline" => "Link Extractor",
	"description" => "The main idea of the Link Extractor is to measure 
		the completeness of articles by comparing the links contained in 
		different language versions. From its analysis the Link Extractor 
		deduces which concepts should be covered by a Wikipedia article. 
		By that, it indicates which information and links may be missing 
		in an article. Moreover the Link Extractor shows articles that 
		do not exist in a language version while in others they do.<br /><br />
		You can access the Link Extractor from the display window of the 
		<a href=\"http://toolserver.org/~render/stools/asqm\" target=\"_blank\">Article Monitor.</a>",
	"headline2" => "Example",
	"description2" => "The example shows the result for the English 
		article about the city of Flensburg. For the district of \"Mørvig\" 
		there is no article available in the English Wikipedia. The 
		article about \"Thirty Years' War\" exists but it is not linked 
		from the article about Flensburg at all. 
		For the terms \"Angeln\" and \"Danfoss\" the 
		article exists and is linked from the requested article.",
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
	"NoTrans" => "There aren't any languages linked with the article %s.",
	"dbError" => "The connection to the database server '%s' could not be established."
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
