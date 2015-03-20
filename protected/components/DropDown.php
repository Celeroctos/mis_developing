<?php

abstract class DropDown extends Field {

	/**
	 * Override that method to get current field instance
	 * @param string $class - Name of field's class
	 * @return DropDown - Field object
	 */
	public static function field($class = null) {
		return parent::field($class);
	}

	/**
     * Override that method to return associative array
     * for drop down list
     * @return array - Array with data
     */
    public abstract function data();

	/**
	 * Get some drop down list option by it's key
	 * @param string $key - Data key to get
	 * @return mixed - Value
	 */
	public function getOption($key) {
		$data = $this->getData();
		if (isset($data[$key])) {
			return $data[$key];
		} else {
			return null;
		}
	}

	/**
	 * Get cached array with drop down list data
	 * @param string $key - Data key to get
	 * @return array - Array with drop down list
	 */
	public function getData($key = null) {
		if ($this->data == null) {
			$this->data = $this->data();
		}
		if ($key != null) {
			if (isset($this->data[$key])) {
				return $this->data[$key];
			} else {
				return null;
			}
		}
		return $this->data;
	}

	private $data = null;

	/**
	 * @param CActiveForm $form
	 * @param FormModel $model
	 * @return string
	 */
	public function renderAsRadio($form, $model) {
		$data = $this->data();
		if (isset($data[-1])) {
			unset($data[-1]);
		}
		return $form->radioButtonList($model, $this->getKey(), $data, $this->getOptions() + [
			'value' => $this->getValue(),
			'class' => 'form-control'
		]);
	}

	public function renderAsCheckbox() {
	}

	/**
	 * Override that method to render field base on it's type
	 * @param CActiveForm $form - Form
	 * @param FormModel $model - Model
	 * @return string - Just rendered field result
	 */
	public final function render($form, $model) {
		$data = $this->data();
		if (!$this->isBoolean() && !isset($data[-1])) {
			$data = [ -1 => "Нет" ] + $data;
		}
		return $form->dropDownList($model, $this->getKey(), $data, [
			'placeholder' => $this->getLabel(),
			'id' => $this->getKey(),
			'class' => 'form-control',
			'options' => [ $this->getValue() => [ 'selected' => true ] ]
		]);
	}

	/**
	 * Override that method to make that field as boolean type
	 * @return bool - True, if your subtype is boolean like
	 */
	public function isBoolean() {
		return false;
	}
}