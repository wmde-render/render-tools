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

$Uses = array(
	"Headline" => "Uses",
	"Text" => "LEA provides an overview of concepts that should definitely be 
		included in an article. It offers hints about probably missing 
		information or links in a requested article. The user gets further 
		advices about articles which do not exist so far. Although, they exist 
		in other Wikipedia language versions and seems to be important for that 
		topic."
);

$Lizenz = "
	<h2>Licensing</h2>
	<p>
		LEA is distributed under <a href=\"http://en.wikipedia.org/wiki/BSD_licenses\">BSD license</a>.
	</p>
	<code>
		<p>
			Copyright (c) 2012, Wikimedia Deutschland (Anselm Metzger)
			All rights reserved.
		</p>
		<p>
			Redistribution and use in source and binary forms, with or without
			modification, are permitted provided that the following conditions 
			are met:
		</p>
		<ul>
			<li>
				Redistributions of source code must retain the above copyright
				notice, this list of conditions and the following disclaimer.
			</li>
			<li>
				Redistributions in binary form must reproduce the above copyright
				notice, this list of conditions and the following disclaimer in 
				the documentation and/or other materials provided with the 
				distribution.
			</li>
			<li>
				Neither the name of Wikimedia Deutschland nor the names of its 
				contributors may be used to endorse or promote products derived 
				from this software without specific prior written permission.
			</li>
		</ul>
		<p>
			THIS SOFTWARE IS PROVIDED BY WIKIMEDIA DEUTSCHLAND ''AS IS'' AND ANY
			EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
			IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
			PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL WIKIMEDIA DEUTSCHLAND BE 
			LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
			CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
			SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR 
			BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
			WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
			OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN 
			IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
		</p>
	</code>

	<h2>Disclaimer</h2>
	<code>
		<p>
			NOTE: This software is not released as a product. It was written 
			primarily for Wikimedia Deutschland's own use, and is made public as 
			is, in the hope it may be useful. Wikimedia Deutschland may at any 
			time discontinue developing or supporting this software. There is no 
			guarantee any new versions or even fixes for security issues will be 
			released.
		</p>
	</code>
";