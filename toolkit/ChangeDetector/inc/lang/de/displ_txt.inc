<?php
$Headline = "Change Detector";
$Description = "Der Change Detector untersucht die Aktualität von 
	Wikipedia-Artikeln durch einen Vergleich der Autorenaktivität in 
	verschiedenen Sprachversionen. Dabei sucht der Change Detector nach 
	Artikeln, in denen an einem Tag in vielen Sprachen Veränderungen 
	vorgenommen wurden. Ist dies der Fall, so ist das ein Hinweis darauf, 
	dass der Artikel in den Sprachversionen, die nicht überarbeitet wurden, 
	veraltet sein könnte. Der Change Detector weist auch auf Artikel hin, 
	die in einer oder wenigen Sprache(n) fehlen, in anderen Sprachversionen 
	aber existieren.
	<br /><br />
	Der Change Detector ist in den <a href=\"http://toolserver.org/~render/stools/tlg\">Artikellistengenerator</a> integriert, 
	wodurch es möglich ist, das Suchergegbnis auf bestimmte Kategorien 
	einzugrenzen.  Außerdem ist der Change Detector in den Artikelmonitor 
	eingebunden. Dort findet sich ein Link zur entsprechenden Analyse.";
$Headline2 = "<h2>Ergebnisdarstellung</h2>";
$Description2 = "Das Ergebnis der Suche wird in einer Tabelle angezeigt. Jede Zeile 
	der Tabelle bezieht sich auf einen Artikel, den der Change Detector 
	entdeckt hat. In der ersten Spalte ist die gewählte Referenzsprache 
	dargestellt. In den restlichen Spalten folgen die Sprachen der 
	gewählten Sprachgruppe.
	<br /><br />
	Eine grüne Zelle mit der Beschriftung „changed“ bedeutet, dass in 
	diesem Artikel am gewählten Tag im Vergleich zu den 50 Tagen zuvor 
	besonders viele Änderungen entdeckt wurden. Der rote Schriftzug „no 
	change“ bedeutet, dass an dem Artikel keine oder weniger Änderungen 
	vorgenommen wurden, als in den 50 vorangegangenen Tagen. „No article“ 
	bedeutet, dass in dieser Sprache kein Artikel gefunden wurde, obwohl 
	in anderen Sprachversionen ein Artikel zu dem Begriff existiert.";

$MoreInfo = "Weitere Informationen";

$errorMessage = "";

$Formtext = "Bitte wählen Sie einen Tag für den der Change Detector suchen soll.";

$Formbutton = "Übermitteln";

$Settings = array(
	"Headline" => "Zusätzliche Einstellungen",
	"Langgroup" => "Sprachgruppe",
	"LanggroupTooltip" => "Entweder werden nur europäische Sprachen untersucht 
		oder alle von Change Detector unterstützten Sprachen.",
	"World" => "Alle unterstützten Sprachen",
	"EU" => "Europäische Sprachen",
	"Referenzlang" => "Referenzsprache",
	"ReferenzlangTooltip" => "Die Referenzsprache ist wichtig für die Sortierung 
		und für die Anzeige der Artikel.",
	"Half" => "Mindestens die Hälfte geändert",
	"HalfTooltip" => "Es werden nur Artikel angezeigt für die sich bei mindestens 
		der Hälfte der Sprachen etwas geändert hat.",
	"Sorting" => "Sortierung",
	"SortingTooltip" => "Entweder stehen Artikel ohne Änderung in der 
		Referenzsprache oben, oder Artikel mit möglichst vielen Änderungen über 
		alle Sprachen.",
	"SortingNoChange" => "Keine Änderung",
	"SortingNews" => "News",
);

$Error = array(
	"NotinGrp" => "Die Referenzsprache %s ist nicht in der gewählten Sprachgruppe %s.",
	"NotinDay" => "Aufgrund technischer Probleme gibt es keine Daten für die 
		Referenzsprache '%s' am gewünschten Datum",
);

$Statistik = array(
	"Day" => "Gewählter Tag",
	"Langgroup" => "Gewählte Sprachgruppe"
);

$Filter = array(
	"m_u" => "Viele Autoren",
	"m_uTooltip" => "Nicht nur ein Autor hat die meisten Änderungen vorgenommen",
	"n_b" => "Keine Bot-Edits",
	"n_bTooltip" => "Keine automatischen Änderungen von Wikibots",
	"o_m" => "Keine kleinen Änderungen",
	"o_mTooltip" => "Kleine Änderungen werden weniger stark gewichtet."
);

$Articlename = "Artikelname";

$YesNo = array(
	"Yes" => "Ja",
	"No" => "Nein"
);
