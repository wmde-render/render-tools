<?php
$leaLang = array(
	"headline" => "Link Extractor",
	"description" => "The main idea of the Link Extractor is to measure 
		the completeness of articles by comparing the links contained in 
		different language versions of that article. Based on this 
		analysis the Link Extractor deduces which concepts and terms 
		should be covered by this Wikipedia article. In doing so, it 
		indicates which information and links may be missing in the 
		article. Moreover, the Link Extractor lists Wikipedia articles 
		that do not exist in a certain language version even though they 
		exist in others.<br /> 
		<br />
		The Link Extractor is incorporated in the Article Monitor. The 
		respective analysis can be accessed via a link in the results 
		window of the <a href=\"/render/stools/articleMonitor\" target=\"_blank\">Article Monitor.</a>",
	"headline2" => "Example",
	"description2" => "In this example, the English Wikipedia article 
		about the city of Flensburg has been analyzed. The article about 
		the district of \"Mürwik\" does not exist in the English Wikipedia. 
		In addition, the term \"Thirty Years' War\" has its own article in the 
		English Wikipedia but it is not from the article about Flensburg. 
		There are articles and links for both terms \"Anglos\" and \"Danfoss.\"",
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
