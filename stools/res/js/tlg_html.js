var xhr = new XMLHttpRequest();
var timer;
var arrResults;
var outputFormat;

$(document).ready(function() {
	// set click function for group heading checkbox
	$( '.cbGroup' ).click( function() {
		$( '.' + this.id ).attr( "checked", $( this ).attr( "checked" ) == "checked" ? "checked" : false );
	});
	
	// open dialog on info icon click
	$( '.fldHelp' ).click( function() {
		$( "#dlg-" + this.id ).dialog( "open" );
	});
	
	// define help dialogs
	var helpDialogs = [
		$( "#dlg-helpLanguage" ),
		$( "#dlg-helpCategories" ),
		$( "#dlg-helpOutput" ),
		$( "#dlg-helpDepth" )
	];
	
	// create help dialogs
	$.each( helpDialogs, function( key, value ) {
		value.dialog( {
			autoOpen: false,
			autoResize: true,
			closeOnEscape: true,
			modal: true,
			resizable: false
		});
	});
	
	$('#dlgError').dialog({
		autoOpen: false,
		width: 480,
		autoResize: true,
		closeOnEscape: true,
		modal: true,
		resizable: false
	});
	
	$('#statusDialog').dialog({
		autoOpen: false,
		closeOnEscape: false,
		modal: true,
		resizable: false,
		width: 500,
		height: 140,
		open: function() {
			$( "#tlgProgress" ).progressbar( "value" , 0 );
			$( "#status" ).text(" ");
		},
		beforeClose: function() {
			if(xhr && xhr.readyState != XMLHttpRequest.DONE && xhr.readyState != XMLHttpRequest.UNSENT) {
				xhr.abort();
			}
			window.clearTimeout(timer);
			$( "#resultContainer" ).toggleClass( "box-hidden", true );
		}
	});
	
	$('#actionDialog').dialog({
		autoOpen: false,
		closeOnEscape: true,
		modal: true,
		resizable: false,
		width: 500,
		height: 140
	});
	
	$( "#tlgProgress" ).progressbar({
		value: 0
	});

	$('#btnSearch').click(function() {
		var qString = getQueryString();
		if( qString ) {
			$('#statusDialog').dialog('open');
			queryTlg(qString);
		} else {
			$( "#dlgError > #errMsg" ).html( errNoFilter );
			$('#dlgError').dialog('open');
		}
		return false;
	});
});

var getQueryString = function() {
	var params = {};
	params.flaws = [];

	var fields = $('form :input');
	fields.each(function() {
		switch( this.tagName ) {
			case 'SELECT':
				params[this.name] = $( this ).find(":selected").val();
				break;
			case 'INPUT':
				switch( this.type ) {
					case 'checkbox':
						// ignore all unchecked and group checkboxes
						if ( !$( this ).hasClass( 'cbGroup' ) && this.checked ) {
							// filter checkboxes will be concatenated
							if ( $( this ).hasClass( 'cbFilter' ) ) {
								params.flaws.push( $( this ).val() );
							} else {
								params[this.name] = $( this ).val();
							}
						}
						break;
					default:
						// ignore fields without value
						if( $( this ).val() ) {
							params[this.name] = $( this ).val();
						}
						break;
				}
				break;
		}
	});

	if ( params.flaws.length > 0 ) {
		params.flaws = params.flaws.join(" ");
		return $.param( params );
	}

	return false;
}

function markAsDone(eId, pageId, pageTitle, revision, filter) {
	$(eId).attr("src", basePath + "res/img/tlg-load.gif");
	var req = new XMLHttpRequest();
	var url = tlgServiceUrl + "?action=markasdone";
	url += "&page_id=" + pageId + "&page_latest=" + revision + "&filter_name=" + filter + "&page_title=" + pageTitle;
	if ( $(eId).attr("class") == "hidden" ) {
		url += "&unmark=true";
	}
	req.open('GET', url, true);
	req.onreadystatechange = function() {
		if (req.readyState == XMLHttpRequest.DONE) {
			var respObj = jQuery.parseJSON(req.responseText);
			if ( respObj ) {
				if ( respObj.status ) {
					if( $(eId).attr("class") == "hidden" ) {
						$(eId).attr("src", basePath + "res/img/bulb-show.png");
						$(eId).attr("title", descHide);
						$(eId).attr("class", "");
					} else {
						$(eId).attr("src", basePath + "res/img/bulb-hidden.png");
						$(eId).attr("title", descUnhide);
						$(eId).attr("class", "hidden");
					}
				} else if( respObj.exception ) {
					$( "#dlgError > #errMsg" ).html( "<pre>" + respObj.exception + "</pre>");
					$( "#dlgError" ).dialog( "open" );
				}
			}
		}
	}
	req.send(null);
}

