<?php
class ArticleMonitor_QueryController /*extends Controller*/ {

	public function __construct() {
		$articleMonitorId = SingletonFactory::getInstance( "Request" )->getVar( 'asqmid' );
		if ( isset( $articleMonitorId ) && !empty( $articleMonitorId ) ) {
			$_SESSION['asqmId'] = $asqmId;
		}
	}

	public function jsonAction() {
		$view = new ArticleMonitor_JsonView( "" );
		SingletonFactory::getInstance( 'Response' )
				->setFullPage( false )
				->setHeader('Content-type: application/json');
		$model = SingletonFactory::getInstance( "ArticleMonitor_Json" );
		$model->setView( $view );
		echo SingletonFactory::getInstance('Request')->getVar('callback') . "({\"asqmResponse\": " . $view->getJson() . "})";
	}
	
	public function newsAction() {
		$req = SingletonFactory::getInstance( "Request" );
		$pageTitle = $req->getVar( 'title' );
		$lang = $req->getVar( 'lang' );
		$articleMonitorId = ( isset( $_SESSION['asqmId'] ) && !empty( $_SESSION['asqmId'] ) ) ? $_SESSION['asqmId'] : "none";
		$result = SingletonFactory::getInstance( 'Newsfeed_Model' )->getNewsCount( $pageTitle );
		
		SingletonFactory::getInstance( "ArticleMonitor_Model" )
			->logRequest( $pageTitle, $lang, $articleMonitorId, $actionType = "newsfinder-use", $result );

		$view = new ArticleMonitor_NewsView("newsfeed_list");
		echo $view->render();
	}
}
