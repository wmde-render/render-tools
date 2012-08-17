<?php 
$Headline = "WIKIGINI";
$Introduction = "WIKIGINI visualizes the inequality of authorship in a Wikipedia article: it accurately determines the authors of all the words in every revision of an article and calculates a so-called “Gini-Index” as a measure of inequality. It can thus show if the “ownership” of words in an article shifted from being quite equal (e.g. every editor wrote about the same amount of words) to being unequal (e.g. 2% of editors wrote 90% of the words).";

$Settings["Headline"] = "Settings";
// $Settings["Text"] = "<p>At first choose the desired language version of Wikipedia. Then you can type the first letters of a word and Corpex shows the first results. At the moment Corpex can handle only lowercase letters. 
// </p>
// <p>
// Corpex is also available as a restful webservice, simply call <code>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y</em></code> with <code><em>XX</em></code> being the Wikipedia language code (see below) and <code><em>Y</em></code> being the starting letter sequence. You will get back a JSON result with the same data that you see on the page.<br/>
// The bigrams statistics are available through <code>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y
// </em></code> with <code><em>XX</em></code> being two \"+\"-separated words representing the bigram in question, e.g. \"star+wars\". 
// </p>";
$Settings["Text"] = "";

$Results["Headline"] = "Results";
// $Results["Text"] = "<p>When you start typing, the system shows you three statistics in six graphs. These are from left to right in the first row 
// </p>
// <ol>
// <li> the ten most frequent words that start with the typed sequence of letters (as a barcharts and a piechart), 
// </li>
// <li> the most frequent letter following the already typed sequence of letters (again, as a barchart and a piechart), and 
// </li>
// <li> both charts in the second row show the most frequend second word of all two-word-terms that start with the typed sequence as first word. 
// </li></ol>

// <p>In any case three dots (...) mean \"other word/letter\", the dollar sign ($) means \"end of the word/end of sentence\". 
// </p>";
$Results["Text"] = "";

$Uses["Headline"] ="Uses";
// $Uses["Text"] = "<p>In the current version of the tool Corpex particularly interesting for researchers which  analyze Corpora or do similar things.
// Perspectively, the results get more practical use, if you can involve the temporal course in the observations.
// For example, to investigate whether a language version tends to a certain recent political orientation. That can be done with identifying the frequency of characteristic words, phrases or expressions over time.
// </p><p>Corpex is still very much under development. The currently extracted data is still very noisy, and we are currently working on better extraction and filtering approaches. The source code is fully open source, and all the data is also freely available. Feedback, and especially suggestions for cooperation, is welcome. 
// </p>";
$Uses["Text"] = "";



$Languages["Headline"] = "Supported languages";
// $Languages["Text"] = "<p>Corpex is currently available in the following languages: 
// </p>
// <table>
// <tbody><tr><td> Bulgarian (bg)</td></tr>

// <tr><td> Bosnian (bs)</td></tr>
// <tr><td> Czech (cs)</td></tr>
// <tr><td> German (de)</td></tr>
// <tr><td> English (en)</td></tr>
// <tr><td> Spanish (es)</td></tr>
// <tr><td> French (fr)</td></tr>

// <tr><td> Croatian (hr)</td></tr>
// <tr><td> Hungarian (hu)</td></tr>
// <tr><td> Italian (it)</td></tr>
// <tr><td> Romanian (ro)</td></tr>
// <tr><td> Serbo-Croatian (sh)</td></tr>
// <tr><td> Albanian (sq)</td></tr>

// <tr><td> Serbian (sr)</td></tr>
// <tr><td> Swedish (sv)</td></tr>
// <tr><td> Simple Englisch (simple)</td></tr>
// <tr><td><a href=\"http://khnt.aksis.uib.no/icame/manuals/brown/\">Brown Corpus</a> (brown)
// </td></tr></tbody></table>

// <p>More languages are being prepared.
// </p>";
$Languages["Text"] = "";
?>
