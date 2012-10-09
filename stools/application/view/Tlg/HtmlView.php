<?php
class Tlg_HtmlView extends View {
	
	public function __construct( $templateName ) {
		parent::__construct( $templateName );
	}

	public function getFlaws() {
		return SingletonFactory::getInstance( 'Tlg_Model' )->getFlawList();
	}
	
	public function getResults() {
		return SingletonFactory::getInstance( 'Tlg_Model' )->query();
	}
}
