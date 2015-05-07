<?php

class BarcodeGenerator {

	const DEFAULT_LENGTH = 8;

	/**
	 * Get barcode generator singleton instance
	 * @return BarcodeGenerator - Barcode generator
	 */
	public static function getGenerator() {
		if (self::$_barcodeGenerator == null) {
			return self::$_barcodeGenerator = new BarcodeGenerator();
		} else {
			return self::$_barcodeGenerator;
		}
	}

	public function generateImage($text) {
		return CHtml::image("data:image/png;base64,".$this->generateBase64($this->prepare($text)), "", [
			"class" => "barcode"
		]);
	}

	public function generateBase64($text) {
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
		return base64_encode(ob_get_clean());
	}

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

	private static $_barcodeGenerator = null;
}