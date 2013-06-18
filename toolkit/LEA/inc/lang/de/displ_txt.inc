<?php
$leaLang = array(
	"headline" => "Link Extractor (LEA)",
	"description" => "Der Link Extractor geht von der Annahme aus, dass 
		die Vollständigkeit eines Artikels über einen Abgleich der in 
		verschiedenen Sprachversionen enthaltenen Links gemessen werden 
		kann. Er gibt einen Überblick über die Begriffe, die nach seinen 
		Berechnungen in einem Wikipedia-Artikel enthalten sein sollten. 
		Damit gibt der Link Extractor Hinweise darauf, welche Informationen 
		oder Verlinkungen in einem Artikel fehlen. Weiterhin zeigt er 
		Artikel auf, die in einer Sprachversion nicht vorhanden sind, 
		aber in anderen existieren.<br />
		Der Link Extractor ist in den <a href=\"/render/stools/articleMonitor\">Artikelmonitor</a> eingebunden. Im 
		Ergebnisfenster des Artikelmonitors findet sich ein Link zur 
		entsprechenden Analyse.<br /><br />",
	"headline2" => "Ergebnisdarstellung",
	"description2" => "Im Beispiel wurde der englischsprachige Artikel 
		über die Stadt Flensburg untersucht. Der Artikel zum Stadtteil 
		Mürwig ist in der englischsprachigen Wikipedia nicht vorhanden. 
		Zum Begriff \"Thirty Years' War\" (Dreißigjähriger Krieg) gibt es in der 
		englischsprachigen Version einen Artikel, aber er ist nicht 
		verlinkt. Für die Begriffe Angeln und Danfoss gibt es sowohl 
		einen Artikel als auch einen Link.",
	"more_info" => "Weitere Informationen",
	"form_text" => "Bitte einen Artikeltitel und die gewünschte Wikipedia-Sprachversion angeben:",
	"form_title" => "Titel:",
	"form_in" => "in",
	"form_button" => "Übermitteln",
);
$FormText = "Bitte einen Artikeltitel und die gewünschte Wikipedia-Sprachversion angeben:";
$FormTitle = "Titel:";
$FormIn = "in";
$FormButton = "Übermitteln";

$Info = array(
	"langVersions1" => "Der Artikel <a href=\"http://%s.wikipedia.org/wiki/%s\" 
		target=\"_blank\" >%s</a> existiert in %d weiteren Sprachversionen.",
	"langVersions2" => "Die folgenden %d Sprachversionen enthalten die meisten 
		Verweise zu anderen Wikipedia-Artikeln:",
	"LIlanguages" => "<a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> ( %s ) 
		mit insgesamt %d verwendeten Verweisen.",
	"lang_link_count" => "%d Verweise",
	"intersection" => "Schnittmenge",
	"requested_lang" => "Angefragte Sprachversion",
	"LIsourcelang" => "('%s' verwendet %d Verweise)"
);

$Error = array(
	"Notexists" => "Ein Artikel mit dem Titel '%s' existiert nicht in Wikipedia (%s)",
	"NoTrans" => "Der Artikel %s ist mit keiner anderen Sprache verlinkt.",
	"dbError" => "Die Verbindung zum Datenbankserver '%s' kann nicht hergestellt werden."
);

$Charttitle = "Verwendung relevanter Links";
$Tooltip["chart"] = "
	Hier wird die prozentuale Verteilung der Verweise nach ihrer Verwendung in 
	der angegebenen Sprache angezeigt.
";

$Legend = array(
	"red" => "Kein Artikel",
	"yellow" => "Nicht verlinkt",
	"green" => "Artikel verlinkt"
);

$analysisLink1 = "Link zu dieser";
$analysisLink2 = "Analyse";
