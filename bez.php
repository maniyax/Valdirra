<?php
##############
# 24.12.2014 #
##############
session_start();
header ('Content-Type: image/png');
$im = imagecreatetruecolor(45,20); // создаем холст 45*20 точек
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 20, 45, $black);
$white = imagecolorallocate($im, 255, 255, 255);

$c1 = mt_rand(1,9);
$c2 = mt_rand(1,9);
$c3 = mt_rand(1,9);
$code = $c1.$c2.$c3;
$_SESSION['bez'] = $code;
$codeimg = $c1.' '.$c2.' '.$c3;
imagestring($im,4,2,2,$codeimg,$white);
imagepng($im);
imagedestroy($im);

exit;
?>