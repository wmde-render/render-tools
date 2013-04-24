<?php
class Tlg_QueryController extends Controller {

	public function __construct() {
		
	}
	

	public function htmlAction() {
		$this->forceSecure();
		
		$view = new Tlg_HtmlView("tlg_html");
		echo $view->render();
	}
	
	
	public function categoriesAction() {
		$term = SingletonFactory::getInstance( "Request" )->getVar( "term" );
		$response = SingletonFactory::getInstance( "Wikipedia_Categories" )->getCategoryNames( $term );
		echo json_encode( $response );
	}
}
