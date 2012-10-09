<?php
class Tlg_IndexController /*extends Controller*/ {

	public function __construct() {
		
	}
	

	public function indexAction() {
		$view = new Tlg_HtmlView("tlg_html");
		echo $view->render();
	}
}
