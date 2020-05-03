<?php

require_once('audio.php');

session_start();

class Captcha 
{
	const CAPTCHA_IMAGE_HEIGHT = 50;
	const CAPTCHA_IMAGE_WIDTH = 300;
	const TOTAL_CHARACTERS_ON_IMAGE = 3;
	const POSSIBLE_WORDS = ['coMe', 'Go', 'caT', 'Zap', 'eat', 'sHit', 'fUck', 'sIp', 'liP'];
	const RANDOM_CAPTCHA_DOTS = 50;
	const RANDOM_CAPTCHA_LINES = 25;
	const CAPTCHA_TEXT_COLOR = "142864";
	const CAPTCHA_NOISE_COLOR = "142864";
	const CAPTCHA_FONT = './fonts/raphtalia.ttf';

	protected $captcha_code = '';
	protected $captcha_image = '';
	protected $image_noise_color = '';

	public function __construct()
	{
		$this->generateCode();
		$this->createImage();
		$this->applyColors();
		$this->applyNoise();
	}

	private function generateCode()
	{
		$rand = array_rand(self::POSSIBLE_WORDS, 3);
		$v1 = $rand[0];
		$v2 = $rand[1];
		$v3 = $rand[2];

		$this->captcha_code = self::POSSIBLE_WORDS[$v1] . ' ' . self::POSSIBLE_WORDS[$v2] . ' ' . self::POSSIBLE_WORDS[$v3];
	}

	private function createImage()
	{
		$this->captcha_image = @imagecreate(
			self::CAPTCHA_IMAGE_WIDTH,
			self::CAPTCHA_IMAGE_HEIGHT
		);
	}

	public static function fontSize()
	{
		return self::CAPTCHA_IMAGE_HEIGHT * 0.65;
	}

	public function backgroundColor()
	{
		return imagecolorallocate(
			$this->captcha_image,
			255,
			255,
			255
		);
	}
	public function textColor()
	{
		[$red, $green, $blue] = $this->hexToRgb(self::CAPTCHA_TEXT_COLOR);
		return imagecolorallocate(
			$this->captcha_image,
			$red,
			$green,
			$blue
		);
	}

	public function noiseColor()
	{
		[$red, $green, $blue] = $this->hexToRgb(self::CAPTCHA_NOISE_COLOR);
		$this->image_noise_color = imagecolorallocate(
			$this->captcha_image,
			$red,
			$green,
			$blue
		);
	}

	public function hexToRgb($hex)
	{
		return array_map('hexdec', str_split($hex, 2));
	}

	private function applyColors()
	{
		$this->backgroundColor();
		$this->noiseColor();
	}

	/* Generate random dots in background of the captcha image */
	private function generateDots()
	{
		for ($count = 0; $count < self::RANDOM_CAPTCHA_DOTS; $count++) {
			imagefilledellipse(
				$this->captcha_image,
				mt_rand(0, self::CAPTCHA_IMAGE_WIDTH),
				mt_rand(0, self::CAPTCHA_IMAGE_HEIGHT),
				2,
				3,
				$this->image_noise_color
			);
		}
	}

	/* Generate random lines in background of the captcha image */
	private function generateLines()
	{
		for ($count = 0; $count < self::RANDOM_CAPTCHA_LINES; $count++) {
			imageline(
				$this->captcha_image,
				mt_rand(0, self::CAPTCHA_IMAGE_WIDTH),
				mt_rand(0, self::CAPTCHA_IMAGE_HEIGHT),
				mt_rand(0, self::CAPTCHA_IMAGE_WIDTH),
				mt_rand(0, self::CAPTCHA_IMAGE_HEIGHT),
				$this->image_noise_color
			);
		}
	}

	public function applyNoise()
	{
		$this->generateDots();
		$this->generateLines();
	}

	public function generateCaptcha()
	{
		/* Create a text box and add 3 captcha words code in it */
		$text_box = imagettfbbox(
			self::fontSize(),
			0,
			self::CAPTCHA_FONT,
			$this->captcha_code
		);
		$x = (self::CAPTCHA_IMAGE_WIDTH - $text_box[4]) / 2;
		$y = (self::CAPTCHA_IMAGE_HEIGHT - $text_box[5]) / 2;

		imagettftext(
			$this->captcha_image,
			self::fontSize(),
			0,
			$x,
			$y,
			$this->textColor(),
			self::CAPTCHA_FONT,
			$this->captcha_code
		);
		header('Content-Type: image/jpeg');
		imagejpeg($this->captcha_image);

		imagedestroy($this->captcha_image); //destroying the image instance
		$_SESSION['captcha'] = $this->captcha_code;

		if (file_exists('audio.mp3')) {
			unlink('audio.mp3');
		}

		(new TextToSpeech($this->captcha_code))();
	}
}

(new Captcha())->generateCaptcha();

