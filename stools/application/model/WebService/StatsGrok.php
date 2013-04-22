<?php
class WebService_StatsGrok extends WebService {

	private $_accessCount = array( "monthly" => 0, "yesterday" => 0 );

	public function __construct() { }

	public function getStats( $title ) {
		$title = rawurlencode($title);
		
		$serviceUrl = "stats.grok.se/json/" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . "/latest30/" . $title;
		$this->init( $serviceUrl );
		$this->sendRequest();

		if( $this->getStatus() === WebService::STATUS_SUCCESS ) {
			$response = $this->parseJsonResponse();
			foreach( $response["daily_views"] as $dailyViews ) {
				$this->_accessCount["monthly"] += $dailyViews;
			}
			return $this->_accessCount["monthly"];
		}

		return false;
	}

	public function getYesterdayStats( $title ) {
		$title = rawurlencode($title);
		
		$serviceUrl = "stats.grok.se/json/" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . "/" . date("Ym") . "/" . $title;
		$this->init( $serviceUrl );
		$this->sendRequest();

		if( $this->getStatus() === WebService::STATUS_SUCCESS ) {
			$response = $this->parseJsonResponse();
			$stats = $response["daily_views"];

			$yesterday = date( "Y-m-d", time() - 60 * 60 * 24 );

			return $stats[$yesterday];
		}

		return false;
	}
	
	public function getClassicStatsLastMonth( $title, $lang = "en" ) {
		$date = date( "Ym", strtotime("-1 month") );
		
		$serviceUrl = "stats-classic.grok.se/json/" . $lang . "/" . $date . "/" . $title;
		$this->init( $serviceUrl );
		$this->sendRequest();

		if( $this->getStatus() === WebService::STATUS_SUCCESS ) {
			$response = $this->parseJsonResponse();
			return $response["total_views"];
		}

		return false;
	}

	public function getClassicStatsYesterday( $title, $lang = "en" ) {
		$date = date("Ym", time() - 60 * 60 * 24);
		
		$serviceUrl = "stats-classic.grok.se/json/" . $lang . "/" . $date . "/" . $title;
		$this->init( $serviceUrl );
		$this->sendRequest();

		if( $this->getStatus() === WebService::STATUS_SUCCESS ) {
			$response = $this->parseJsonResponse();

			return end( $response["daily_views"] );
		}

		return false;
	}
}
