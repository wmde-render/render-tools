<?php
class ArticleMonitor_Json extends Model {

	private $_view;

	private $_id;
	private $_lang;
	private $_articleMonitorId;

	private $_result;
	private $_groups;
	
	private $_articleInfo;

	public function __construct() {
		# get article id, language and widget id from request
		$this->_id = SingletonFactory::getInstance( 'Request' )->getVar( 'id' );
		$this->_lang = SingletonFactory::getInstance( 'Request' )->getVar( 'lang' );
		$this->_articleMonitorId = ( isset( $_SESSION['asqmId'] ) && !empty( $_SESSION['asqmId'] ) ) ? $_SESSION['asqmId'] : "none";

		# retrieve article info
		$articleInfo = SingletonFactory::getInstance( 'ArticleMonitor_Model' )
				->getArticle( $this->_id );
		$this->_articleInfo = $articleInfo[0];
		
		$this->_result = new stdClass();
		$this->_groups =  array(
			"statistics" => array(
				"pageTitle",
				"status",
				"firstEdit",
				"recentEdit",
				"totalEditors",
				"references",
				"images",
				"visitorsYesterday",
				"visitorsLastMonth"
			),
			"analysis" => array(
				"lea",
				/*"newsFinder",*/
				"changeDetector",
				"giniScore"
			),
			"assessment" => array(
				"wikibuch",
				"aft5",
				"aft4"
			)
		);
	}

	public function setView( $view ) {
		$this->_view = $view;
	}

	public function getResults() {
		$this->_result = $this->_createResult();
		$this->_scrubResult();
		return $this->_result;
	}

	private function _createResult() {
		SingletonFactory::getInstance( "ArticleMonitor_Model" )->logRequest(
			$this->_articleInfo['page_title'], $this->_lang, $this->_articleMonitorId, "articleMonitor-show", "" );

		foreach( $this->_groups as $group => $items ) {
			$this->_result->$group = new stdClass();
			$this->_result->$group->title = $this->_view->translate( array( $group, "title" ) );
			$this->_result->$group->items = new stdClass();
			foreach ( $items as $item ) {
				$func = "_get" . ucfirst( $item );
				$itemName = $this->_view->translate( array( $group, $item ) );
				$this->_result->$group->items->$itemName = $this->$func();
			}
		}

		return $this->_result;
	}
	
	private function _scrubResult() {
		foreach( $this->_result as $groupName => $groupProperties ) {
			foreach( $groupProperties->items as $itemName => $itemValue ) {
				if( $itemValue === null || !isset( $itemValue ) || empty( $itemValue ) ) {
					unset( $this->_result->$groupName->items->$itemName );
				}
			}

			if ( !isset( $groupProperties->items ) || 
					empty( $groupProperties->items ) ||  
					$groupProperties->items === null || 
					count( (array)$groupProperties->items ) < 1 ) {
				unset( $this->_result->$groupName );
			}
		}
	}
	
	private function _getPageTitle() {
		return htmlspecialchars( $this->_articleInfo['page_title'], ENT_QUOTES );
	}

	private function _getStatus() {
		return htmlspecialchars( SingletonFactory::getInstance( 'Api_Model' )
				->getFeaturedArticle( $this->_articleInfo['page_title'] ), ENT_QUOTES );
	}

	private function _getFirstEdit() {
		$revInfo = SingletonFactory::getInstance( 'ArticleMonitor_Model' )->getRevision( $this->_id , true /* get first revision */ );
		return $this->_getRevisionInfo( $revInfo[0] );
	}

	private function _getRecentEdit() {
		$revInfo = SingletonFactory::getInstance( 'ArticleMonitor_Model' )->getRevision( $this->_id , false /* get last revision */ );
		return $this->_getRevisionInfo( $revInfo[0] );
	}
	
	private function _getRevisionInfo( $revInfo ) {
		$retVal = array( "multipart" );
		$retVal[] = $this->_convertTimestamp( $revInfo['rev_timestamp'] );
		$retVal[] = " (";
		$retVal[] = $this->_view->translate( array( "general", "editedBy" ) ) . " ";
		$retVal[] = array(
			( $revInfo['rev_user'] === "0" ? "IP" : htmlspecialchars( $revInfo['rev_user_text'], ENT_QUOTES) ),
			( $revInfo['rev_user'] === "0" ? $this->_view->translate( array ( "general", "unregUsersPage" ) ) : "https://" . $this->_lang . ".wikipedia.org/wiki/User:" . urlencode( $revInfo['rev_user_text'] ) )
		);
		$retVal[] = ")";
		return $retVal;
	}

	private function _getTotalEditors() {
		$eCount = SingletonFactory::getInstance( 'ArticleMonitor_Model' )->getUniqueEditorCount( $this->_id );
		return $eCount["loggedin"] . " (+IP: " . $eCount["anonymous"] . ")";
	}

	private function _getReferences() {
		return SingletonFactory::getInstance( 'Api_Model' )->getCurrentRevision();
	}

	private function _getImages() {
		$imageInfo = SingletonFactory::getInstance( 'ArticleMonitor_Model' )->getImageCount( $this->_id );
		$this->_imageInfo = $imageInfo[0][0];
		
		return $this->_imageInfo;
	}

	private function _getVisitorsYesterday() {
		return SingletonFactory::getInstance( "WebService_StatsGrok" )
				->getClassicStatsYesterday( $this->_articleInfo['page_title'], $this->_lang );
	}

	private function _getVisitorsLastMonth() {
		return SingletonFactory::getInstance( "WebService_StatsGrok" )
				->getClassicStatsLastMonth( $this->_articleInfo['page_title'], $this->_lang );
	}

