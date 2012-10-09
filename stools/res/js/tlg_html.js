var xhr = new XMLHttpRequest();
var timer;
var arrResults;
var outputFormat;

$(document).ready(function() {
	// set click function for group heading checkbox
	$( '.cbGroup' ).click( function() {
		$.each( $( '.' + this.id ), function() {
			$( this ).attr( "checked", !$( this ).attr( "checked" ) );
		});
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
	
	$( "#tlgProgress" ).progressbar({
		value: 0
	});

	$('#btnSearch').click(function() {
		$('#statusDialog').dialog('open');

		var $inputs = $('form :input');
		var qryStr = "";
		var flaws = [];
		var flawCount = -1;
		$inputs.each(function() {
			if (this.name == "flaw") {
				if ($(this).attr('checked')) {
					flaws[++flawCount] = $(this).val().replace("-", ":");
				}
			} else if (this.name == "format") {
				if ($('#bymail').attr('checked')) {
					qryStr += "&" + this.name + "=" + $(this).val();
				} else {
					outputFormat = $(this).find(":selected").val();
				}
			} else if (this.name == "mailto") {
				if ($('#bymail').attr('checked')) {
					qryStr += "&" + this.name + "=" + encodeURIComponent($(this).val());
				}
			} else if (this.name != "") {
				qryStr += "&" + this.name + "=" + $(this).val();
			}
		});
		qryStr += "&flaws=" + flaws.join("%20");

		queryTlg(qryStr);
		return false;
	});
});

function queryTlg(qryStr) {
	$('#resultContainer').empty();
	arrResults = [];
	var respText = "";
	var error = false;

	xhr.open('GET', 'http://toolserver.org/~jkroll/tlgbe/tlgwsgi.py?action=query&format=json&chunked=true' + qryStr, true);
	xhr.send(null);

	timer = window.setInterval(function() {
		$('div#requestState').text('Request State: ' + xhr.readyState + ', Response length: ' + xhr.responseText.length);

		if (xhr.readyState == XMLHttpRequest.DONE) {
			$('#statusDialog').dialog('close');

			var arrResponse = xhr.responseText.split("\n");
			var lastStatusSet = false;

			if (outputFormat == 'html') {
				arrResults.push("<table><tr><th>Mangel</th><th>Seitentitel</th></tr>");
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
								arrResults.push("<strong>Anfrage erfolgreich</strong><br />Die Liste wird nach Fertigstellung an die angegebene E-Mail-Adresse versendet.");
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
	var flawList = flaws.join(", ");
	
	if (outputFormat == 'html') {
		row = "<tr><td>" + flawList + "</td>";
		row += "</td><td><a href=\"//" + 
			$('#language').val() + 
			".wikipedia.org/wiki/" + 
			page.page_title + 
			"\" target\"_blank\">" +
			page.page_title + 
			"</a>" + 
			"</td></tr>";
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
	}
}
