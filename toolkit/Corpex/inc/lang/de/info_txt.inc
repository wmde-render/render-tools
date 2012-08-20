<?php 
$Headline = "Corpex - Corpora Explorer";
$Introduction = "Corpex ermöglicht das schnelle Durchsuchen aller Wörter der Wikipedia. Es zeigt die Umgebung von Buchstabenketten und Wörtern innerhalb des Textes einer Wikipedia Sprachversion. ";

$Settings["Headline"] = "Einstellungen";
$Settings["Text"] = "<p>Um Corpex zu nutzen muss zuerst eine Wikipedia-Sprachversion gewählt werden. Danach kann direkt begonnen werden einen Wortanfang einzutippen. Corpex beherrscht derzeit nur klein geschriebene Wörter.
</p>
<p>
Corpex ist auch als RESTful Webservice verfügbar, einfach
<code>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y</em></code> mit <code><em>XX</em></code> als Wikipedia Sprachcode (siehe unten) und
<code><em>Y</em></code> als Start-Buchstabensequenz aufrufen. Das Ergebnis wird im
JSON-Format zurückgegeben, mit den selben Daten, die Du auf der Seite siehst.
<br/>
Die Bigramm-Statistiken sind ebenfalls verfügbar unter
<code>http://km.aifb.kit.edu/sites/corpex/corpex.php?lang=<em>XX</em>&amp;q=<em>Y
</em></code> mit <code><em>XX</em></code> als zwei \"+\"-getrennte Wörter, die das
angefragte Bigramm darstellen, z.B. \"star+wars\".

</p>";

$Results["Headline"] = "Ergebnisse";
$Results["Text"] = "<p>Mit den ersten eingetippten Buchstaben erscheinen drei
Statistiken in sechs Graphen. Diese sind von links nach rechts in der ersten
Reihe
</p>
<ol>
<li> die zehn am häufigsten vorkommenden Wörter, die mit der eingegebenen
Buchstabensequenz anfangen (als Balken- und Kuchendiagramm),
</li>
<li> die häufigsten vorkommenden Buchstaben, die der eingegebenen
Buchstabensequenz folgen und
</li>
<li> in der zweiten Reihe die am häufigsten vorkommenden zweiten Wörter aller
Zwei-Wort-Terme, die mit der eingegebenen Buchstabensequenz als erstes Wort
starten.
</li></ol>

<p>In allen Fällen bedeuten drei Punkte (...) ein \"anderes Wort/anderen
Buchstaben\", das Dollarzeichen ($) bedeutet \"Wortende/Satzende\".
</p>";

$Uses["Headline"] ="Nutzen";
$Uses["Text"] = "<p>In der gegenwärtigen Version ist das Corpex Tool vor allem für Wissenschaftler interessant, die Corpora-Analysen oder ähnliches durchführen.
Perspektivisch werden die Corpex-Ergebnisse besonders interessant bei Betrachtungen und Untersuchungen, die den zeitlichen Verlauf miteinbeziehen.
Beispielsweise kann untersucht werden, ob eine Sprachversion zu einer bestimmten politschen Ausrichtung tendiert. Diese kann durch die Identifizierung und Bestimmung der Häufigkeit charakteristischer Worte, Wendungen oder Ausdrücke ermittelt werden.
</p><p>Corpex ist immer noch stark in der Entwicklung. Die aktuell extrahierten Daten sind noch sehr verrauscht und wir arbeiten zurzeit an besseren Extraktions- und Filteransätzen. Der Quellcode ist komplett Open Source und die Daten sind frei verfügbar. Feedback und Anregungen zur Zusammenarbeit sind willkommen. 
</p>";





