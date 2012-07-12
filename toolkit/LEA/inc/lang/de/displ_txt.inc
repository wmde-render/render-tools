<?php
$leaLang = array(
	"headline" => "LEA - Link ExtrActor",
	"description" => "LEA hilft dabei, fehlende Verweise in Wikipedia-
		Artikeln zu finden. Das Tool vergleicht verschiedene Sprachversionen 
		und zeigt fehlende Verweisabdeckung.<br />
		Die Schnittmenge der Verweise aus den drei linkreichsten 
		Sprachversionen wird mit denen der angefragten Sprachversion 
		verglichen.<br /><br />
		Im Beispiel auf der rechten Seite wurde der englischsprachige 
		Artikel über die Stadt Flensburg angefragt. Der Artikel zum Stadtteil 
		Mürwig ist in der englischsprachigen Wikipedia nicht vorhanden. 
		Zum Begriff Venskabsby (Städtepartnerschaft) ist kein Verweis enthalten.",
	"more_info" => "Weitere Informationen",
	"form_text" => "Bitte einen Artikeltitel und die gewünschte Wikipedia Sprachversion angeben:",
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
	"NoTrans" => "Der Artikel %s ist mit keiner anderen Sprache verlinkt."
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