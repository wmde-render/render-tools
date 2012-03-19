<?php
if ($lang == "de"){

$Headline = "LEA - Link ExtrActor";
$Description = "";
$Formtext = "Bitte einen Artikeltitel und die gewünschte Wikipedia Sprachversion angeben.";
$FormTitle = "Titel:";
$FormIn = "in";
$Formbutton = "Übermitteln";

$Statistik_Einleitung = "Der Artikel <a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> hat %d Übersetzungen.</p><p>Die folgenden %d Übersetzungen enthalten die meisten Wikipedia internen Verweise:";
$Listenelement_greatestSprachen = "%s ( %s ) mit insgesamt %d verwendeten Verweisen.";
$Listenelement_Ausgangssprache = "('%s' verwendet %d Verweise)";

$Charttitel = "Verwendung relvanter Links";
$Tooltip["chart"] = "Hier wird die prozentuale Verteilung der Verweise nach ihrer Verwendung in der angegebenen Sprache angezeigt.";


$Legende["red"] = "Kein Artikel";
$Legende["yellow"] = "Nicht verlinkt";
$Legende["green"] = "Artikel verlinkt";
}
else {

$Headline = "LEA - Link ExtrActor";
$Description = "";
$Formtext = "Please enter an article title and choose a language version.";
$FormTitle = "title:";
$FormIn = "in";
$Formbutton = "Submit";


$Statistik_Einleitung = "The article <a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> has %d translations.</p><p>The following %d language versions contain the most internal Wikipedia links:";
$Listenelement_greatestSprachen = "%s ( %s ) contains %d internal links.";
$Listenelement_Ausgangssprache = "('%s' contains %d internal links)";

$Charttitel = "distribution of common link usage";
$Tooltip["chart"] = "This chart displays the percentile distribution of the usage of wikilinks in the choosen language.";


$Legende["red"] = "no article";
$Legende["yellow"] = "not linked";
$Legende["green"] = "linked";

}



?>
