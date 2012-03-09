<?php
$Headline = "Change Detector";
$Description = "Change Detector zeigt hohe Autorenaktivität in Wikipedia an einem bestimmten Tag an. Damit wird es möglich, vermutlich veraltete Artikel in bestimmten Sprachversionen zu identifizieren. Die Ergebnisse werden übersichtlich als Tabelle dargestellt. Sollten in nur einer oder zwei Sprachversionen keine Änderungen erfolgt sein, in allen anderen betrachteten Sprachversionen jedoch schon, so ist das ein Hinweis auf einen veralteten Artikeltext in den nicht veränderten Sprachversionen.";

$MoreInfo = "Weitere Informationen";

$Formtext = "Bitte wählen Sie einen Tag für den der Change Detector suchen soll.";

$Formbutton = "Übermitteln";

$Settings["Headline"] = "Zusätzliche Einstellungen";

$Settings["Langgroup"] = "Sprachgruppe";
$Settings["LanggroupTooltip"] = "Entweder werden nur europäische Sprachen untersucht oder alle von Change Detector unterstützten Sprachen.";
$Settings["World"] = "Alle Sprachen";
$Settings["EU"] = "Europäische Sprachen";

$Settings["Referenzlang"] = "Referenzsprache";
$Settings["ReferenzlangTooltip"] = "Die Referenzsprache ist wichtig für die Sortierung und für die Anzeige der Artikel.";


$Settings["Half"] = "Mindestens die Hälfte geändert";
$Settings["HalfTooltip"] = "Es werden nur Artikel angezeigt für die sich bei mindestens der Hälfte der Sprachen etwas geändert hat.";

$Settings["Sorting"] = "Sortierung";
$Settings["SortingTooltip"] = "Entweder stehen Artikel ohne Änderung in der Referenzsprache oben, oder Artikel mit möglichst vielen Änderungen über alle Sprachen.";
$Settings["SortingNoChange"] = "Keine Änderung";
$Settings["SortingNews"] = "News";

// printf( $_ , $reflang, $Langgroup)
$Error["NotinGrp"] = "Die Referenzsprache %s ist nicht in der gewählten Sprachgruppe %s.";



$Statistik["Day"] = "Gewählter Tag";
$Statistik["Langgroup"] = "Gewählte Sprachgruppe";

$Filter["m_u"] = "Viele Autoren";
$Filter["m_uTooltip"] = "Nicht nur ein Autor hat die meisten Änderungen vorgenommen";
$Filter["n_b"] = "Keine Bot-Edits";
$Filter["n_bTooltip"] = "Keine automatischen Änderungen von Wikibots";
$Filter["o_m"] = "Keine kleinen Änderungen";
$Filter["o_mTooltip"] = "Kleine Änderungen werden weniger stark gewichtet.";

$Articlename = "Artikelname";

$YesNo["Yes"] = "Ja";
$YesNo["No"] = "Nein";
?>
