<?php

class UniqueGenerator extends CComponent {

	/**
	 * Generate random elements identification number. In future that method can be used
	 * to optimize generation algorithm and return identifications for forms
     *
	 * @param string $prefix name of item prefix (for example 'form' or 'modal')
	 * @param int $length length of random generated string (will be divided in two)
     *
	 * @return string - Generated string
	 */
	public static function generate($prefix, $length = 10) {
		return $prefix . '-' . bin2hex(Yii::app()->getSecurityManager()->generateRandomBytes($length / 2));
	}
}