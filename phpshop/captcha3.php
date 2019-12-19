<?php

require_once './lib/captcha/php-captcha.inc.php';
$imagesPath = './lib/font/'; 

$aFonts = array( 
    $imagesPath . 'VeraBd.ttf',
    $imagesPath . 'VeraIt.ttf',
    $imagesPath . 'Vera.ttf'
);

$oVisualCaptcha = new PhpCaptcha($aFonts, 90, 32);
$oVisualCaptcha->UseColour(true);
//$oVisualCaptcha->SetOwnerText('Source: '.FULL_BASE_URL);
$oVisualCaptcha->SetNumChars(4);
$oVisualCaptcha->SetNumLines(15);
$oVisualCaptcha->SetMinFontSize(14);
$oVisualCaptcha->SetMaxFontSize(16);
$oVisualCaptcha->Create();
$_SESSION['text'] = $oVisualCaptcha->sCode;
?>
