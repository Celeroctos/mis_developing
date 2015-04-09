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

	/**
	 * Generate barcode for direction or something else
	 * @param int $degree - Value's range via it's degree
	 * @return int - Generated random value
	 * @throws CException
	 */
	public function generate($degree = 33) {
		if (($bytes = Yii::app()->getSecurityManager()->generateRandomBytes($degree / 8 + 1)) === false) {
		 	throw new CException("Can't generate barcode number \"" .openssl_error_string(). "\"");
		} else {
			return $bytes & ~(1 << $degree);
		}
	}

	private static $_barcodeGenerator = null;
}