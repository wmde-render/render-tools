<?php
class Asqm_QueryController /*extends Controller*/ {

	public function __construct() {
		
	}
	

	public function htmlAction() {
		$view = new Asqm_HtmlView("asqm_html");
		echo $view->render();
	}

	public function htmlNewAction() {
		$view = new Asqm_HtmlView("asqm_html_new");
		echo $view->render();
	}

	public function jsonAction() {
		$view = new Asqm_HtmlView("asqm_jsonp");
		SingletonFactory::getInstance( 'Response' )
				->setFullPage( false )
				->setHeader('Content-type: application/json');
		echo $view->render();
	}
	
	public function newsAction() {
		$view = new Asqm_NewsView("newsfeed_list");
		echo $view->render();
	}
}
