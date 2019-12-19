<?php
session_start();
putenv('GDFONTPATH=' . realpath('.'));
header("Content-type: image/png");
$font="../../../lib/font/norobot_font.ttf";
$text=substr(md5(time()),0,5);

// Защитный код
$_SESSION['mod_formgenerator_captcha']=$text;
$im=imagecreate(180, 56);
$w=imagecolorallocate($im, 255, 255, 255);
$b=imagecolorallocate($im, 90, 90, 90);
$g1=imagecolorallocate($im, 180, 180, 180);
$g2=imagecolorallocate($im, 100, 100, 100);
$g3=imagecolorallocate($im, 170, 200, 220);
$cl1=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
$cl2=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
$cl3=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
$cl4=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
$cl5=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
for($i=0; $i <= 180; $i += 4 ) {
    imageline($im, $i, 0, $i, 55, $g1);
}
for($i=0; $i <= 55; $i += 4 ) {
    imageline($im, 0, $i, 180, $i, $g1);
}
for($i=0; $i <= 180; $i += 45 ) {
    imageline($im, rand(-2, 18) + $i, rand(-2, 18), rand(38, 58) + $i, rand(38, 58), $g3);
}
for($i=0; $i <= 180; $i += 45 ) {
    imageline($im, rand(-2, 18) + $i, rand(38, 58), rand(38, 58) + $i, rand(-2, 18), $g3);
}
@imagettftext($im, rand(28, 32), rand(-30, 30), 10 + rand(0, 6), 40 + rand(-10, 10), $cl1, $font, substr($text, 0, 1));
@imagettftext($im, rand(28, 32), rand(-30, 30), 45 + rand(-6, 6), 40 + rand(-10, 10), $cl2, $font, substr($text, 1, 1));
@imagettftext($im, rand(28, 32), rand(-30, 30), 80 + rand(-6, 6), 40 + rand(-10, 10), $cl3, $font, substr($text, 2, 1));
@imagettftext($im, rand(28, 32), rand(-30, 30), 115 + rand(-6, 6), 40 + rand(-10, 10), $cl4, $font, substr($text, 3, 1));
@imagettftext($im, rand(28, 32), rand(-30, 30), 150 + rand(-6, 6), 40 + rand(-10, 10), $cl5, $font, substr($text, 4, 1));
for($i=1; $i <= 14; $i++ ) {
    imageline($im, rand(0, 90), rand(0, 60), rand(0, 90), rand(0, 60), $g2);
}
for($i=1; $i <= 14; $i++ ) {
    imageline($im, rand(90, 180), rand(0, 60), rand(90, 180), rand(0, 60), $g2);
}
imagerectangle($im , 0, 0, 179, 55, $b);
$k=1.9;
$im1=imagecreatetruecolor(180 * $k, 56 * $k);
$im2=imagecreatetruecolor(180, 56);
imagecopyresized($im1, $im, 0, 0, 0, 0, 180 * $k, 56 * $k, 180, 56);
imagecopyresampled($im2, $im1, 0, 0, 0, 0, 180, 56, 180 * $k, 56 * $k);
imagepng($im2);
imagedestroy($im);
imagedestroy($im1);
imagedestroy($im2);
?>