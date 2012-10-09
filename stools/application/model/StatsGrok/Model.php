<?php
class StatsGrok_Model extends Model {

	public function __construct() { }

	public function getStats( $title ) {
		$title = rawurlencode($title);
		#$url = "http://stats.grok.se/json/" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . "/latest30/" . $title;
		$url = "http://www.knse.de/render/proxyGrok.php?lang=" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . "&type=latest30&title=" . $title;
		
		$res = json_decode( file_get_contents( $url ) );
		$stats = (array) $res->daily_views;
		
		$sum = 0;
		foreach ($stats as $stat) {
			$sum += $stat;
		}
		
		return $sum;
	}

	public function getTodayStats( $title ) {
		$title = rawurlencode($title);
		$year = "";
		$month = "";
		
		#$url = "http://stats.grok.se/json/" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . "/" . date("Ym") . "/" . $title;
		$url = "http://www.knse.de/render/proxyGrok.php?lang=" . SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) . "&type=" . date("Ym") . "&title=" . $title;

		$res = json_decode( file_get_contents( $url ) );
		$stats = (array) $res->daily_views;
		ksort( $stats );
		
		return end($stats);
	}
}
