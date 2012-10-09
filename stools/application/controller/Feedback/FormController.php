<?php
class Feedback_FormController /*extends Controller*/ {

	public function __construct() {
		
	}
	
	public function indexAction() {
		$view = new Feedback_HtmlView( "stools_feedback" );
		echo $view->render();
	}
	
	public function captchaAction() {
		echo SingletonFactory::getInstance( 'Feedback_Model' )->getCaptcha();
	}
	
	public function sendAction() {
		$view = new Feedback_HtmlView( "stools_feedback" );
		$err = SingletonFactory::getInstance( 'Feedback_Model' )->validateForm();
		
		if ( !empty($err) ) {
			$view->setErrorMessage( $err );
		} else {
			$err = SingletonFactory::getInstance( 'Feedback_Model' )->sendMail();
			
			if ( !empty($err) ) {
				$view->setErrorMessage( $err );
			} else {
				$view->setMessage( "Vielen Dank für das Feedback!" );
			}
		}
		
		echo $view->render();
	}
}