$Languages["Headline"] = "Unterstützte Sprachversionen";
$Languages["Text"] = "<p><strong>Corpex</strong> als auch die extrahierten n-Grame sind zurzeit in folgenden Sprachen verfügbar:
<table class=languages>
<tr><th>Code</th><th>Land</th><th>1-Grame</th><th>2-Grame</th></tr>
<tr><td>bg</td><td>Bulgarisch</td><td><a href=downloads/bg_1-grams.csv.zip>bg_1-grams.csv.zip</a></td><td><a href=downloads/bg_2-grams.csv.zip>bg_2-grams.csv.zip</a></td></tr>
<tr><td>bs</td><td>Bosnisch</td><td><a href=downloads/bs_1-grams.csv.zip>bs_1-grams.csv.zip</a></td><td><a href=downloads/bs_2-grams.csv.zip>bs_2-grams.csv.zip</a></td></tr>
<tr><td>cs</td><td>Tschechisch</td><td><a href=downloads/cs_1-grams.csv.zip>cs_1-grams.csv.zip</a></td><td><a href=downloads/cs_2-grams.csv.zip>cs_2-grams.csv.zip</a></td></tr>
<tr><td>de</td><td>Deutsch</td><td><a href=downloads/de_1-grams.csv.zip>de_1-grams.csv.zip</a></td><td><a href=downloads/de_2-grams.csv.zip>de_2-grams.csv.zip</a></td></tr>
<tr><td>en</td><td>Englisch</td><td><a href=downloads/en_1-grams.csv.zip>en_1-grams.csv.zip</a></td><td><a href=downloads/en_2-grams.csv.zip>en_2-grams.csv.zip</a></td></tr>
<tr><td>es</td><td>Spanisch</td><td><a href=downloads/es_1-grams.csv.zip>es_1-grams.csv.zip</a></td><td><a href=downloads/es_2-grams.csv.zip>es_2-grams.csv.zip</a></td></tr>
<tr><td>fr</td><td>Französisch</td><td><a href=downloads/fr_1-grams.csv.zip>fr_1-grams.csv.zip</a></td><td><a href=downloads/fr_2-grams.csv.zip>fr_2-grams.csv.zip</a></td></tr>
<tr><td>hr</td><td>Kroatisch</td><td><a href=downloads/hr_1-grams.csv.zip>hr_1-grams.csv.zip</a></td><td><a href=downloads/hr_2-grams.csv.zip>hr_2-grams.csv.zip</a></td></tr>
<tr><td>hu</td><td>Ungarisch</td><td><a href=downloads/hu_1-grams.csv.zip>hu_1-grams.csv.zip</a></td><td><a href=downloads/hu_2-grams.csv.zip>hu_2-grams.csv.zip</a></td></tr>
<tr><td>it</td><td>Italienisch</td><td><a href=downloads/it_1-grams.csv.zip>it_1-grams.csv.zip</a></td><td><a href=downloads/it_2-grams.csv.zip>it_2-grams.csv.zip</a></td></tr>
<tr><td>ro</td><td>Rumänisch</td><td><a href=downloads/ro_1-grams.csv.zip>ro_1-grams.csv.zip</a></td><td><a href=downloads/ro_2-grams.csv.zip>ro_2-grams.csv.zip</a></td></tr>
<tr><td>sh</td><td>Serbisch-Kroatisch</td><td><a href=downloads/sh_1-grams.csv.zip>sh_1-grams.csv.zip</a></td><td><a href=downloads/sh_2-grams.csv.zip>sh_2-grams.csv.zip</a></td></tr>
<tr><td>sq</td><td>Albanisch</td><td><a href=downloads/sq_1-grams.csv.zip>sq_1-grams.csv.zip</a></td><td><a href=downloads/sq_2-grams.csv.zip>sq_2-grams.csv.zip</a></td></tr>
<tr><td>sr</td><td>Serbisch</td><td><a href=downloads/sr_1-grams.csv.zip>sr_1-grams.csv.zip</a></td><td><a href=downloads/sr_2-grams.csv.zip>sr_2-grams.csv.zip</a></td></tr>
<tr><td>sv</td><td>Schwedisch</td><td><a href=downloads/sv_1-grams.csv.zip>sv_1-grams.csv.zip</a></td><td><a href=downloads/sv_2-grams.csv.zip>sv_2-grams.csv.zip</a></td></tr></td></tr>
<tr><td>simple</td><td>Einfaches English</td><td><a href=downloads/simple_1-grams.csv.zip>simple_1-grams.csv.zip</a></td><td><a href=downloads/simple_2-grams.csv.zip>simple_2-grams.csv.zip</a></td></tr>
<tr><td>brown</td><td><a href=http://khnt.aksis.uib.no/icame/manuals/brown/>Brown Corpus</a></td><td></td><td></td>
</table>
Weitere Sprachen werden vorbereitet.</p>";


?>
