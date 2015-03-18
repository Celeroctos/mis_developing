<?php

class LAddressField extends LField {

	/**
	 * Override that method to render field base on it's type
	 * @param CActiveForm $form - Form
	 * @param LFormModel $model - Model
	 * @return String - Just rendered field result
	 */
	public function render($form, $model) {
		return LTextField::field()->render2($form, $model, $this->getKey(), $this->getOptions() + [
			"data-form" => LGenerator::generate("form"),
			"data-laboratory" => "address",
		]);
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "Address";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Адрес";
	}
}