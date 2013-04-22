<?php
class WebService_StatsGrok extends WebService {

	# constants
	const MONTHLY = "monthly";
	const DAILY = "daily";
	const BOTH = "both";
	
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
	
	public function getClassicStats( $type, $title, $lang = "en" ) {
		$accessCount = array( "monthly" => 0, "daily" => 0 );
		$date = date( "Ym" );
		if ( $type === WebService_StatsGrok::DAILY ) {
			$date = date("Ym", time() - 60 * 60 * 24);
		}
		
		$serviceUrl = "stats.grok.se/json/" . $lang . "/" . $date . "/" . $title;
		$this->init( $serviceUrl );
		$this->sendRequest();

		if( $this->getStatus() === WebService::STATUS_SUCCESS ) {
			$response = $this->parseJsonResponse();

			if( $type === WebService_StatsGrok::MONTHLY || $type === WebService_StatsGrok::BOTH ) {
				foreach( $response["daily_views"] as $dailyViews ) {
					$accessCount["monthly"] += $dailyViews;
				}
			}

			if( $type === WebService_StatsGrok::DAILY || $type === WebService_StatsGrok::BOTH ) {
				$yesterday = date( "Y-m-d", time() - 60 * 60 * 24 );
				if( array_key_exists( $yesterday, $response["daily_views"] ) ) {
					$accessCount["daily"] = $response["daily_views"][$yesterday];
				} else {
					$stats = $this->getClassicStats( WebService_StatsGrok::DAILY, $title, $lang );
					$accessCount["daily"] = $stats["daily"];
				}
			}
			return $accessCount;
		}

		return false;
	}
}
