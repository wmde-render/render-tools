var toggleDescription = function() {
	$('#Description2').toggleClass('displayNone');
	if ( $('#Description2').hasClass('displayNone') ) {
		$('#expandIcon').attr( "src", "../img/expand-large-silver.png" );
	} else {
		$('#expandIcon').attr( "src", "../img/collapse-large-silver.png" );
	}
}
