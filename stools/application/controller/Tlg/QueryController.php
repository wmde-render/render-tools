<?php
class Tlg_QueryController /*extends Controller*/ {

	public function __construct() {
		
	}
	

	public function htmlAction() {
		$view = new Tlg_HtmlView("tlg_html");
		echo $view->render();
	}
}
