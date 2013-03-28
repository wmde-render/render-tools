<?php
class Asqm_QueryController /*extends Controller*/ {

	public function __construct() {
		$asqmId = SingletonFactory::getInstance( "Request" )->getVar( 'asqmid' );
		if ( isset( $asqmId ) && !empty( $asqmId ) ) {
			$_SESSION['asqmId'] = $asqmId;
		}
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
		$req = SingletonFactory::getInstance( "Request" );
		$pageTitle = $req->getVar( 'title' );
		$lang = $req->getVar( 'lang' );
		$asqmId = ( isset( $_SESSION['asqmId'] ) && !empty( $_SESSION['asqmId'] ) ) ? $_SESSION['asqmId'] : "none";
		$result = SingletonFactory::getInstance( 'Newsfeed_Model' )->getNewsCount( $pageTitle );
		
		SingletonFactory::getInstance( "Asqm_Model" )
			->logRequest( $pageTitle, $lang, $asqmId, $actionType = "newsfinder-use", $result );

		$view = new Asqm_NewsView("newsfeed_list");
		echo $view->render();
	}
}
