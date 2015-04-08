<?php

class BarcodeGenerator {

	public static function getDefaultGenerator() {
		if (self::$_barcodeGenerator == null) {
			return self::$_barcodeGenerator = new BarcodeGenerator();
		} else {
			return self::$_barcodeGenerator;
		}
	}

	private static $_barcodeGenerator = null;
}