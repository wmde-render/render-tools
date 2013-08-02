<?php
$strLang = array(
	"topHeading" => "Article List Generator",
	"algDescription" => "The Article List Generator makes it possible 
		to search categories and compile article lists using different 
		criteria. The query may consist of a one or more categories, the 
		<a href=\"http://en.wikipedia.org/wiki/Intersection_%28set_theory%29\">intersection</a> 
		or the <a href=\"http://en.wikipedia.org/wiki/Complement_%28set_theory%29#Relative_complement\">difference</a> 
		of categories. 
		The user is able to determine the depth of the search and a set 
		of filters shown on the righthand side allows further refinement 
		of the result. The combination of these filters is also possible. 
		The result is displayed in the form of a list.",
	"descHeading" => "Language, Categories and Search Depth",
	"descLanguage" => "Language",
	"descLanguageDesc" => "Two character language code",
	"descCategories" => "Search terms",
	"descCategoriesMore" => "Separate category titles using enter or tab key.",
	"descCategoriesDesc" => "A list of category names separated by semicolons. 
		Prepending a plus sign generates an intersection, prefixing the minus sign 
		excludes the elements of the category.<br /><br />
		Example: To generate the intersection of the categories \"Software\" and 
		\"Newsreader\" while excluding articles within the category \"Windows-Software\" 
		enter <em>\"Software; +Newsreader; -Windows-Software\"</em> into the
		category field.<br /><br />
		Alternatively one can also check articles that have been registered on a 
		watchlist. The syntax to do that is 'wl#Username,TOKEN' the token needs to
		be defined in the Wikipedia user account settings first. If you aren't already
		using a <a href=\"https://tools.wmflabs.org/render/stools/alg\">secure connection</a>, 
		we advise you to use it, since your token will be sent to the server.",
	"descDepth" => "Depth",
	"descDepthDesc" => "Defines the recursion depth which is used to crawl the
		categories. A category depth of '1' will find all pages directly within
		the given category. The depth of '2' will additionally search the 
		subcategories while the depth of '3' searches the subcategories of 
		subcategories as well.<br /><br />
		The number of found article pages are expected to grow exponentially if 
		the recursion depth is raised, e. g. Biology:
		<ul><li>Depth 1: 120 pages</li>
		<li>Depth 2: 3.468 pages</li>
		<li>Depth 3: 14.184 pages</li></ul>
		The number of pages that need to be inspected for having a certain flaw 
		is, however, influencing the execution time. The recursion depth should 
		therefore not be set too high.<br /><br />
		The recursion depth is applied to all given categories, i. e. the
		intersecting categories as well as the excluding categories.",
	"descFormat" => "Format",
	"descOutput" => "Output Options",
	"descOutputDesc" => "<strong>Format</strong><br />
		The results can either be displayed as an HTML-Table or WikiText<br /><br />
		<strong>By email</strong><br />
		The results can also be sent by email, choosing this option requires to 
		provide an email address. A field for entering the email address will 
		appear when activating this option.",
	"descAddress" => "Email Address",
	"descAddressDesc" => "Provide your email address to have a list sent to you 
		rather than being output to the screen.",
	"labelAddressCb" => "by Email",
	"labelAddress" => "Email Address",
	"labelSearch" => "Search",
	"formHeading" => "Select filters",
	"filterGeneral" => "General",
	"tblHeadFlaw" => "Flaw",
	"tblHeadTitle" => "Page Title",
	"dlgErrorTitle" => "Error",
	"dlgErrorHeading" => "An error occurred while executing this request.",
	"dlgErrorReport" => "Do you want to report this error?",
	"statusTitle" => "Processing request",
	"reqSuccess" => "Request successful",
	"reqSuccessMsg" => "The list will be sent after processing is done.",
	"tableHeadFlaw" => "Filter",
	"tableHeadPage" => "Page Title",
	"errNoFilter" => "No filter selected",
	"errNoQuery" => "No query string provided",
	"errNoAddress" => "Results by email require a valid email address.",
	"markedAsHidden" => "The revision will not be displayed in future requests.",
	"unmarkedAsHidden" => "The revision will be displayed again in future requests.",
	"descHide" => "Click to hide this result in future requests.",
	"descUnhide" => "Click to show this result in future requests.",
	"alsoShowHidden" => "also show hidden results",
	"msgGeneralStatus" => "Please wait... Depending on the given parameters the execution can take a while.",
	"linkToRequest" => "Link to this request",
	"resultCount" => "%COUNT% articles were found.",
);
