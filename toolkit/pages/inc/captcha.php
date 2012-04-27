<?php
require('php-captcha.inc.php');
$aFonts = array('../../img/VeraBd.ttf', '../../img/VeraIt.ttf', '../../img/Vera.ttf');
$oVisualCaptcha = new PhpCaptcha($aFonts, 200, 60);
$oVisualCaptcha->Create();
