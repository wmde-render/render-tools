<?php
class Newsfeed_Model extends Model {
	private $_count;

	public function __construct() { }

	public function getNewsCount( $title ) {
		if ( $this->_count ) {
			$title = "http://en.wikipedia.org/wiki/" . urlencode( $title );
			$url = "http://newsfeed.ijs.si/render/search?cu=" . urlencode( $title );
			$result = @file_get_contents( $url );
			if ( $result !== false ) {
				$news = json_decode( $result );

				if ( isset( $news->error ) ) {
					return -1;
				} elseif ( isset( $news->hits ) ) {
					$this->_count = $news->hits;
					return $news->hits;
				}
			}
		} else {
			return $this->_count;
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
