<?php

class DropDownField extends Field {

	/**
	 * Override that method to render field base on it's type
	 * @param CActiveForm $form - Form
	 * @param FormModel $model - Model
	 * @return String - Just rendered field result
	 */
	public function render($form, $model) {
		$data = $this->getData();
		if (!isset($data[-1]) && !$this->getValue()) {
			$data = [ -1 => "Нет" ] + $data;
		}
		if (!count($data)) {
			$data = [ -1 => "Нет" ];
		}
		if ($this->getValue() !== null) {
			$value = $this->getValue();
		} else {
			$value = -1;
		}
		return $form->dropDownList($model, $this->getKey(), $data, $this->getOptions() + [
			'placeholder' => $this->getLabel(),
			'id' => $this->getKey(),
			'class' => 'form-control',
			'value' => $this->getValue(),
			'options' => [ $value => [ 'selected' => true ] ]
		]);
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "DropDown";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Выпадающий список";
	}
}