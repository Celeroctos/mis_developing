<?php

class LGenerator extends CComponent {

	/**
	 * Generate random elements identification number. In future that method can be used
	 * to optimize generation algorithm and return identifications for forms
	 * @param string $prefix - Name of item prefix (for example 'form' or 'modal')
	 * @param int $length - Length of random generated string (will be divided in two)
	 * @return string - Generated string
	 */
	public static function generate($prefix, $length = 10) {
		return $prefix . "-" . Yii::app()->getSecurityManager()->generateRandomString($length);
	}
}