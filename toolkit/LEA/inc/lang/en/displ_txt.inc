<?php
$Headline = "LEA - Link ExtrActor";
$Description = "LEA extracts concepts in Wikipedia articles. It presents concepts that are linked to the corresponding article, concepts which are not linked yet and concepts that do not have a corresponding article. Therefore, LEA is useful to find out which concept should be linked and which concept should be added by creating a new article. LEA helps to find out about knowledge diversity and could help improving the fact coverage by pointing to imbalance.";

$MoreInfo = "More Information";

$FormText = "Please enter an article title and choose a language version.";
$FormTitle = "title:";
$FormIn = "in";
$FormButton = "Submit";

// printf ( $_ , $language, $title, $title, $countTrans , $countbiggestTrans)
$Info["Introduction"] = "The article <a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> exists in %d language versions.</p><p>The following %d language versions contain the most internal Wikipedia links:";

// printf ( $_ , $language, $title, $title, $language, $countLinks)
$Info["LIlanguages"] = "<a href=\"http://%s.wikipedia.org/wiki/%s\" >%s</a> ( %s ) contains %d internal links.";

// printf ( $_, $language, $countLinks )
$Info["LIsourcelang"] = "('%s' contains %d internal links)";

// printf ($_, $articletitle $language)
$Error["Notexists"] = "There is no article %s in Wikipedia (%s)";

// printf ($_, $articletile)
$Error["NoTrans"] = "There aren't any languages linked with the article %s.";

$Charttitle = "distribution of common link usage";
$Tooltip["chart"] = "This chart displays the percentile distribution of the usage of wikilinks in the chosen language.";


$Legend["red"] = "no article";
$Legend["yellow"] = "not linked";
$Legend["green"] = "linked";

?>
