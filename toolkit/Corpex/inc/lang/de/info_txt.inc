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
$Languages["Text"] = "<p>Corpex ist zurzeit in folgenden Sprachen verfügbar:
</p>
<table>
<tbody><tr><td> Bulgarisch (bg)</td></tr>

<tr><td> Bosnisch (bs)</td></tr>
<tr><td> Tschechisch (cs)</td></tr>
<tr><td> Deutsch (de)</td></tr>
<tr><td> Englisch (en)</td></tr>
<tr><td> Spanisch (es)</td></tr>
<tr><td> Französisch (fr)</td></tr>

<tr><td> Kroatisch (hr)</td></tr>
<tr><td> Ungarisch (hu)</td></tr>
<tr><td> Italienisch (it)</td></tr>
<tr><td> Rumänisch (ro)</td></tr>
<tr><td> Serbisch-Kroatisch (sh)</td></tr>
<tr><td> Albanisch (sq)</td></tr>

<tr><td> Serbisch (sr)</td></tr>
<tr><td> Schwedisch (sv)</td></tr>
<tr><td> Einfaches English (simple)</td></tr>
<tr><td><a href=\"http://khnt.aksis.uib.no/icame/manuals/brown/\">Brown Corpus</a> (brown)
</td></tr></tbody></table>

<p>Weitere Sprachen werden vorbereitet.
</p>";


?>
