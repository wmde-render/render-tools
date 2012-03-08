<?php
$Headline = "Change Detector";
$Introduction = "Change Detector shows high editing activity in Wikipedia on a certain day and compares Wikipedias in different language versions. It enables the user to identify outdated articles in certain languages. The results are presented as a table. If a certain article was modified in almost every language version, but one or two languages were left out, you would be advised to have a closer look at the article. It could be outdated. ";

$Settings["Headline"] = "Settings";
$Settings["Text"] = "<p>To start the Chnage Detector you have to enter some informations. 
</p>
<ul><li> <strong>Date</strong>: Select a day for the Change Detector search. Yesterday is set as default.
</li><li> <strong>Language group</strong>: For which group of languages you want to detect changes. You can choose between european languages only and all languages in the Change Detector.

</li><li> <strong>Reference language</strong>: Choose for which language the results are sorted and the display language of the articles found.
</li></ul>
<p>In addition, further adjustments can be set to modify the results.
</p>
<ul><li> <strong>More than a half changes</strong>: If checked only articles are shown for which more than a half of the languages of the language group was changed. If not checked,  articles are shown with changes for less languages in addition (to a minimum of 3 changed languages).
</li><li> <strong>Sorting</strong>: The results are sorted by default, with articles at first position for which the reference language is not changed, but changes in a maximum of the other languages appears. The option NEWS  sorts  the result by the maximum of changes  without considering for the reference language. 
</li><li> <strong>Filter</strong>:  To take not only the bare number of changes into account, you can select three filter. All are checked by default.
<ul><li> <strong>Many editors</strong>: Are more than a half of the changes  from only one editor, then is the article marked as not changed.

</li><li> <strong>No bot edits</strong>: The edits from Wikipedia-Bots are not counted.
</li><li> <strong>Only major edits</strong>: Edits, that are marked as minor, are weighted lower. 
</li></ul>
</li></ul>";

$Results["Headline"] = "Results";
$Results["Text"] = "<p>The table shows all article found by the Change Detector in the rows. The columns are headed by the languages with the reference language in the first column.
</p>
<ul><li> a green cell means that this article in this language are notably changed in comparison with the last 50 days
</li><li> the red <i>no change</i> means that no or less changes were found, or that in the last 50 days more changes are made in this article

</li><li> cells with <i>no article</i> means, that there is no 	 accordant article in this language
</li></ul>";

$Uses["Headline"] ="Uses";
$Uses["Text"] = "The Change Detector shows for the reference language, if an article probably needs an update. If you want to find out the changes for an special article, view the history of a changed language version of that article. Select and compare the first revisions of the chosen day and the next day.";

$Languages["Headline"] = "Supported languages";
$Languages["Text"] = "<p>Change Detector analyses Wikipedia in the following languages:
</p>
<ul><li> English (en)
</li><li> French (fr)
</li><li> German (de)
</li><li> Portuguese (pt)
</li><li> Italian (it)
</li><li> Russian (ru)
</li><li> Dutch (nl)
</li><li> Polish (pl) 
</li><li> Swedish (sv)
</li><li> Spanish (es)
</li><li> Chinese (zh) (World only)
</li><li> Japanese (ja) (World only)
</li></ul>
";

$Lizenz = "<h2>Lizenz</h2>
<p>ChangeDetector is distributed under <a href=\"http://en.wikipedia.org/wiki/BSD_licenses\">BSD license</a>.</p>

 <code>
  <p>Copyright (c) 2012, Wikimedia Deutschland (Anselm Metzger)
  All rights reserved.</p>
 
  </p>Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:</p>
  <ul>
      <li>Redistributions of source code must retain the above copyright
        notice, this list of conditions and the following disclaimer. </li>
      <li>Redistributions in binary form must reproduce the above copyright
        notice, this list of conditions and the following disclaimer in the
        documentation and/or other materials provided with the distribution. </li>
      <li>Neither the name of Wikimedia Deutschland nor the
        names of its contributors may be used to endorse or promote products
        derived from this software without specific prior written permission. </li>
  </ul>
 
  <p>THIS SOFTWARE IS PROVIDED BY WIKIMEDIA DEUTSCHLAND ''AS IS'' AND ANY
  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL WIKIMEDIA DEUTSCHLAND BE LIABLE FOR ANY
  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.</p></code>

<h2>Disclaimer</h2>

  <code><p>NOTE: This software is not released as a product. It was written primarily for
  Wikimedia Deutschland's own use, and is made public as is, in the hope it may
  be useful. Wikimedia Deutschland may at any time discontinue developing or
  supporting this software. There is no guarantee any new versions or even fixes
  for security issues will be released.</p></code>";

?>
