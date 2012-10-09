<?php
$strLang = array(
	"topHeading" => "Task List Generator",
	"descHeading" => "Language, Categories and Search Depth",
	"descLanguage" => "Language",
	"descLanguageDesc" => "Two character language code (currently only articles
		in the German language version can be searched.)",
	"descCategories" => "Categories",
	"descCategoriesDesc" => "A list of category names separated by semicolons. 
		Prepending a plus sign generates an intersection, prefixing the minus sign 
		excludes the elements of the category.<br /><br />
		Example: To generate the intersection of the categories \"Software\" and 
		\"Newsreader\" while excluding articles within the category \"Windows-Software\" 
		enter <em>\"Software; +Newsreader; -Windows-Software\"</em> into the
		category field.",
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
	"dlgErrorReport" => "Do you want to report this error?"
);
