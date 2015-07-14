<?php

class LSmokeField extends LDropDown {

	public function isBoolean() {
		return true;
	}

	/**
	 * Override that method to return associative array
	 * for drop down list
	 * @return array - Array with data
	 */
	public function data() {
		return [
			0 => "Не курит",
			1 => "Курит"
		];
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "Smoke";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Курение";
	}
}