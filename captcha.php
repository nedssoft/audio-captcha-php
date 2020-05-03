<?php

require_once('audio.php');

session_start();
//You can customize your captcha settings here

$captcha_code = '';
$captcha_image_height = 50;
$captcha_image_width = 300;
$total_characters_on_image = 3;

//The characters that can be used in the CAPTCHA code.
//avoid all confusing characters and numbers (For example: l, 1 and i)
// $possible_captcha_letters = 'bcdfghjkmnpqrstvwxyz23456789';
$possible_words = ['come', 'go', 'cat', 'zap', 'eat', 'shit', 'fuck'];
// $captcha_font = 'monofont.ttf';
// $captcha_font = 'Amputee_Type.ttf';
// $captcha_font = 'xander.ttf';
$captcha_font = 'Jspacker.ttf';

$random_captcha_dots = 50;
$random_captcha_lines = 25;
$captcha_text_color = "0x142864";
$captcha_noise_color = "0x142864";



$rand = array_rand($possible_words, 3);
$v1 = $rand[0];
$v2 = $rand[1];
$v3 = $rand[2];
$captcha_code = "$possible_words[$v1] $possible_words[$v2] $possible_words[$v3]";


$captcha_font_size = $captcha_image_height * 0.65;
$captcha_image = @imagecreate(
	$captcha_image_width,
	$captcha_image_height
	);

/* setting the background, text and noise colours here */
$background_color = imagecolorallocate(
	$captcha_image,
	255,
	255,
	255
	);

$array_text_color = hextorgb($captcha_text_color);
$captcha_text_color = imagecolorallocate(
	$captcha_image,
	$array_text_color['red'],
	$array_text_color['green'],
	$array_text_color['blue']
	);

$array_noise_color = hextorgb($captcha_noise_color);
$image_noise_color = imagecolorallocate(
	$captcha_image,
	$array_noise_color['red'],
	$array_noise_color['green'],
	$array_noise_color['blue']
	);

/* Generate random dots in background of the captcha image */
for( $count=0; $count<$random_captcha_dots; $count++ ) {
imagefilledellipse(
	$captcha_image,
	mt_rand(0,$captcha_image_width),
	mt_rand(0,$captcha_image_height),
	2,
	3,
	$image_noise_color
	);
}

/* Generate random lines in background of the captcha image */
for( $count=0; $count<$random_captcha_lines; $count++ ) {
imageline(
	$captcha_image,
	mt_rand(0,$captcha_image_width),
	mt_rand(0,$captcha_image_height),
	mt_rand(0,$captcha_image_width),
	mt_rand(0,$captcha_image_height),
	$image_noise_color
	);
}

/* Create a text box and add 6 captcha letters code in it */
$text_box = imagettfbbox(
	$captcha_font_size,
	0,
	$captcha_font,
	$captcha_code
	); 
$x = ($captcha_image_width - $text_box[4])/2;
$y = ($captcha_image_height - $text_box[5])/2;
imagettftext(
	$captcha_image,
	$captcha_font_size,
	0,
	$x,
	$y,
	$captcha_text_color,
	$captcha_font,
	$captcha_code
	);

/* Show captcha image in the html page */
// defining the image type to be shown in browser widow
header('Content-Type: image/jpeg'); 
imagejpeg($captcha_image); 

imagedestroy($captcha_image); //destroying the image instance
$_SESSION['captcha'] = $captcha_code;

if ( file_exists('audio.mp3')) {
	unlink('audio.mp3');
}

textToSpeech($captcha_code);

function hextorgb ($hexstring){
  $integar = hexdec($hexstring);
  return array("red" => 0xFF & ($integar >> 0x10),
               "green" => 0xFF & ($integar >> 0x8),
               "blue" => 0xFF & $integar);
			   }
