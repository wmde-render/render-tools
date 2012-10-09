<?php
class Newsfeed_Model extends Model {

	public function __construct() { }

	public function getNewsCount( $title ) {
		$title = urlencode( $title );
		$url = "http://newsfeed.ijs.si/query/news-search?cu=http://en.wikipedia.org/wiki/" . $title;
		$result = file_get_contents( $url );
		$news = json_decode( $result );

		if ( isset( $news->error ) ) {
			return -1;
		}
		return $news->hits;
	}
	
	public function getNewsArticles( $title ) {
		$title = "http://en.wikipedia.org/wiki/" . $title;
		$url = "http://newsfeed.ijs.si/query/news-search?cu=" . urlencode($title);
		$result = file_get_contents( $url );
		$news = json_decode( $result );

		if ( isset( $news->error ) ) {
			return -1;
		}
		return $news->articles;
	}
}
