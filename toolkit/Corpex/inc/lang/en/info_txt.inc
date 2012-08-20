<?php 
$Headline = "Corpex - Corpora Explorer";
$Introduction = "Corpex let you swiftly browse through all the words of Wikipedia.It displays the context of characters and words within the text of different  language versions of Wikipedia. ";

$Settings["Headline"] = "Settings";
$Settings["Text"] = "<p>At first choose the desired language version of Wikipedia. Then you can type the first letters of a word and Corpex shows the first results. At the moment Corpex can handle only lowercase letters. 
</p>
<p>
Corpex is also available as a restful webservice, simply call <code>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y</em></code> with <code><em>XX</em></code> being the Wikipedia language code (see below) and <code><em>Y</em></code> being the starting letter sequence. You will get back a JSON result with the same data that you see on the page.<br/>
The bigrams statistics are available through <code>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y
</em></code> with <code><em>XX</em></code> being two \"+\"-separated words representing the bigram in question, e.g. \"star+wars\". 
</p>";

$Results["Headline"] = "Results";
$Results["Text"] = "<p>When you start typing, the system shows you three statistics in six graphs. These are from left to right in the first row 
</p>
<ol>
<li> the ten most frequent words that start with the typed sequence of letters (as a barcharts and a piechart), 
</li>
<li> the most frequent letter following the already typed sequence of letters (again, as a barchart and a piechart), and 
</li>
<li> both charts in the second row show the most frequend second word of all two-word-terms that start with the typed sequence as first word. 
</li></ol>

<p>In any case three dots (...) mean \"other word/letter\", the dollar sign ($) means \"end of the word/end of sentence\". 
</p>";

$Uses["Headline"] ="Uses";
$Uses["Text"] = "<p>In the current version of the tool Corpex particularly interesting for researchers which  analyze Corpora or do similar things.
Perspectively, the results get more practical use, if you can involve the temporal course in the observations.
For example, to investigate whether a language version tends to a certain recent political orientation. That can be done with identifying the frequency of characteristic words, phrases or expressions over time.
</p><p>Corpex is still very much under development. The currently extracted data is still very noisy, and we are currently working on better extraction and filtering approaches. The source code is fully open source, and all the data is also freely available. Feedback, and especially suggestions for cooperation, is welcome. 
</p>";



$Languages["Headline"] = "Supported languages";
$Languages["Text"] = "<strong>Corpex</strong> as well as the extracted n-grams are currently available in the following languages:
<table class=\"languages\">
<tr><th>Code</th><th>Country</th><th>1-grams</th><th>2-grams</th></tr>
<tr><td>bg</td><td>Bulgarian</td><td><a href=\"downloads/bg_1-grams.csv.zip\">bg_1-grams.csv.zip</a></td><td><a href=\"downloads/bg_2-grams.csv.zip\">bg_2-grams.csv.zip</a></td></tr>
<tr><td>bs</td><td>Bosnian</td><td><a href=\"downloads/bs_1-grams.csv.zip\">bs_1-grams.csv.zip</a></td><td><a href=\"downloads/bs_2-grams.csv.zip\">bs_2-grams.csv.zip</a></td></tr>
<tr><td>cs</td><td>Czech</td><td><a href=\"downloads/cs_1-grams.csv.zip\">cs_1-grams.csv.zip</a></td><td><a href=\"downloads/cs_2-grams.csv.zip\">cs_2-grams.csv.zip</a></td></tr>
<tr><td>de</td><td>German</td><td><a href=\"downloads/de_1-grams.csv.zip\">de_1-grams.csv.zip</a></td><td><a href=\"downloads/de_2-grams.csv.zip\">de_2-grams.csv.zip</a></td></tr>
<tr><td>en</td><td>English</td><td><a href=\"downloads/en_1-grams.csv.zip\">en_1-grams.csv.zip</a></td><td><a href=\"downloads/en_2-grams.csv.zip\">en_2-grams.csv.zip</a></td></tr>
<tr><td>es</td><td>Spanish</td><td><a href=\"downloads/es_1-grams.csv.zip\">es_1-grams.csv.zip</a></td><td><a href=\"downloads/es_2-grams.csv.zip\">es_2-grams.csv.zip</a></td></tr>
<tr><td>fr</td><td>French</td><td><a href=\"downloads/fr_1-grams.csv.zip\">fr_1-grams.csv.zip</a></td><td><a href=\"downloads/fr_2-grams.csv.zip\">fr_2-grams.csv.zip</a></td></tr>
<tr><td>hr</td><td>Croatian</td><td><a href=\"downloads/hr_1-grams.csv.zip\">hr_1-grams.csv.zip</a></td><td><a href=\"downloads/hr_2-grams.csv.zip\">hr_2-grams.csv.zip</a></td></tr>
<tr><td>hu</td><td>Hungarian</td><td><a href=\"downloads/hu_1-grams.csv.zip\">hu_1-grams.csv.zip</a></td><td><a href=\"downloads/hu_2-grams.csv.zip\">hu_2-grams.csv.zip</a></td></tr>
<tr><td>it</td><td>Italian</td><td><a href=\"downloads/it_1-grams.csv.zip\">it_1-grams.csv.zip</a></td><td><a href=\"downloads/it_2-grams.csv.zip\">it_2-grams.csv.zip</a></td></tr>
<tr><td>ro</td><td>Romanian</td><td><a href=\"downloads/ro_1-grams.csv.zip\">ro_1-grams.csv.zip</a></td><td><a href=\"downloads/ro_2-grams.csv.zip\">ro_2-grams.csv.zip</a></td></tr>
<tr><td>sh</td><td>Serbo-Croatian</td><td><a href=\"downloads/sh_1-grams.csv.zip\">sh_1-grams.csv.zip</a></td><td><a href=\"downloads/sh_2-grams.csv.zip\">sh_2-grams.csv.zip</a></td></tr>
<tr><td>sq</td><td>Albanian</td><td><a href=\"downloads/sq_1-grams.csv.zip\">sq_1-grams.csv.zip</a></td><td><a href=\"downloads/sq_2-grams.csv.zip\">sq_2-grams.csv.zip</a></td></tr>
<tr><td>sr</td><td>Serbian</td><td><a href=\"downloads/sr_1-grams.csv.zip\">sr_1-grams.csv.zip</a></td><td><a href=\"downloads/sr_2-grams.csv.zip\">sr_2-grams.csv.zip</a></td></tr>
<tr><td>sv</td><td>Swedish</td><td><a href=\"downloads/sv_1-grams.csv.zip\">sv_1-grams.csv.zip</a></td><td><a href=\"downloads/sv_2-grams.csv.zip\">sv_2-grams.csv.zip</a></td></tr></td></tr>
<tr><td>simple</td><td>Simple English</td><td><a href=\"downloads/simple_1-grams.csv.zip\">simple_1-grams.csv.zip</a></td><td><a href=\"downloads/simple_2-grams.csv.zip\">simple_2-grams.csv.zip</a></td></tr>
<tr><td>brown</td><td><a href=\"http://khnt.aksis.uib.no/icame/manuals/brown/\">Brown Corpus</a></td><td></td><td></td>
</table>
More languages are being prepared.";
?>
