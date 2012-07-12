<?php
$Headline = "Change Detector";
$Description = "
	Change Detector zeigt hohe Autorenaktivität in Wikipedia an einem bestimmten 
	Tag an. Damit wird es möglich, vermutlich veraltete Artikel in bestimmten 
	Sprachversionen zu identifizieren. Die Ergebnisse werden übersichtlich als 
	Tabelle dargestellt. Sollten in nur einer oder zwei Sprachversionen keine 
	Änderungen erfolgt sein, in allen anderen betrachteten Sprachversionen jedoch 
	schon, so ist das ein Hinweis auf einen veralteten Artikeltext in den nicht 
	veränderten Sprachversionen.
";

$MoreInfo = "Weitere Informationen";

$errorMessage = "";

$Formtext = "Bitte wählen Sie einen Tag für den der Change Detector suchen soll.";

$Formbutton = "Übermitteln";

$Settings = array(
	"Headline" => "Zusätzliche Einstellungen",
	"Langgroup" => "Sprachgruppe",
	"LanggroupTooltip" => "Entweder werden nur europäische Sprachen untersucht 
		oder alle von Change Detector unterstützten Sprachen.",
	"World" => "Alle Sprachen",
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