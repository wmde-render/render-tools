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
