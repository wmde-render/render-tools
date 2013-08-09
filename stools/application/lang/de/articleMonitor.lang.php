<?php
$strLang = array(
	"dateFormat" => "d.m.Y H:i",
	"articleMonitorHeading" => "Artikelmonitor",
	"articleMonitorDesc" => "Der Artikelmonitor bietet einen Überlick über statistische 
		Daten und verschiedene Analysen zu einem Wikipedia-Artikel mit deren 
		Hilfe die Qualität des Artikels eingeschätzt werden kann.
		<br /><br />
		Nach erfolgter Installation erscheint neben dem Reiter zur 
		\"Versionsgeschichte\" ein weiterer mit der Aufschrift \"Artikelmonitor\". Durch 
		Klicken auf diesen Reiter öffnet sich ein Extrafenster, in dem die 
		Statistiken und Analysen zusammengefasst sind.",
	"instHeading" => "Installation",
	"instDesc" => "Das Helferlein kann auf der Unterseite 'vector.js' der eigenen 
		Benutzerseite installiert werden. Ist das automatische Login in die 
		Wikipedia aktiviert, kann folgender Link verwendet werden, um die Seite 
		zur Skripteinbindung aufzurufen:
		<a href=\"https://de.wikipedia.org/wiki/Spezial:Meine Benutzerseite/common.js\" target=\"_blank\">
			https://de.wikipedia.org/wiki/Spezial:Meine Benutzerseite/common.js
		</a>
		<br />
		Weitere Informationen zur Individualisierung der Benutzeroberfläche in der 
		Wikipedia gibt es auf der 
		<a href=\"http://de.wikipedia.org/wiki/Wikipedia:Technik/Skin\" target=\"_blank\">Projektseite</a>.<br /><br /><br />
		Zur Benutzung wird folgender Code benötigt:",
	"noticeHeading" => "Hinweis",
	"notice" => "Zur Evaluation der Nutzung enthält der Code die Variable 
		'asqmid'. Der Wert dient lediglich statistischen Zwecken und lässt 
		keinen Rückschluss auf persönliche Angaben wie Benutzernamen zu.",
	"newsfeedHeading" => "News Finder",
	"newsfeedDesc" => "Die Ergebnisse, die hier angezeigt werden, stammen aus 
		dem Newsfeed des Josef-Stefan-Instituts in Ljubljana, Slowenien.<br />
		Das Institut ist ein Partner des RENDER-Projekts.",
	"newsfeedTableTitle" => "Titel",
	"newsfeedTableUrl" => "URL",
	"newsfeedTableDate" => "Datum",
	
	"articleMonitorTabTitle" => "Artikelmonitor",
	"articleMonitorTooltip" => "Statistiken und weitere Analysen zu diesem Artikel anzeigen",
	
	# articleMonitor output
	# old format - keep for now
	"general" => array(
		"title" => "Statistiken",
		"pageTitle" => "Seitentitel",
		"status" => "Status",
		"firstEdit" => "Angelegt am",
		"recentEdit" => "Letzte Änderung",
		"editedBy" => "von",
		"unregUsersPage" => "https://de.wikipedia.org/wiki/Hilfe:Benutzer#Nicht_angemeldeter_Benutzer",
		"totalEditors" => "Autoren",
		"references" => "Einzelnachweise",
		"images" => "Mediendateien",
		"visitorsToday" => "Besucher heute",
		"visitors30days" => "Letzte 30 Tage",
		"visitorsYesterday" => "Besucher gestern",
		"visitorsLastMonth" => "Besucher im letzten Monat",
	),
	"factCoverage" => array(
		"title" => "Faktenabdeckung",
		"lea" => "LEA (Link Extractor)",
		"showAnalysis" => "Analyse anzeigen",
	),
	"currentness" => array(
		"title" => "Aktualität",
		"titleNewsFinder" => "NewsFinder",
		"noNews" => "Keine Nachrichtenartikel gefunden",
		"newsFound" => " Nachrichtenartikel gefunden",
		"titleChangeDetector" => "Change Detector",
		"cdHit" => "Hohe Aktivität in anderen Sprachen",
	),
/*
		"neutrality" => "Neutralität",
		"editorInteraction" => "Autoreninteraktion",
*/
	"editorInteraction" => array(
		"title" => "Autoreninteraktion",
		"giniScore" => "WikiGini-Score",
	),
	"other" => array(
		"title" => "Weitere Bewertungen",
		"wikibuch" => "Wikibu.ch",
		"lookupAssessment" => "Bewertung ansehen",
	),
	"aft4" => array(
		"title" => "Article Feedback Score",
		"trustworthy" => "Vertrauenswürdig",
		"objective" => "Objektiv",
		"complete" => "Vollständig",
		"wellWritten" => "Gut geschrieben",
	),
	"aft5" => array(
		"title" => "Article Feedback Score",
		"negRating" => "Negative Bewertungen",
	),
	"statistics" => array(
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
		"visitors30days" => "Letzte 30 Tage",
		"visitorsYesterday" => "Besucher gestern",
		"visitorsLastMonth" => "Besucher im letzten Monat",
		"questionnaire" => "Umfrage",
		"questionnaireText1" => "Bitte nimm an unserer ",
		"questionnaireLinkText" => "Umfrage",
		"questionnaireText2" => " teil.",
		"questionnaireLink" => "https://docs.google.com/spreadsheet/viewform?formkey=dDJLN2RsalFvQWx1REhoakluS0tIU3c6MA"
	),
	"analysis" => array(
		"title" => "Analysen",
		# Link Extractor
		"lea" => "Linkvergleich",
		"showAnalysis" => "Verweise mit dem Link Extractor prüfen",
		# News Finder
		"newsFinder" => "Nachrichtenartikel",
		"noNews" => "Keine Artikel im News Finder",
		"newsFound" => " Artikel im News Finder",
		# Change Detector
		"changeDetector" => "Bearbeitungen in anderen Sprachen",
		"cdHit" => "Ergebnis des Change Detector",
		# WikiGini
		"giniScore" => "Autorenverteilung",
		"giniDesc" => "WikiGini-Wert",
		"giniScoreProcessing" => "WikiGini-Werte werden gerade berechnet",
	),
	"assessment" => array(
		"title" => "Bewertungen",
		"wikibuch" => "Wikibu.ch",
		"lookupAssessment" => "Bewertung ansehen",
		# aft 4
		"aft4title" => "Article Feedback Score",
		"trustworthy" => "Vertrauenswürdig",
		"objective" => "Objektiv",
		"complete" => "Vollständig",
		"wellWritten" => "Gut geschrieben",
		# aft 5
		"aft5title" => "Article Feedback Score",
		"negRating" => "Negative Bewertungen",
	),
);
