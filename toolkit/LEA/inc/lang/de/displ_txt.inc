<?php
$Headline = "LEA - Link ExtrActor";
$Description = "LEA filtert Begriffe aus Wikipedia-Artikeln heraus. Dabei wird zwischen verlinkten Begriffen, nicht verlinkten Begriffen und Begriffen ohne Wikipedia-Artikel unterschieden. Es bietet sich an, nicht verlinkte Artikel zu verlinken und fehlende Artikel anzulegen. Somit kann LEA Mangel an Wissensdiversität entdecken und dabei helfen, diesen in Wikipedia zu beheben.";

$MoreInfo = "Weitere Informationen";

$FormText = "Bitte einen Artikeltitel und die gewünschte Wikipedia Sprachversion angeben:";
$FormTitle = "Titel:";
$FormIn = "in";
$FormButton = "Übermitteln";

// printf ( $_ , $language, $title, $title, $countTrans , $countbiggestTrans)
$Info["Introduction"] = "Der Artikel <a href=\"http://%s.wikipedia.org/wiki/%s\" target=\"_blank\" >%s</a> existiert in %d Sprachversionen.</p><p>Die folgenden %d Sprachversionen enthalten die meisten Wikipedia internen Verweise:";

// printf ( $_ , $language, $title, $title, $language, $countLinks)
$Info["LIlanguages"] = "<a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> ( %s ) mit insgesamt %d verwendeten Verweisen.";

// printf ( $_, $language, $countLinks )
$Info["LIsourcelang"] = "('%s' verwendet %d Verweise)";

// printf ($_, $articletitle $language)
$Error["Notexists"] = "Ein Artikel mit dem Titel existiert %s nicht in Wikipedia (%s)";

// printf ($_, $articletile)
$Error["NoTrans"] = "Der Artikel %s ist mit keiner anderen Sprache verlinkt.";

$Charttitle = "Verwendung relevanter Links";
$Tooltip["chart"] = "Hier wird die prozentuale Verteilung der Verweise nach ihrer Verwendung in der angegebenen Sprache angezeigt.";


$Legend["red"] = "Kein Artikel";
$Legend["yellow"] = "Nicht verlinkt";
$Legend["green"] = "Artikel verlinkt";

?>
