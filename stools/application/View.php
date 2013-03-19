<?php
class View {
	
	private $_template;
	private $_lang;
	private $_messages;
	private $_errorMessages;
	private $_execStart;
	
	public function __construct( $templateName ) {
		$this->_execStart = microtime( true );
		$this->_template = $templateName;
		$this->_messages = array();
		$this->_errorMessages = array();

		# determine which language to use (1. get, 2. session, 3. browser language)
		$reqLang = SingletonFactory::getInstance( 'Request' )->getVar( 'uilang' );
		if ( $reqLang ) {
			$this->_lang = $reqLang;
		} elseif ( @$_SESSION['uilang'] ) {
			$this->_lang = $_SESSION['uilang'];
		} elseif( SingletonFactory::getInstance( 'Request' )->getVar( 'lang' ) ) {
			$this->_lang = SingletonFactory::getInstance( 'Request' )->getVar( 'lang' );
		} else {
			$this->_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}

		// HACK
		$objReq = SingletonFactory::getInstance('Request');
		if ($objReq->getVar('lang') && $objReq->getVar('id')) $this->_lang = $objReq->getVar('lang');
		
		$_SESSION['uilang'] = $this->_lang;
	}
	

	public function render() {
		$templateFile = TEMPLATE_PATH . $this->_template . '.phtml';
		
		if ( file_exists( $templateFile ) ) {
			include $templateFile;
		} else {
			echo 'could not find template';
		}
	}
	
	public function getExecutionTime() {
		$currentTime = microtime( true );
		$executionTime = $currentTime - $this->_execStart;
		$this->_execStart = $currentTime;
		return $executionTime;
	}
	
	public function setMessage( $msg ) {
		if ( is_array( $msg ) ) {
			$this->_messages = array_merge( $this->_messages, $msg );
		} else {
			$this->_messages[] = $msg;
		}
	}
	
	public function getMessages() {
		return $this->_messages;
	}

	public function setErrorMessage( $msg ) {
		if ( is_array( $msg ) ) {
			$this->_errorMessages = array_merge( $this->_errorMessages, $msg );
		} else {
			$this->_errorMessages[] = $msg;
		}
	}
	
	public function getErrorMessages() {
		return $this->_errorMessages;
	}
	
	public function hasErrors() {
		if ( !empty( $this->_errorMessages ) ) {
			return true;
		}
		
		return false;
	}
	
	public function translate( $key, $module = null ) {
		if ( !$module ) {
			$module = SingletonFactory::getInstance( 'Request' )->getModule();
		}
		
		$langFile = APP_PATH . "lang/" . $this->_lang . "/" . $module . ".lang.php";
		
		if ( file_exists( $langFile ) ) {
			include ( $langFile );
		} else {
			include( APP_PATH . "lang/en/" . $module . ".lang.php" );
		}

		if( is_array( $key ) ) {
			if( array_key_exists( $key[0], $strLang ) ) {
				if( array_key_exists( $key[1], $strLang[$key[0]] ) ) {
					return $strLang[$key[0]][$key[1]];
				}
			}
		} else {
			if (array_key_exists( $key, $strLang ) ) {
				return $strLang[$key];
			}
		}
		
		return is_array( $key ) ? $key[0] : $key;
	}

	public function getLang() {
		return isset($this->_lang) ? $this->_lang : 'de';
	}
}
