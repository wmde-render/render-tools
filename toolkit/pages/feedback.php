<?php
$_SESSION['lang'] = $_POST['lang'];
$ts_pw = posix_getpwuid(posix_getuid());
include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/feedback_txt.inc");

require('inc/php-captcha.inc.php');

if (PhpCaptcha::Validate($_POST['captcha'])) {

	$mailBody = "Ein Benutzer hat das Feedback-Formular ausgefüllt und folgende Angaben übermittelt:\n\n";
	foreach ($_POST as $key => $value) {
		if ($key != "captcha") {
			$mailBody .= $key . ": " . $value . "\n";
		}
	}

	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=UTF-8";
	$headers[] = "From: RENDER-Toolkit <render@wikimedia.de>";
	$headers[] = "Subject: {$subject}";
	$headers[] = "X-Mailer: PHP/".phpversion();
	$mailSent = mail('render@wikimedia.de', 'RENDER Feedback-Formular', $mailBody, implode("\r\n", $headers));

	if ($mailSent === false) {
		echo "(ERR)" . $mailDeliveryError;
	} else {
		echo $mailDeliverySuccess;
	}

} else {
	echo "(ERR)" . $wrongCaptchaCode;
}
