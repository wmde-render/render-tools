<?php
class Newsfeed_Model extends Model {

	public function __construct() { }

	public function getNewsCount( $title ) {
		$title = "http://en.wikipedia.org/wiki/" . urlencode( $title );
		$url = "http://newsfeed.ijs.si/render/search?cu=" . urlencode( $title );
		$result = @file_get_contents( $url );
		if ( $result !== false ) {
			$news = json_decode( $result );

			if ( isset( $news->error ) ) {
				return -1;
			} elseif ( isset( $news->hits ) ) {
				return $news->hits;
			}
		}
		
		return -1;
	}
	
	public function getNewsArticles( $title ) {
		$title = "http://en.wikipedia.org/wiki/" . $title;
		$url = "http://newsfeed.ijs.si/render/search?limit=100&cu=" . urlencode( $title );
		$result = @file_get_contents( $url );
		if ( $result !== false ) {
			$news = json_decode( $result );

			if ( isset( $news->error ) ) {
				return -1;
			} elseif ( isset( $news->articles ) ) {
				return $news->articles;
			}
		}
		
		return -1;
	}
}
