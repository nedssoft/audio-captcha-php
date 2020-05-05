<?php

require_once('audio.php');

session_start();

class Captcha 
{
	
	const CAPTCHA_IMAGE_HEIGHT = 50;
	const CAPTCHA_IMAGE_WIDTH = 300;
	const TOTAL_WORDS_ON_IMAGE = 3;
	const POSSIBLE_WORDS = ['coMe', 'Go', 'caT', 'Zap', 'eat', 'leAp', 'meME', 'sIp', 'liP'];
	const RANDOM_CAPTCHA_DOTS = 50;
	const RANDOM_CAPTCHA_LINES = 25;
	const CAPTCHA_TEXT_COLOR = "142864";
	const CAPTCHA_NOISE_COLOR = "142864";
	const CAPTCHA_FONT = __DIR__.'/fonts/raphtalia.ttf';

	private $captcha_code = '';
	private $captcha_image = '';
	private $image_noise_color = '';

	public function __construct()
	{
		$this->generateCode();
		$this->createImage();
		$this->applyColors();
		$this->applyNoise();
	}
	
	/**
	 * Generate the words on the captcha image
	 */
	private function generateCode(): void
	{
		$rand = array_rand(self::POSSIBLE_WORDS, self::TOTAL_WORDS_ON_IMAGE);
		$v1 = $rand[0];
		$v2 = $rand[1];
		$v3 = $rand[2];

		$this->captcha_code = self::POSSIBLE_WORDS[$v1] . ' ' . self::POSSIBLE_WORDS[$v2] . ' ' . self::POSSIBLE_WORDS[$v3];
	}

	/**
	 * Create the captcha image
	 */
	private function createImage(): void
	{
		try {
			$this->captcha_image = imagecreate(
				self::CAPTCHA_IMAGE_WIDTH,
				self::CAPTCHA_IMAGE_HEIGHT
			);
		} catch(\Exception $e) {
			die($e->getMessage());
		}
	}
   
	/**
	 * Calculate the font size of the captcha characters
	 */
	public static function fontSize()
	{
		return self::CAPTCHA_IMAGE_HEIGHT * 0.65;
	}

	/**
	 * Add background image to the captcha image
	 */
	public function backgroundColor()
	{
		return imagecolorallocate(
			$this->captcha_image,
			100,
			120,
			130
		);
	}

	/**
	 * Generate the text color of the captcha image
	 */
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

	/**
	 * Generate noise background color for the image
	 */
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
  
	/**
	 * Convert hex color to RGB color
	 */
	public function hexToRgb(string $hex): array
	{
		return array_map('hexdec', str_split($hex, 2));
	}
 
	/**
	 * Apply the background color and noise color
	 */
	private function applyColors(): void
	{
		$this->backgroundColor();
		$this->noiseColor();
	}

	/* Generate random dots in background of the captcha image */
	private function generateDots(): void
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

	/* 
	* Generate random lines in background of the captcha image 
	*/
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
	
	/**
	 * Apply the noise effect
	 */
	public function applyNoise()
	{
		$this->generateDots();
		$this->generateLines();
	}

	/**
	 * Now that all the tools are set
	 * Let us generate the captcha image and render on the browser
	 */
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

		// Delete the previous captcha audio if it exists
		if (file_exists('audio.mp3')) {
			unlink('audio.mp3');
		}
		 
		// Now that the captcha is generated, let us also generate the audio
		// to accommodate everyone
		(new TextToSpeech($this->captcha_code))();
	}
}

// Instantiate the Captcha class and generate the captcha
(new Captcha())->generateCaptcha();

