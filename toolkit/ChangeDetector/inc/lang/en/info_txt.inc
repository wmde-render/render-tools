<?php
$Headline = "Change Detector";
$Introduction = "
	Change Detector shows high editing activity in Wikipedia on a certain day 
	and compares Wikipedias in different language versions. It enables the user 
	to identify outdated articles in certain languages. The results are presented 
	as a table. If a certain article was modified in almost every language 
	version, but one or two languages were left out, you would be advised to have 
	a closer look at the article. It could be outdated.
";

$Settings["Headline"] = "Settings";
$Settings["Text"] = "
	<p>To start the Change Detector you have to enter some information.</p>
	<ul>
		<li>
			<strong>Date</strong>: Select a day for the Change Detector search. 
			Yesterday is set as default.
		</li>
		<li>
			<strong>Language group</strong>: For which group of languages you 
			want to detect changes. You can choose between european languages 
			only and all languages in the Change Detector.
		</li>
		<li>
			<strong>Reference language</strong>: Choose for which language the 
			results are sorted and the display language of the articles found.
		</li>
	</ul>
	<p>In addition, further adjustments can be set to modify the results.</p>
	<ul>
		<li>
			<strong>More than a half changes</strong>: If checked only articles 
			are shown for which more than a half of the languages of the language 
			group was changed. If not checked,  articles are shown with changes 
			for less languages in addition (to a minimum of 3 changed languages).
		</li>
		<li>
			<strong>Sorting</strong>: The results are sorted by default, with 
			articles at first position for which the reference language is not 
			changed, but changes in a maximum of the other languages appears. 
			The option NEWS  sorts  the result by the maximum of changes  without 
			considering for the reference language.
		</li>
		<li>
			<strong>Filter</strong>: To take not only the bare number of changes 
			into account, you can select three filter. All are checked by default.
			<ul>
				<li>
					<strong>Many editors</strong>: Are more than a half of the 
					changes from only one editor, then is the article marked as 
					not changed.
				</li>
				<li>
					<strong>No bot edits</strong>: The edits from Wikipedia-Bots 
					are not counted.
				</li>
				<li>
					<strong>Only major edits</strong>: Edits, that are marked as 
					minor, are weighted lower. 
				</li>
			</ul>
		</li>
	</ul>
";

$Results["Headline"] = "Results";
$Results["Text"] = "
	<p>
		The table shows all article found by the Change Detector in the rows. 
		The columns are headed by the languages with the reference language in 
		the first column.
	</p>
	<ul>
		<li>
			a green cell means that this article in this language are notably 
			changed in comparison with the last 50 days
		</li>
		<li>
			the red <i>no change</i> means that no or less changes were found, 
			or that in the last 50 days more changes are made in this article
		</li>
		<li>
			cells with <i>no article</i> means, that there is no accordant 
			article in this language
		</li>
	</ul>
";

$Uses["Headline"] ="Uses";
$Uses["Text"] = "
	The Change Detector shows for the reference language, if an article probably 
	needs an update. If you want to find out the changes for an special article, 
	view the history of a changed language version of that article. Select and 
	compare the first revisions of the chosen day and the next day.
";

$Languages["Headline"] = "Supported languages";
$Languages["Text"] = "
	<p>Change Detector analyses Wikipedia in the following languages:</p>
	<ul>
		<li> English (en)</li>
		<li> French (fr)</li>
		<li> German (de)</li>
		<li> Portuguese (pt)</li>
		<li> Italian (it)</li>
		<li> Russian (ru)</li>
		<li> Dutch (nl)</li>
		<li> Polish (pl)</li>
		<li> Swedish (sv)</li>
		<li> Spanish (es)</li>
		<li> Chinese (zh) (World only)</li>
		<li> Japanese (ja) (World only)</li>
	</ul>
";
