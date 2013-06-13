<?php
class Tlg_IndexController extends Controller {

	public function __construct() {
		
	}
	

	public function indexAction() {
		if( ALG_FORCE_SSL ) {
			$this->forceSecure();
		}

		$view = new Alg_HtmlView("alg_html");
		echo $view->render();
	}
}
