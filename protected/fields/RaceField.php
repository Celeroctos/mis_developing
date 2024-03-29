<?php

class RaceField extends DropDown {

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
			1 => "Европеоид",
			2 => "Монголоид",
			3 => "Негроид",
			4 => "Американоид"
		];
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "Race";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Расовая принадлежность";
	}
}