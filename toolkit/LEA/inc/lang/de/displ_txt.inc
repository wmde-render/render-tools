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

// printf ( $_ , $language, $title, $title, $countTrans , $countbiggestTrans)
$Info["langVersions1"] = "Der Artikel <a href=\"http://%s.wikipedia.org/wiki/%s\" target=\"_blank\" >%s</a> existiert in %d weiteren Sprachversionen.";
$Info["langVersions2"] = "Die folgenden %d Sprachversionen enthalten die meisten Verweise zu anderen Wikipedia-Artikeln:";

// printf ( $_ , $language, $title, $title, $language, $countLinks)
$Info["LIlanguages"] = "<a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> ( %s ) mit insgesamt %d verwendeten Verweisen.";
$Info["lang_link_count"] = "%d Verweise";
$Info["intersection"] = "Schnittmenge";
$Info["requested_lang"] = "Angefragte Sprachversion";

// printf ( $_, $language, $countLinks )
$Info["LIsourcelang"] = "('%s' verwendet %d Verweise)";

// printf ($_, $articletitle $language)
$Error["Notexists"] = "Ein Artikel mit dem Titel '%s' existiert nicht in Wikipedia (%s)";

// printf ($_, $articletile)
$Error["NoTrans"] = "Der Artikel %s ist mit keiner anderen Sprache verlinkt.";

$Charttitle = "Verwendung relevanter Links";
$Tooltip["chart"] = "Hier wird die prozentuale Verteilung der Verweise nach ihrer Verwendung in der angegebenen Sprache angezeigt.";


$Legend["red"] = "Kein Artikel";
$Legend["yellow"] = "Nicht verlinkt";
$Legend["green"] = "Artikel verlinkt";

$analysisLink1 = "Link zu dieser";
$analysisLink2 = "Analyse";
