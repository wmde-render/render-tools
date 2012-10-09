<?php
require( APP_PATH . 'lib/php-captcha.inc.php' );

class Feedback_Model extends Model {

	public function __construct() { }

	public function getCaptcha() {
		$aFonts = array(
			APP_PATH . '../res/font/VeraBd.ttf',
			APP_PATH . '../res/font/VeraIt.ttf',
			APP_PATH . '../res/font/Vera.ttf'
		);
		$oVisualCaptcha = new PhpCaptcha( $aFonts, 200, 60 );
		$oVisualCaptcha->Create();
	}

	public function validateForm() {
		$err = array();
		
		if ( !PhpCaptcha::Validate( SingletonFactory::getInstance( 'Request' )->getVar( 'captcha' ) ) ) {
			$err[] = "wrongCaptcha";
		}
		
		$address = SingletonFactory::getInstance( 'Request' )->getVar( 'address' );
		if ( empty( $address ) ) {
			$err[] = "mailAddressMandatory";
		}elseif ( !preg_match( "/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i", $address ) ) {
			$err[] = "mailAddressIncorrect";
		}
		
		return $err;
	}

	public function sendMail() {
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=UTF-8";
		$headers[] = "From: RENDER <render@wikimedia.de>";
		$headers[] = "Reply-To: " . SingletonFactory::getInstance( 'Request' )->getVar( 'address' );
		$headers[] = "Subject: {Feedback zu den Supporting-Tools}";
		$headers[] = "X-Mailer: PHP/" . phpversion();

		$mailBody = "Ein Benutzer hat das Feedback-Formular ausgefüllt und folgende Angaben übermittelt:\n\n";
		foreach ( SingletonFactory::getInstance( 'Request' )->getVars() as $key => $value ) {
			if ( $key != "captcha" && $key != "PHPSESSID" ) {
				$mailBody .= $key . ": " . $value . "\n";
			}
		}
		
		$mailSent = mail( 'render@wikimedia.de', 'Feedback zu den Supporting-Tools',
			$mailBody, implode( "\r\n", $headers ) );

		if ( $mailSent === false ) {
			return "mailError";
		}
		
		return null;
	}
}
