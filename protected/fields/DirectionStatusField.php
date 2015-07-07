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
			Laboratory_Direction::STATUS_TREATMENT_ROOM => "Находится в процедурном кабинет",
			Laboratory_Direction::STATUS_LABORATORY => "Находится в лаборатории",
			Laboratory_Direction::STATUS_READY => "Анализ завершен",
			Laboratory_Direction::STATUS_TREATMENT_REPEAT => "Отправено на повторный забор образца"
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