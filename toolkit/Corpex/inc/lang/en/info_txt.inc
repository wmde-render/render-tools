<?php 

$Headline = "Corpex — Corpora Explorer";
$Introduction = "Corpex ist ein Korpusanalyse Werkzeug. Als Datenbasis dient die Wikipedia in vielen Sprachen.";

$Text1 = "<strong>Corpex</strong> let's you swiftly browse through all the
	words of Wikipedia. Select your language, and when you start typing, the
	system shows you two statistics in four graphs. These are from left to
	right: 1) the ten most frequent words that start with the typed sequence of
	letters (as a barcharts and a piechart), and 2) the most frequent letter
	following the already typed sequence of letters (again, as a barchart and a
	piechart). The three dots (...) mean \"other word\", the dollar sign ($)
	means \"end of the word\".
	<br/>
	In the second row, the ten most frequent following words of any
	input word are visualized (as a barcharts and a piechart). Here, the three
	dots (...) mean \"other word\", the dollar sign ($) means \"end of
	sentence\".";

$Text2 = "<strong>Corpex</strong> is also available as a restful webservice,
	simply call
	<tt>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y</em></tt>
	with <tt><em>XX</em></tt> being the Wikipedia language code (see below)
	and <tt><em>Y</em></tt> being the starting letter sequence. You will get
	back a JSON result with the same data that you see on the page.
	<br/>
	The bigrams statistics are available through
	<tt>http://km.aifb.kit.edu/sites/corpex/bigrams.php?lang=<em>XX</em>&amp;q=<em>Y</em></tt>
	with <tt><em>Y</em></tt> being two \"+\"-separated words
	representing the bigram in question, e.g. \"star+wars\". ";

$Text3 = "<strong>Corpex</strong> is still very much under development. The
	currently extracted data is still very noisy, and we are currently working
	on better extraction and filtering approaches. The source code is fully
	open source, and all the data is also freely available. Feedback, and
	especially suggestions for cooperation, is welcome.";

$Text4 = "<strong>Corpex</strong> is currently available in the following
	languages:
	German (de),
	English (en), 
	Spanish (es), 
	French (fr), 
	Hungarian (hr), 
	Romanian (ro), 
	Albanian (sq), 
	Bulgarian (bg), 
	Czech (cs),
	Italian (it), 
	Swedish (sv), 
	Serbian (sr), 
	Croatian (hr),
	Serbo-Croatian (sh), 
	Bosnian (bs), 
	and simple English (simple).
	It is further available for the <a href=\"http://khnt.aksis.uib.no/icame/manuals/brown/\">Brown Corpus</a> (brown).
	Further languages are being prepared.";

$Text5 = "<strong>Corpex</strong> is being developed within the EU FP7 project
	<a href=\"http://render-project.eu\">RENDER</a>, which aims at understanding
	diversity on the Web.";

?>
