<?php
$Headline = "LEA - Link ExtrActor";
$Introduction = "LEA extracts concepts in Wikipedia articles. It presents 
	concepts that are linked to the corresponding article, concepts which are 
	not linked yet and concepts that do not have a corresponding article. 
	Therefore, LEA is useful to find out which concept should be linked and 
	which concept should be added by creating a new article. LEA helps to find 
	out about knowledge diversity and could help improving the fact coverage by 
	pointing to imbalance.";

$Settings = array(
	"Headline" => "Settings",
	"Text" => "The user can insert the title of an article (case sensitive) in a 
		certain language version. "
);

$Results = array(
	"Headline" => "Results",
	"Text" => "
		<p>
			LEA shows the language versions with the most links in a certain 
			article in a chosen language version of Wikipedia. The idea behind 
			LEA (Link ExtrActor) is the interpretation of links in Wikipedia 
			articles as facts. Links that occur in several language versions are 
			likely to represent an entity, due to the fact that articles 
			themselves usually describe entities. Words with merely grammatical 
			functions are ignored. Filters are being applied to abstract concepts 
			like years or names of disciplines like mathematics. The tool takes 
			different language versions into account, therefore the analysis is 
			multilingual. The analysis tries to present some information on the 
			fact coverage of an article in a certain language version by taking 
			a look at all other accesible language versions. It is not necessary 
			to understand the natural language used in the article text. Red links 
			(links to non-existent articles) and redirects are not part of the 
			analysis in the current version.
		</p>
		<p>
			LEA analyses the three largest language versions (measured by the 
			number of the containing internal links) and extracts the set of the 
			matching internal links. This set is compared with the internal links 
			of the requested article. The results are shown in a table and a graph:
		</p>
		<ul>
			<li>
				RED: For these concepts exists no article in the analysed 
				language version.
			</li>
			<li>
				YELLOW: These concepts are not linked yet, although an article 
				exists in that language version.
			</li>
			<li>
				GREEN: These concepts exist and are linked in all analysed 
				language versions.
			</li>
		</ul>"
);
