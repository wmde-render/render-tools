<?php
class Tlg_QueryController extends Controller {

	public function __construct() {
		
	}
	

	public function htmlAction() {
		$this->forceSecure();
		
		$view = new Tlg_HtmlView("tlg_html");
		echo $view->render();
	}
}
