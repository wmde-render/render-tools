<?php
function api_normalize_redirect( $articletitle, $LanguageVersion ) {
	$articletitle =  str_replace( " ", "_", $articletitle );
	$post = array(
		'action' => 'query',
		'format' => 'php',
		'titles' => $articletitle,
		'redirects' => '3'
	);
	
	$post = http_build_query($post);
	
	$defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'wmde-render-lea/1.01',
        CURLOPT_POSTFIELDS => $post
    );

	$url = 'http://' . $LanguageVersion . '.wikipedia.org/w/api.php?';
	$ch = curl_init($url);
    curl_setopt_array($ch, $defaults);
    if( !$result = curl_exec( $ch ) ) { 
		trigger_error( curl_error( $ch ) );
    }
    curl_close($ch);

	if( preg_match( '@"missing"@', $result ) ) {
		return NULL;
	}

	$result_array = unserialize( $result );

	if( isset( $result_array['query']['redirects']['0'] ) ) {
		return $result_array['query']['redirects']['0']['to'];
	}
	
	if( isset( $result_array['query']['normalized']['0'] ) ) {
		return $result_array['query']['normalized']['0']['to'];
	}	
	
	return NULL;
}