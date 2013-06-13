<?php
class Alg_HtmlView extends View {
	
	public function __construct( $templateName ) {
		parent::__construct( $templateName );
	}

	public function getFlaws() {
		return SingletonFactory::getInstance( 'Alg_Model' )->getFlawList();
	}
	
	public function getResults() {
		return SingletonFactory::getInstance( 'Alg_Model' )->query();
	}
}
