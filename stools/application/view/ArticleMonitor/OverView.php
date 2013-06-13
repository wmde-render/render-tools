<?php
class ArticleMonitor_OverView extends View {
	
	public function __construct( $templateName ) {
		parent::__construct( $templateName );
	}
	
	public function getUniqueId() {
		return substr( md5( microtime() ), 0, 8 ); #uniqid( "", true );
	}
}
