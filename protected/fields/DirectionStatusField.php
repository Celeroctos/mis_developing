<?php

class DirectionStatusField extends DropDown {

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
			LDirection::STATUS_JUST_CREATED => "Новое",
			LDirection::STATUS_SAMPLE_DONE => "Произведен забор образца",
			LDirection::STATUS_ANALYSIS_DONE => "Проведён анализ",
			LDirection::STATUS_SAMPLE_REPEAT => "Повторный забор образца"
		];
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "DirectionStatus";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Статус направления";
	}
}