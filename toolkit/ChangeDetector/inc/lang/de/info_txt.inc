<?php
$Headline = "Change Detector";
$Introduction = "Change Detector zeigt hohe Autorenaktivität in Wikipedia an einem bestimmten Tag an. Damit wird es möglich, vermutlich veraltete Artikel in bestimmten Sprachversionen zu identifizieren. Die Ergebnisse werden übersichtlich als Tabelle dargestellt. Sollten in nur einer oder zwei Sprachversionen keine Änderungen erfolgt sein, in allen anderen betrachteten Sprachversionen jedoch schon, so ist das ein Hinweis auf einen veralteten Artikeltext in den nicht veränderten Sprachversionen.";

$Settings["Headline"] = "Einstellungen";
$Settings["Text"] = "<p>Für eine Anwendung des Change Detectors können unterschiedliche Angaben gemacht werden.
</p>
<ul><li> <strong>Datum</strong>: Der Tag für den der Change Detector suchen soll. Voreingestellt ist der gestrige Tag.
</li><li> <strong>Sprachgruppe</strong>: Innerhalb welcher Sprachgruppe werden Änderungen betrachtet. Gewählt werden kann zwischen europäischen Sprachen oder allen dem Change Detector zur Verfügung stehenden Sprachen.  
</li><li> <strong>Referenzsprache</strong>: Nach welcher Sprache werden die Ergebnisse sortiert und die Artikelnamen angezeigt.

</li></ul>
<p>Zusätzlich können weitere Einstellungen gemacht werden um die Ergebnisse zu verändern.
</p>
<ul><li> <strong>Mindestens die Hälfte geändert</strong>: Ist der Haken gesetzt, werden nur die Artikel angezeigt die in mehr als der Hälfte der Sprachen der Sprachgruppe geändert wurden. Wird der Haken nicht gesetzt, dann werden auch Artikel angezeigt die in weniger Sprachen geändert wurden (bis zu einem Minimum von mindestens 3 veränderten Sprachen). 
</li><li> <strong>Sortierung</strong>: Standardmäßig werden die Ergebnisse so sortiert, dass Artikel zuerst angezeigt werden, in denen es in der Referenzsprache keine Änderung stattfand, aber in möglichst vielen anderen Sprachen. Wird die Option NEWS gewählt, dann werden die Artikel mit den meisten Änderungen als erstes gezeigt unabhängig von der Referenzsprache.
</li><li> <strong>Filter</strong>: Drei Filter stehen zur Auswahl, welche zusätzlich zu der reinen Anzahl die Art der Änderungen betrachten. Normalerweise sind alle drei Filter eingeschaltet.
<ul><li> <strong>Viele Autoren</strong>: Stammen die Änderungen an einem Artikel zu mehr als der Hälfte nur von einem Autor, dann wird der Artikel als nicht geändert angesehen.
</li><li> <strong>Keine Bot-Edits</strong>: Änderungen von Wikipedia-Bots werden nicht gewertet.
</li><li> <strong>Keine kleinen Änderungen</strong>: Änderungen, die als <i>Nur Kleinigkeiten wurden geändert</i> markiert wurden, werden weniger stark berücksichtigt.    

</li></ul></ul>";

$Results["Headline"] = "Ergebnisse";
$Results["Text"] = "
<p>Die dargestellte Tabelle zeigt untereinander alle Artikel, die der Algorithmus des Change Detectors entdeckt hat. In den Spalten sind die einzelnen Sprachen der Sprachgruppe aufgelistet, angeführt von der gewählten Referenzsprache.
</p>
<ul><li> Eine grüne Zelle bedeutet, dass in diesem Artikel am gewählten Tag im Vergleich zu den 50 Tagen zuvor besonders viele Änderungen entdeckt wurden.
</li><li> Der Schriftzug <i>no change</i> bedeutet, dass keine oder wenige Änderungen an dem Artikel vorgenommen wurden, oder dass in den 50 Tagen zuvor häufiger und mehr an diesem Artikel geändert wurde.

</li><li> Zellen mit dem Schriftzug <i>no article</i> bedeuten, dass in dieser Sprache kein Artikel zu dem gefundenen Artikel in anderen Sprachen vorhanden ist 
</li></ul>";

$Uses["Headline"] ="Nutzen";
$Uses["Text"] = "Der Change Detector zeigt für die gewählte Referenzsprache an, ob ein Artikel aus dieser Sprache vielleicht einer Aktualisierung bedarf. Um die genaue Änderung verfolgen zu können, ist zu empfehlen die Versionsgeschichte eines geänderten Artikels für den gewählten Tag bis zum nächsten Tag zu betrachten. ";





$Languages["Headline"] = "Unterstützte Sprachversionen";
$Languages["Text"] = "<p>Change Detector kann Wikipedia in folgenden Sprachen analysieren:
</p>
<ul><li> Englisch (en)
</li><li> Niederländisch (nl)
</li><li> Französisch (fr)
</li><li> Deutsch (de)
</li><li> Italienisch (it)
</li><li> Portugiesisch (pt)
</li><li> Polnisch (pl)
</li><li> Russisch (ru)
</li><li> Spanisch (es)
</li><li> Schwedisch (sv)
</li><li> Chinesisch (zh) (nur Welt)
</li><li> Japanisch (ja) (nur Welt)
</li></ul>";




$Lizenz = "<h2>Lizenz</h2>
<p>ChangeDetector wird unter der <a href=\"http://de.wikipedia.org/wiki/BSD-Lizenz\">BSD-Lizenz</a> veröffentlicht.</p>

 <code>
  <p>Copyright (c) 2012, Wikimedia Deutschland (Anselm Metzger)
  All rights reserved.</p>
 
  </p>Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:</p>
  <ul>
      <li>Redistributions of source code must retain the above copyright
        notice, this list of conditions and the following disclaimer. </li>
      <li>Redistributions in binary form must reproduce the above copyright
        notice, this list of conditions and the following disclaimer in the
        documentation and/or other materials provided with the distribution. </li>
      <li>Neither the name of Wikimedia Deutschland nor the
        names of its contributors may be used to endorse or promote products
        derived from this software without specific prior written permission. </li>
  </ul>
 
  <p>THIS SOFTWARE IS PROVIDED BY WIKIMEDIA DEUTSCHLAND ''AS IS'' AND ANY
  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL WIKIMEDIA DEUTSCHLAND BE LIABLE FOR ANY
  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.</p></code>

<h2>Disclaimer</h2>

  <code><p>NOTE: This software is not released as a product. It was written primarily for
  Wikimedia Deutschland's own use, and is made public as is, in the hope it may
  be useful. Wikimedia Deutschland may at any time discontinue developing or
  supporting this software. There is no guarantee any new versions or even fixes
  for security issues will be released.</p></code>";

?>
