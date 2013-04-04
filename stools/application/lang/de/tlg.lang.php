<?php
$strLang = array(
	"topHeading" => "Artikellisten-Generator",
	"descHeading" => "Sprache, Kategorien und Suchtiefe",
	"descLanguage" => "Sprache",
	"descLanguageDesc" => "Kürzel der Sprachversion",
	"descCategories" => "Kategorien",
	"descCategoriesDesc" => "Eine mit Semikola getrennte Liste von Kategorienamen. 
		Ein vorangestelltes Plus-Zeichen bildet eine Schnittmenge, ein Minus-Zeichen 
		schließt Artikel der Kategorie aus.<br /><br />
		Beispiel: Um die Schnittmenge aus Artikeln in den Kategorien \"Software\" 
		und \"Newsreader\" zu bilden und dabei die Artikel in der Kategorie 
		\"Windows-Software\" auszuschließen, wird in das Kategoriefeld <em>\"Software; 
		+Newsreader; -Windows-Software\"</em> eingegeben.<br /><br />
		Alternativ können auch Artikel geprüft werden, die auf einer Beobachtungsliste 
		hinterlegt sind. Die Syntax lautet 'wl:Benutzername,TOKEN', das Token muss
		zunächst in den Wikipedia-Benutzereinstellungen definiert werden.",
	"descDepth" => "Tiefe",
	"descDepthDesc" => "Gibt die Rekursionstiefe an, mit der in der Kategoriestruktur 
		gesucht werden soll. So findet Suchtiefe 1 alle Seiten, die direkt in der 
		angegebenen Kategorie aufgezählt sind. Tiefe 2 durchsucht zusätzlich alle 
		Unterkategorien, Tiefe 3 alle Unterkategorien der Unterkategorien, und 
		so weiter.<br /><br />
		Man sollte erwarten, dass die Anzahl der gefundenen Seiten näherungsweise 
		exponentiell mit der Suchtiefe wächst. Am Beispiel Biologie:
		<ul><li>Suchtiefe 1: 120 Seiten</li>
		<li>Suchtiefe 2: 3.468 Seiten</li>
		<li>Suchtiefe 3: 14.184 Seiten</li></ul>
		Die Anzahl der Seiten, die auf Mängel getestet werden sollen, beeinflusst 
		natürlich auch die Ausführungszeit. Die Suchtiefe sollte daher nicht zu 
		groß gewählt werden.<br /><br />
		Die Suchtiefe wird auf alle angegebenen Kategorien angewendet, 
		einschließlich der Kategorien für Schnittmengenbildung (+) und 
		Ausschluss (-).",
	"descFormat" => "Format",
	"descOutput" => "Ausgabeoptionen",
	"descOutputDesc" => "<strong>Format</strong><br />
		Die Ergebnisse können entweder als HTML-Tabelle oder als WikiText 
		ausgegeben werden.<br /><br />
		<strong>per E-Mail</strong><br />
		Die Ergebnisse können auch per E-Mail gesendet werden. Diese Option 
		erfordert die Angabe einer E-Mail-Adresse. Ein Formularfeld für die 
		E-Mail-Adresse wird zusätzlich angezeigt, wenn diese Option ausgewählt 
		wird.",
	"labelAddressCb" => "per E-Mail",
	"labelAddress" => "E-Mail-Adresse",
	"labelSearch" => "Suchen",
	"formHeading" => "Filter auswählen",
	"filterGeneral" => "Allgemein",
	"tblHeadFlaw" => "Mangel",
	"tblHeadTitle" => "Seitentitel",
	"dlgErrorTitle" => "Fehler",
	"dlgErrorHeading" => "Während der Verarbeitung der Anfrage ist ein Fehler aufgetreten.",
	"dlgErrorReport" => "Soll diese Fehlermeldung gesendet werden?",
	"statusTitle" => "Anfrage wird verarbeitet",
	"reqSuccess" => "Anfrage erfolgreich",
	"reqSuccessMsg" => "Die Liste wird nach Fertigstellung an die angegebene E-Mail-Adresse versendet.",
	"tableHeadFlaw" => "Filter",
	"tableHeadPage" => "Seitentitel",
	"errNoFilter" => "Kein Filter ausgewählt",
	"markedAsHidden" => "Die Artikelrevision wird bei künftigen Anfragen nicht mehr angezeigt.",
	"unmarkedAsHidden" => "Die Artikelrevision wird bei künftigen Anfragen wieder angezeigt.",
	"descHide" => "Klicken, um dieses Filterergebnis bei künftigen Anfragen auszublenden",
	"descUnhide" => "Klicken, um dieses Filterergebnis bei künftigen Anfragen wieder einzublenden.",
	"alsoShowHidden" => "auch ausgeblendete Ergebnisse anzeigen",
	"linkToRequest" => "Link zu dieser Anfrage",
);
