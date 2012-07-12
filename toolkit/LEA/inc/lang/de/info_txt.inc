<?php
$Headline = "LEA - Link ExtrActor";
$Introduction = "LEA filtert Begriffe aus Wikipedia-Artikeln heraus. Darüber hinaus unterscheidet LEA verlinkte Begriffe, nicht verlinkte Begriffen und Begriffe ohne Wikipedia-Artikel. Es bietet sich an, nicht verlinkte Artikel zu verlinken und fehlende Artikel anzulegen. Somit kann LEA Mangel an Wissensdiversität entdecken und dabei helfen, diesen in Wikipedia zu beheben.";

$Settings = array(
	"Headline" => "Einstellungen",
	"Text" => "Der Nutzer kann den Titel eines Wikipedia-Artikels in einer 
		bestimmten Sprachversion eingeben. Dabei muss die Groß- und 
		Kleinschreibung beachtet werden."
);

$Results = array(
	"Headline" => "Ergebnisse",
	"Text" => "
		<p>
			LEA (Link ExtrActor) interpretiert Links in Wikipedia-Artikeln als 
			Fakten. Ergebnis ist eine Menge von Fakten aus dem Artikeltext. Es 
			wird davon ausgegangen, dass im Artikeltext mehrerer Sprachen 
			verlinkte Lemmata Fakten darstellen, da Artikel in der Regel zur 
			Beschreibung von Entitäten angelegt und verlinkt werden. Wörter, die 
			nur eine grammatische Funktion haben, werden dabei nicht erfasst. 
			Das Tool berücksichtigt unterschiedliche Sprachversionen. Damit wird 
			eine multilinguale Analyse möglich. Ziel ist die Faktenvollständigkeit 
			von Artikeln einer bestimmten Sprache über die Betrachtung aller 
			verfügbaren Sprachversionen nachzuvollziehen. Dazu ist es nicht 
			notwendig die natürlichen Sprachen der analysierten Artikeltexte zu 
			verstehen. Rote Links (Links auf nicht-existierende Artikel) und 
			Redirects werden in der gegenwärtigen Version nicht berücksichtigt.
		</p>
		<p>
			LEA analysiert die drei größten Sprachversionen (gemessen an der 
			Anzahl der enthaltenen Wikilinks). Für diese Artikel wird die Menge 
			der übereinstimmenden Links ermittelt. Für den angefragten Artikel 
			werden die enthaltenen Wikilinks mit dieser extrahierten Menge 
			verglichen. Das Ergebnis wird tabellarisch und in einer Graphik 
			dargestellt:
		</p>
		<ul>
			<li>
				ROT: zu diesem Begriff existiert kein Artikel in der angefragten 
				Sprachversion.
			</li>
			<li>
				GELB: Diese Begriffe sind nicht im Artikel der angefragten 
				Sprachversion enthalten, obwohl diese Artikel vorhanden sind.
			</li>
			<li>
				GRÜN: Diese Begriffe sind ebenfalls im untersuchten Artikel 
				enthalten.
			</li>
		</ul>",
	"Headline" => "Nutzen"
);

$Uses["Text"] = "LEA liefert einen Überblick über diejenigen Begriffe, die 
	unbedingt in einem Artikel enthalten sein sollten. Es gibt Hinweise darüber, 
	welche Informationen oder Verlinkungen möglicherweise in einem Artikel fehlen. 
	Außerdem werden fehlende Artikel in einer Sprachversion aufgezeigt, die in 
	anderen Wikipedias hingegen existieren.";

$Lizenz = "
	<h2>Lizenz</h2>
	<p>
		LEA wird unter der <a href=\"http://de.wikipedia.org/wiki/BSD-Lizenz\">BSD-Lizenz</a> veröffentlicht.
	</p>
	<code>
		<p>
			Copyright (c) 2012, Wikimedia Deutschland (Anselm Metzger) 
			All rights reserved.
		</p>
		<p>
			Redistribution and use in source and binary forms, with or without
			modification, are permitted provided that the following conditions 
			are met:
		</p>
		<ul>
			<li>
				Redistributions of source code must retain the above copyright
				notice, this list of conditions and the following disclaimer.
			</li>
			<li>
				Redistributions in binary form must reproduce the above copyright
				notice, this list of conditions and the following disclaimer in the
				documentation and/or other materials provided with the distribution.
			</li>
			<li>
				Neither the name of Wikimedia Deutschland nor the names of its 
				contributors may be used to endorse or promote products derived 
				from this software without specific prior written permission.
			</li>
		</ul>
		<p>
			THIS SOFTWARE IS PROVIDED BY WIKIMEDIA DEUTSCHLAND ''AS IS'' AND ANY
			EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
			IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
			PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL WIKIMEDIA DEUTSCHLAND BE 
			LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
			CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
			SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR 
			BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
			WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
			OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
			EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
		</p>
	</code>

	<h2>Disclaimer</h2>
	<code>
		<p>
			NOTE: This software is not released as a product. It was written 
			primarily for Wikimedia Deutschland's own use, and is made public as 
			is, in the hope it may be useful. Wikimedia Deutschland may at any 
			time discontinue developing or supporting this software. There is no 
			guarantee any new versions or even fixes for security issues will be 
			released.
		</p>
	</code>
";