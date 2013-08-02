<?php
class Newsfeed_Model extends Model {
	private $_count;
	private $_title;

	public function __construct() { }

	public function setArticleTitle( $title, $lang ) {
		if ( !isset( $this->_title ) && $lang !== "en" ) {
			$this->_title = $this->_getEnglishTitle( $title, $lang );
		}
	}

	private function _getEnglishTitle( $title, $lang ) {
		$title = urldecode( $title );

		$post = array(
			'action' => 'query',
			'format' => 'php',
			'titles' => $title,
			'prop' => 'langlinks',
			'lllimit' => '500'
		);
		$post = http_build_query($post);
		
		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_USERAGENT => 'wmde-render-newsfinder/1.00',
			CURLOPT_POSTFIELDS => $post
		);

		$url = 'http://' . $lang . '.wikipedia.org/w/api.php?';
		$ch = curl_init($url);
		curl_setopt_array($ch, $defaults);
		if( !$result = curl_exec( $ch ) ) { 
			trigger_error( curl_error( $ch ) );
		}
		curl_close($ch);

		if( preg_match( '@"missing"@', $result ) ) {
			return null;
		}

		$resultArray = unserialize( $result );
		
		if( isset( $resultArray ) ) {
			foreach( $resultArray['query']['pages'] as $page ) {
				foreach( $page['langlinks'] as $langlink ) {
					if ( $langlink['lang'] == 'en' ) {
						return str_replace( " ", "_", $langlink['*'] );
					}
				}
			}
		}
		
		return $title;
	}

	public function getNewsCount() {
		if ( !$this->_count ) {
			$title = "http://en.wikipedia.org/wiki/" . urlencode( $this->_title );
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
	
	public function getNewsArticles( $page = 0 ) {
		$title = "http://en.wikipedia.org/wiki/" . $this->_title;
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
