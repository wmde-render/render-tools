<?php
class Asqm_NewsView extends View {
	
	public function __construct( $templateName ) {
		parent::__construct( $templateName );
	}
	
	public function shortenUrl( $url ) {
		$parts = parse_url($url);
		if (array_key_exists("host", $parts) ) {
			return $parts["host"];
		}
		
		return $url;
	}
}
