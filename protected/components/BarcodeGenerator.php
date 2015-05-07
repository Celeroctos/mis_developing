<?php

class BarcodeGenerator {

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

	public function generate($text) {
		$colorBack = new BCGColor(255, 255, 255);
		$code = new BCGcode39(); // Or another class name from the manual
		$code->setScale(2); // Resolution
		$code->setThickness(30); // Thickness
		$code->setBackgroundColor($colorBack); // Color of spaces
		$code->parse($text); // Text
		$drawing = new BCGDrawing('', $colorBack);
		$drawing->setBarcode($code);
		$drawing->draw();
		ob_start();
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
		$bin = ob_get_clean();
		return CHtml::image("data:image/png;base64,".base64_encode($bin));
	}

	private static $_barcodeGenerator = null;
}