<?php
class ArticleMonitor_IndexController /*extends Controller*/ {

	public function __construct() {
		
	}
	

	public function indexAction() {
		$view = new ArticleMonitor_OverView("articleMonitor_overview");
		echo $view->render();
	}
}
