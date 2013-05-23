<?php
class Asqm_HtmlView extends View {
	
	private $_articleInfo = null;
	private $_revisionInfo = null;
	
	public function __construct( $templateName ) {
		$this->_articleInfo = $this->getArticleInfo();
		parent::__construct( $templateName );
	}

	public function getId() {
		return SingletonFactory::getInstance( 'Request' )->getVar('id');
	}
	
	public function getLanguage() {
		return SingletonFactory::getInstance( 'Request' )->getVar('lang');
	}
	
	public function getArticleInfo() {
		if( !$this->_articleInfo ) {
			$this->_articleInfo = SingletonFactory::getInstance( 'Asqm_Model' )
				->getArticle( SingletonFactory::getInstance( 'Request' )->getVar( 'id' ) );
		}
		
		return $this->_articleInfo;
	}

	public function getRevisionInfo( $first ) {
		$this->_revisionInfo = SingletonFactory::getInstance( 'Asqm_Model' )
			->getRevision( SingletonFactory::getInstance( 'Request' )->getVar( 'id' ), $first );
		
		return $this->_revisionInfo;
	}

	public function getTitle() {
		return $this->_articleInfo[0]['page_title'];
	}
	
	public function getLinkCount() {
		$this->_linkInfo = SingletonFactory::getInstance( 'Asqm_Model' )
			->getLinkCount( SingletonFactory::getInstance( 'Request' )->getVar( 'id' ) );
		
		return $this->_linkInfo;
	}

	public function getImageCount() {
		$imageInfo = SingletonFactory::getInstance( 'Asqm_Model' )
			->getImageCount( SingletonFactory::getInstance( 'Request' )->getVar( 'id' ) );
		$this->_imageInfo = $imageInfo[0][0];
		
		return $this->_imageInfo;
	}

	public function getRealImageCount() {
		$this->_imageInfo = SingletonFactory::getInstance( 'Asqm_Model' )
			->getRealImageCount( SingletonFactory::getInstance( 'Request' )->getVar( 'id' ) );
		
		return $this->_imageInfo;
	}

	public function getRevisionUserName() {
		if ( $this->_revisionInfo[0]['rev_user'] === "0" ) {
			return "IP";
		} else {
			return $this->_revisionInfo[0]['rev_user_text'];
		}
	}
	
	public function getTimestamp( $wikiTimestamp ) {
		if ( strlen($wikiTimestamp) == 14 ) {
			# yyyymmddhhmmss
			if ( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) == "de" ) {
				return date( "d.m.Y H:i", strtotime( $wikiTimestamp ) );
			}

			return date( "Y-m-d H:i", strtotime( $wikiTimestamp ) );
		}
		
		return "";
	}
	
	public function getNewsItems() {
		$newsCount = SingletonFactory::getInstance( 'Newsfeed_Model' )->getNewsCount( $this->getTitle() );
		if ( $newsCount != -1 ) {
			return $newsCount;
		} else {
			return false;
		}
	}
	
	public function getUniqueEditorCount() {
		return SingletonFactory::getInstance( 'Asqm_Model' )
			->getUniqueEditorCount( SingletonFactory::getInstance( 'Request' )->getVar( 'id' ) );
	}
	
	public function getUserInfoObject( $key ) {
		$uInfo = posix_getpwuid(posix_getuid());
		if ( is_array( $uInfo ) && array_key_exists( $key, $uInfo ) ) {
			return $uInfo[$key];
		}

		return false;
	}
}