function queryTlg(qryStr) {
	$('#resultContainer').empty();
	arrResults = [];
	var respText = "";
	var error = false;

	var reqParams = 'action=query&format=json&chunked=true' + qryStr;
	xhr.open('POST', tlgServiceUrl, true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.setRequestHeader('Content-length', reqParams.length);
	xhr.send(reqParams);

	timer = window.setInterval(function() {
		if (xhr.readyState == XMLHttpRequest.DONE) {
			$('#statusDialog').dialog('close');

			var arrResponse = xhr.responseText.split("\n");
			var lastStatusSet = false;

			if (outputFormat == 'html') {
				arrResults.push("<table><tr><th>" + tableHeadFlaw + "</th><th>" + tableHeadPage + "</th></tr>");
			} else if(outputFormat == 'wikitext' || $('#address').val() != "") {
				arrResults.push("<div>");
			}

			var arrLen = arrResponse.length;
			for (i = 0; i < arrLen; i++) {
				try {
					var respObj = jQuery.parseJSON(arrResponse[i]);
					if (respObj) {
						if (respObj.status) {
							lastStatus = respObj.status;

							if ($('#address').val() != "") {
								arrResults.push("<strong>" + reqSuccess + "</strong><br />" + reqSuccessMsg);
							}
						} else if (respObj.flaws) {
							if (!lastStatusSet) {
								lastStatusSet = true;
								$('#status').text(lastStatus);
							}

							pushResultRow(respObj.flaws, respObj.page);
						} else if (respObj.exception) {
							error = true;
							$( "#dlgError > #errMsg" ).html( "<pre>" + respObj.exception + "</pre>");
						}
					}
				} catch (e) {
					error = true;
					$( "#dlgError > #errMsg" ).html( e.toString() );
				}
			}

			if (outputFormat == 'html') {
				arrResults.push("</table>");
			} else if(outputFormat == 'wikitext' || $('#address').val() != "") {
				arrResults.push("</div>");
			}

			if ( !error ) {
				var container = $( "#resultContainer" );
				container.html(arrResults.join(""));
				$( "#resultContainer" ).toggleClass( "box-hidden", false );
				$( "#resultLink" ).html( '<a href="?submit=true' + getQueryString() + '">' + descLinkToRequest + '</a>' );
			} else {
				$( "#dlgError" ).dialog( "open" );
			}
		} else {
			newText = xhr.responseText.replace(respText, "");
			if (newText != '') {
				var arrResponse = newText.split("\n");
				for (i = 0; i < arrResponse.length; i++) {
					var respObj = jQuery.parseJSON(arrResponse[i]);
					if (respObj) {
						if (respObj.progress) {
							setProgress(respObj.progress);
						} else if (respObj.status) {
							lastStatus = respObj.status;
						} else {
							break;
						}
					}
				}
				$('#status').text(lastStatus);
			}
		}
	}, 100);
}

var lastFlaws = "";
function pushResultRow(flaws, page) {
	var row = "";
	var flawList = "";
	var articleFeedbackFilter = false;

	if (outputFormat == 'html') {
		row = "<tr><td>";
		$.each(flaws, function(index, flawObj) {
			row += '<img src="' + basePath + 'res/img/bulb-' + (flawObj.hidden ? "hidden" : "show") + '.png" class="' + (flawObj.hidden ? "hidden" : "") + '" style="cursor: pointer; color: blue;" onclick="markAsDone(this, ' + page.page_id + ', \'' + page.page_title + '\', ' + page.page_latest + ', \'' + flawObj.name + '\')" title="' + (flawObj.hidden ? descUnhide : descHide) + '" />'
			row += "&nbsp;" + flawObj.name;
			if( flawObj.infotext ) {
				row += " (" + flawObj.infotext + ") " 
			}
			row += "<br />";

			if (flawObj.name == 'ArticleFeedbackRatings') {
				articleFeedbackFilter = true;
			}
		});
		row += "</td>";
		row += "</td><td><a href=\"//" +
			$('#language').find(":selected").val() +
			".wikipedia.org/wiki/" +
			page.page_title + 
			"\" target=\"_blank\">" +
			page.page_title +
			"</a>";
		if (articleFeedbackFilter) {
			row += "<br /><a href=\"//" +
			$('#language').find(":selected").val() +
			".wikipedia.org/wiki/Special:ArticleFeedbackv5/" +
			page.page_title +
			"\" target=\"_blank\">Feedback</a>";
		}
		row += "</td></tr>";
	} else if (outputFormat == 'wikitext') {
		if (flawList != lastFlaws) {
			row = "<br />== " + flawList + " ==<br />";
			lastFlaws = flawList;
		}

		row += "* [[" + page.page_title + "]]<br />";
	}

	arrResults.push(row);
}

function setProgress( progress ) {
	progress = progress.split("/");
	
	if (progress.length == 2) {
		$('#tlgProgress').progressbar("value", Math.round(progress[0] * 100 / progress[1]));
	}

}

function toggleAddressField() {
	if ($('#bymail').attr('checked')) {
		$('#divAddress').css('visibility', 'visible');
	} else {
		$('#divAddress').css('visibility', 'hidden');
		$('#address').val("");
	}
}
