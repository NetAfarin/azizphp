<?php
session_start();

$captchaCode = rand(1000, 9999);

$_SESSION['captcha'] = [
    'code' => $captchaCode,
    'time' => time()
];

header('Content-Type: image/png');
$image = imagecreatetruecolor(100, 40);

$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);

imagefilledrectangle($image, 0, 0, 100, 40, $bgColor);

imagestring($image, 5, 22, 10, $captchaCode, $textColor);

imagepng($image);
imagedestroy($image);
