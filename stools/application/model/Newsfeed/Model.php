<?php
class Newsfeed_Model extends Model {
	private $_count;

	public function __construct() { }

	public function getNewsCount( $title ) {
		if ( !$this->_count ) {
			$title = "http://en.wikipedia.org/wiki/" . urlencode( $title );
			$url = "http://newsfeed.ijs.si/render/search?limit=1&cu=" . urlencode( $title );
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
	
	public function getNewsArticles( $title, $page = 0 ) {
		$title = "http://en.wikipedia.org/wiki/" . $title;
		$url = "http://newsfeed.ijs.si/render/search?limit=10&page=" . $page . "&cu=" . urlencode( $title );
		$result = @file_get_contents( $url );
		if ( $result !== false ) {
			$news = json_decode( $result );

			if ( isset( $news->error ) ) {
				return -1;
			} elseif ( isset( $news->articles ) ) {
				if ( isset( $news->hits ) ) {
					$this->_count = $news->hits;
				}
				return $news->articles;
			}
		}

		return -1;
	}
	
	public function getItemCount() {
		return $this->_count;
	}
}
