<?php
$strLang = array(
	"dateFormat" => "d.m.Y H:i",
	"asqmHeading" => "Qualitätsübersichtswerkzeug",
	"asqmDesc" => " Der ASQM (Article Statistics and Quality Monitor) bietet 
		einen schnellen Überblick über die Qualität und den Zustand eines 
		Wikipedia-Artikels.<br />
		<br />
		Für den Zugriff auf die Artikelanalysen kann ein Gadget (Helferlein) 
		installiert werden, das neben den gewohnten Links zum Editieren und zur 
		Darstellung der Versionshistorie einen Link zur Analyse anzeigt. ",
	"instHeading" => "Installation",
	"instDesc" => "Das Gadget kann auf der Unterseite 'vector.js' der eigenen 
		Benutzerseite installiert werden. Ist das automatische Login in die 
		Wikipedia aktiviert, kann folgender Link verwendet werden, um die Seite 
		zur Skripteinbindung aufgerufen werden:
		<a href=\"http://de.wikipedia.org/wiki/Spezial:Meine Benutzerseite/common.js\" target=\"_blank\">
			http://de.wikipedia.org/wiki/Spezial:Meine Benutzerseite/common.js
		</a>
		<br />
		Weitere Informationen zur Individualisierung der Benutzeroberfläche in der 
		Wikipedia gibt es auf der 
		<a href=\"http://de.wikipedia.org/wiki/Wikipedia:Technik/Skin\" target=\"_blank\">
			Projektseite
		</a>.<br />
		Zur Benutzung wird folgender Code benötigt:",
	"noticeHeading" => "Hinweis",
	"notice" => "Zur Evaluation der Nutzung enthält der Code die Variable 
		'asqmid'. Der Wert dient lediglich statistischen Zwecken und lässt 
		keinen Rückschluss auf persönliche Angaben wie Benutzernamen zu.",
	"newsfeedHeading" => "IJS Newsfeed",
	"newsfeedDesc" => "Die Ergebnisse, die hier angezeigt werden, stammen aus 
		dem Newsfeed des Josef-Stefan-Instituts in Ljubljana, Slowenien.<br />
		Das Institut ist ein Partner des RENDER-Projekts.",
	"newsfeedTableTitle" => "Titel",
	"newsfeedTableUrl" => "URL",
	"newsfeedTableDate" => "Datum",
	
	# asqm output
	"groupStats" => array(
		"title" => "Statistiken",
		"pageTitle" => "Seitentitel",
		"status" => "Status",
		"firstEdit" => "Angelegt am",
		"recentEdit" => "Letzte Änderung",
		"editedBy" => "von",
		"totalEditors" => "Autoren",
		"references" => "Einzelnachweise",
		"images" => "Mediendateien",
		"visitorsToday" => "Besucher heute",
		"visitors30days" => "Letzte 30 Tage"
	),
	"groupCoverage" => array(
		"title" => "Faktenabdeckung",
		"lea" => "LEA (Link Extractor)",
		"showAnalysis" => "Analyse anzeigen",
	),
	"groupCurrentness" => array(
		"title" => "Aktualität",
		"titleNewsFinder" => "NewsFinder",
		"noNews" => "Keine Nachrichtenartikel gefunden",
		"newsFound" => " Nachrichtenartikel gefunden",
		"showResults" => "Ergebnisse anzeigen",
		"titleChangeDetector" => "Change Detector",
		"cdHit" => "Hohe Aktivität in anderen Sprachen",
	),
/*
		"neutrality" => "Neutralität",
		"editorInteraction" => "Autoreninteraktion",
*/
	"groupEditorInteraction" => array(
		"title" => "Autoreninteraktion",
		"giniScore" => "WikiGini-Score",
	),
	"groupOther" => array(
		"title" => "Weitere Bewertungen",
		"wikibuch" => "Wikibu.ch",
		"lookupAssessment" => "Bewertung ansehen",
	),
	"groupAftV4" => array(
		"title" => "Article Feedback Score",
		"trustworthy" => "Vertrauenswürdig",
		"objective" => "Objektiv",
		"complete" => "Vollständig",
		"wellWritten" => "Gut geschrieben",
	),
	"groupAftV5" => array(
		"title" => "Article Feedback Score",
		"negRating" => "Negative Bewertungen",
	),
);
