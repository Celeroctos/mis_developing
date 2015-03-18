<?php

class MultipleField extends Field {

	/**
	 * Override that method to render field base on it's type
	 * @param CActiveForm $form - Form
	 * @param FormModel $model - Model
	 * @return String - Just rendered field result
	 */
	public function render($form, $model) {
        return $form->dropDownList($model, $this->getKey(), $this->getData(), $this->getOptions() + [
            'placeholder' => $this->getLabel(),
            'id' => $this->getKey(),
            'class' => 'form-control',
            'value' => $this->getValue(),
            'onchange' => "DropDown && DropDown.change && DropDown.change.call(this)",
            'options' => [ $this->getValue() => [ 'selected' => true ] ],
            'multiple' => true
        ]);
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "Multiple";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Множественный выбор";
	}
}