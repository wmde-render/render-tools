<?php
class Asqm_IndexController /*extends Controller*/ {

	public function __construct() {
		
	}
	

	public function indexAction() {
		$view = new Asqm_OverView("asqm_overview");
		echo $view->render();
	}
}
