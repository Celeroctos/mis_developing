<?php

class AnalysisTypeTechniqueField extends DropDown {

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
			-1 => "Не определена",
			 1 => "Автоматическая",
			 2 => "Ручная"
		];
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "AnalysisTypeTechnique";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Методика типа анализа";
	}
}