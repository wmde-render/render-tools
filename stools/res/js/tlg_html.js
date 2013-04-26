var xhr = new XMLHttpRequest();
var timer;
var arrResults;
var outputFormat;

$(document).ready(function() {
	// set click function for group heading checkbox
	$( '.cbGroup' ).click( function() {
		$( '.' + this.id ).attr( "checked", $( this ).attr( "checked" ) == "checked" ? "checked" : false );
	});
	
	$( '.cbFilter' ).click( function() {
		$( this ).parent().find( 'input.cbGroup' ).attr( "checked",
			$( this ).parent().find( 'input.cbFilter' ).not( ':checked' ).length === 0 ?
			"checked" : false
		)
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

	$('input.taggable').tagedit({
		animSpeed: 100,
		breakKeyCodes: [ 10, 13 ],
		additionalListClass: "field-taggable",
		autocompleteOptions: {
			minLength: 3,
			source: function(request, response) {
				var lang = $( "#language" ).find( ":selected" ).val();
				$.ajax({
					url: basePath + "tlg/query/categories",
					dataType: "json",
					data: {
						lang: lang,
						term: request.term
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
							return {
								label: item.value,
								value: item.value
							}
						}));
					},
					beforeSend: function() {
						$( "#helpCategories" ).attr( "src", basePath + "res/img/tlg-load.gif" );
					},
					complete: function() {
						$( "#helpCategories" ).attr( "src", basePath + "res/img/emblem-notice.png" );
					}
				});
			}
		}
	});

	$('#btnSearch').click(function() {
		var qString = getQueryString();
		if( qString ) {
			$('#statusDialog').dialog('open');
			queryTlg(qString);
		}
		return false;
	});
});

var getQueryString = function() {
	var params = {};
	params.flaws = [];
	params.query = [];

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
							if ( this.name === 'query[]' ) {
								params.query.push( $( this ).val() );
							} else {
								params[this.name] = $( this ).val();
							}
						}
						break;
				}
				break;
		}
	});

	// format is overwritten by field for output format, resetting to 'json'
	params.action = 'query';
	params.format = 'json';
	params.chunked = true;
	
	var formResult = formCheck( params );
	if ( formResult === true ) {
		params.flaws = params.flaws.join(" ");
		params.query = params.query.join(";");
		return $.param( params );
	} else {
		$( "#dlgError > #errMsg" ).html( formResult );
		$('#dlgError').dialog('open');
	}
	
	return false;
}

var validateAddress = function( value ) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test( value );
};

var formCheck = function( params ) {
	if ( params.flaws.length <= 0 ) {
		return errNoFilter;
	}

	if ( params.query.length <= 0 ) {
		return errNoQuery;
	}

	if ( params.bymail ) {
		if ( !validateAddress( $( "#address" ).val() ) ) {
			return errNoAddress;
		}
	}

	return true;
}

/*
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
*/

function queryTlg(params) {
	$('#resultTable').empty();
	arrResults = [];
	var jqXHR = $.ajax({
		type: "POST",
		url: tlgServiceUrl,
		data: params,
		progress: statusUpdate,
		progressInterval: 250
	});
	
	jqXHR.done( function( data ) {
			$('#statusDialog').dialog('close');
			parseResponse( data );
		}
	);
}

$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
    if ( $.isFunction( options.progress ) ) {
        var xhrFactory = options.xhr, interval, partialResponse = "";

        options.xhr = function() {
            var xhr = xhrFactory.apply( this, arguments );
            interval = setInterval( function() {
                var responseText;
                try {
                    responseText = xhr.responseText;
                    if ( responseText && ( responseText.length > partialResponse.length ) ) {
                        options.progress( responseText );
                    }
                } catch(e) {
                    console.log(e);
                }
            }, options.progressInterval );

            return xhr;
        };

		function stop() {
            if ( interval ) {
                clearInterval( interval );
            }
        }

		// stop interval on 'done' and 'fail''
		jqXHR.then( stop, stop );
    }
});
    
var lastFlaws = "";
function pushResultRow(flaws, page) {
	var row = "";
	var flawList = "";
	var articleFeedbackFilter = false;

	if (outputFormat == 'html') {
		row = "<tr><td>";
		$.each(flaws, function(index, flawObj) {
			// row += '<img src="' + basePath + 'res/img/bulb-' + (flawObj.hidden ? "hidden" : "show") + '.png" class="' + (flawObj.hidden ? "hidden" : "") + '" style="cursor: pointer; color: blue;" onclick="markAsDone(this, ' + page.page_id + ', \'' + page.page_title + '\', ' + page.page_latest + ', \'' + flawObj.name + '\')" title="' + (flawObj.hidden ? descUnhide : descHide) + '" />'
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

function statusUpdate( data ) {
	var arrResponse = data.split("\n");
	for (i = 0; i < arrResponse.length; i++) {
		var respObj = $.parseJSON(arrResponse[i]);
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

function parseResponse( data ) {
	outputFormat = $('#outputFormat').find(":selected").val();
	var resultCount = 0;
	var arrResponse = data.split("\n");
	var lastStatusSet = false;
	var error = false;

	if (outputFormat == 'html') {
		arrResults.push("<table><tr><th>" + tableHeadFlaw + "</th><th>" + tableHeadPage + "</th></tr>");
	} else if(outputFormat == 'wikitext' || $('#address').val() != "") {
		arrResults.push("<div>");
	}

	var arrLen = arrResponse.length;
	for (i = 0; i < arrLen; i++) {
		try {
			var respObj = $.parseJSON(arrResponse[i]);
			if (respObj) {
				if (respObj.status) {
					lastStatus = respObj.status;

					if ( $('#address').val() != "" ) {
						arrResults.push("<strong>" + reqSuccess + "</strong><br />" + reqSuccessMsg);
					}
				} else if (respObj.flaws) {
					resultCount ++;
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
		var container = $( "#resultTable" );
		container.html(arrResults.join(""));
		if ( resultCount > 0 ) {
			$( "#resultContainer" ).toggleClass( "box-hidden", false );
			$( "#resultInfo" ).html( msgResultCount.replace( '%COUNT%', resultCount ) + '<br /><a href="?submit=true&' + getQueryString() + '">' + descLinkToRequest + '</a>' );
			$( "html, body" ).animate({
				scrollTop: $( "#resultContainer" ).offset().top
			}, 1000);
		}
	} else {
		$( "#dlgError" ).dialog( "open" );
	}
}
