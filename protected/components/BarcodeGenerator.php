<?php

class BarcodeGenerator {

	const DEFAULT_LENGTH = 8;

	/**
	 * Get generator method for backward compatibility
	 *
	 * @param $text string|int with text, which should be
	 * 	encode as barcode
	 *
	 * @return static new instance of current class
	 */
	public static function createGenerator($text) {
		return new static($text);
	}

	/**
	 * Construct barcode generator with text, it generates
	 * base64 encoded text and cached it
	 *
	 * @param $text string|int with text, which should be
	 * 	encode as barcode
	 *
	 * @throws CException if text empty
	 */
	public function __construct($text) {
		$this->generate($text);
	}

	/**
	 * Get PNG image with encoded barcode
	 *
	 * @param $options array with image's HTML
	 * 	options
	 *
	 * @return string with HTML image
	 *
	 * @throws CException if barcode hasn't been generated
	 */
	public function getImage($options = []) {
		if (!$this->_cached) {
			throw new CException("Barcode hasn't been generated");
		}
		return CHtml::image("data:image/png;base64,".$this->_cached, "", [
			"class" => "barcode"
		] + $options);
	}

	/**
	 * Generate base64 encoded barcode image, that can be
	 * converted to image file or something else, you can
	 * set [new] flag to regenerate barcode
	 *
	 * @param $text string|int with barcode value, it will be
	 * 	aligned to [DEFAULT_LENGTH] symbols
	 *
	 * @param $new bool should barcode be regenerated or
	 * 	on several invokes it will return cached value
	 *
	 * @return string with base64 encode barcode
	 * @throws CException if text empty
	 */
	public function generate($text, $new = false) {
		if ($this->_cached && !$new) {
			return $this->_cached;
		} else if (empty($text)) {
			throw new CException("Generation text can't be empty");
		}
		$colorBack = new BCGColor(255, 255, 255);
		$code = new BCGcode39(); // Or another class name from the manual
		$code->setScale(2); // Resolution
		$code->setThickness(30); // Thickness
		$code->setBackgroundColor($colorBack); // Color of spaces
		$code->parse($this->prepare($text)); // Text
		$drawing = new BCGDrawing('', $colorBack);
		$drawing->setBarcode($code);
		$drawing->draw();
		ob_start();
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
		return $this->_cached = base64_encode(ob_get_clean());
	}

	/**
	 * Align text to [DEFAULT_LENGTH] symbols
	 * @param $text string|int with barcode text
	 * @return string align to [DEFAULT_LENGTH] symbols
	 */
	private function prepare($text) {
		$str = "$text";
		if (($length = strlen($str)) == static::DEFAULT_LENGTH) {
			return $str;
		}
		for ($i = 0; $i < static::DEFAULT_LENGTH - $length; $i++) {
			$text = "0".$text;
		}
		return $text;
	}

	private $_cached = null;
}