	private function _getLea() {
		$title = $this->_view->translate( array( "analysis", "showAnalysis" ) );
		$link = "http://tools.wmflabs.org/" . str_replace( "local-", "", $this->_view->getUserInfoObject( "name" ) ) .
			"/toolkit/LEA/index.php" .
			"?submit=1&title=" . urlencode( $this->_articleInfo['page_title'] ) .
			"&lg=" . $this->_lang . "&lang=" . $this->_lang;
		return array( $title, $link );
	}

	private function _getNewsFinder() {
		$link = "";
		$nfModel = SingletonFactory::getInstance( 'Newsfeed_Model' );
		$nfModel->setArticleTitle( $this->_articleInfo['page_title'], $this->_lang );
		$newsCount = $nfModel->getNewsCount();
		if ( $newsCount > 0 ) {
			$link = "http://tools.wmflabs.org/" . str_replace( "local-", "", $this->_view->getUserInfoObject( "name" ) ) .
			"/stools/articleMonitor/query/news/title/" .
				urlencode( $this->_articleInfo['page_title'] ) . "/lang/" . $this->_lang;
			SingletonFactory::getInstance( "ArticleMonitor_Model" )->logRequest(
				$this->_articleInfo['page_title'], $this->_lang, $this->_articleMonitorId, "newsfinder-show", $newsCount );
			$text = $newsCount . $this->_view->translate( array( "analysis", "newsFound" ) );
			return array( $text, $link );
		}

		return null;
	}

	private function _getChangeDetector() {
		if ( SingletonFactory::getInstance( 'ChangeDetector_Model' )
				->checkDetected( $this->_id, $this->_lang ) ) {
			SingletonFactory::getInstance( "ArticleMonitor_Model" )->logRequest(
				$this->_articleInfo['page_title'], $this->_lang, $this->_articleMonitorId, "cd-show", "" );

			$title = $this->_view->translate( array( "analysis", "cdHit" ) );
			$link = "http://tools.wmflabs.org/" . str_replace( "local-", "", $this->_view->getUserInfoObject( "name" ) ) .
			"/toolkit/ChangeDetector/index.php" .
				"?Cuthalf=on&Sorting=No_change" .
				"&filterMU=on&filterNB=on&filterOM=on&day=".
				date( "Ymd", time() - ( 86400 * 2 ) ) .
				"&Langgroup=EU&Reflang=" . $this->_lang .
				"&submit=%C3%9Cbermitteln#result_table";
			return array( $title, $link );
		}

		return null;
	}

	private function _getGiniScore() {
		$id = SingletonFactory::getInstance('Request')->getVar('id');
		$lang = SingletonFactory::getInstance('Request')->getVar('lang');
		$url = "http://wikiauth.fekepp.net/api.php" .
			"?format=json&action=gini&language=" . $lang .
			"&pageid=" . $id . "&mode=timestamp&dir=desc&round=2";
		$gini = @file_get_contents( $url );
		$jsonResult = json_decode( $gini );
		$link = "http://tools.wmflabs.org/" . str_replace( "local-", "", $this->_view->getUserInfoObject( "name" ) ) .
			"/toolkit/WIKIGINI/" .
			"?language_code=" . $this->_lang .
			"&page_id=" . $this->_id;
		if ( $jsonResult === false || $jsonResult === null ) {
			return array( $this->_view->translate( array( "analysis", "giniScoreProcessing" ) ), $link );
		} else {
			$score = $jsonResult[0][1];
			SingletonFactory::getInstance( "ArticleMonitor_Model" )->logRequest(
				$this->_articleInfo['page_title'], $this->_lang, $this->_articleMonitorId, "wikigini-show", $score );

			$retVal = array( "multipart" );
			$retVal[] = $this->_view->translate( array( "analysis", "giniDesc" ) ) . " ";
			$retVal[] = array( $score, $link );
			return $retVal;
		}
		
		return null;
	}

	private function _getWikibuch() {
		if ( $this->_lang === "de" ) {
			$title = $this->_view->translate( array( "assessment", "lookupAssessment" ) );
			$link = "http://wikibu.ch/search.php?search=" . urlencode( $this->_articleInfo['page_title'] );
			return array( $title, $link );
		}
		
		return null;
	}
	
	private function _getAft5() {
		$percent = SingletonFactory::getInstance( 'Api_Model' )->getArticleFeedback5();
		if ( !empty( $percent ) ) {
			SingletonFactory::getInstance( "ArticleMonitor_Model" )->logRequest( 
				$this->_articleInfo['page_title'], $this->_lang, $this->_articleMonitorId, "aft5-show", $percent );

			$title = $this->_view->translate( array( "aft5", "negRating" ) );
			$link = "http://en.wikipedia.org/wiki/Special:ArticleFeedbackv5/" . urlencode( $this->_articleInfo['page_title'] );
			return array( $title, $link );
		}

		return null;
	}
	
	private function _getAft4() {
		
	}

	private function _getQuestionnaire() {
		$retVal = array( "multipart" );
		$retVal[] = $this->_view->translate( array( "notice", "questionnaireText1" ) ) . " ";
		$retVal[] = array(
			$this->_view->translate( array( "notice", "questionnaireLinkText" ) ),
			$this->_view->translate( array( "notice", "questionnaireLink" ) )
		);
		$retVal[] = $this->_view->translate( array( "notice", "questionnaireText2" ) );
		return $retVal;
	}

	private function _convertTimestamp( $wikiTimestamp ) {
		if ( strlen($wikiTimestamp) == 14 ) {
			# yyyymmddhhmmss
			if ( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) == "de" ) {
				return date( "d.m.Y H:i", strtotime( $wikiTimestamp ) );
			}

			return date( "Y-m-d H:i", strtotime( $wikiTimestamp ) );
		}
		
		return "";
	}
}
