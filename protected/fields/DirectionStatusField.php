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
			LDirection::STATUS_TREATMENT_ROOM => "Находится в процедурном кабинет",
			LDirection::STATUS_LABORATORY => "Находится в лаборатории",
			LDirection::STATUS_READY => "Анализ завершен",
			LDirection::STATUS_TREATMENT_REPEAT => "Отправено на повторный забор образца"
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