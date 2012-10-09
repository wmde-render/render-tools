<?php
class Default_IndexController /*extends Controller*/ {

	public function __construct() {
		
	}
	

	public function indexAction() {
		$view = new View( "stools_overview" );
		echo $view->render();
	}
}
