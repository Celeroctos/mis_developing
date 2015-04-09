<?php

class DateField extends Field {

	const HTML5 = true;

	/**
	 * Override that method to render field base on it's type
	 * @param CActiveForm $form - Form
	 * @param FormModel $model - Model
	 * @return String - Just rendered field result
	 */
	public function render($form, $model) {
		if (static::HTML5 == false) {
			$uniqueKey = UniqueGenerator::generate("datetimepicker");
			ob_start();
			print CHtml::openTag("div", [
				"class" => "input-group",
				"id" => $uniqueKey
			]);
			print CHtml::activeTextField($model, $this->getKey(), [
				"class" => "form-control"
			]);
			print CHtml::tag("span", [
				"class" => "input-group-addon",
				"onclick" => "$('#{$uniqueKey}').datepicker()"
			], CHtml::tag("span", [
				"class" => "glyphicon glyphicon-calendar"
			]));
			print CHtml::closeTag("div");
			return ob_get_clean();
		} else {
			return $form->dateField($model, $this->getKey(), [
				"id" => $this->getKey(),
				"class" => "form-control",
				"value" => $this->getValue()
			]);
		}
	}

	/**
	 * Override that method to return field's key
	 * @return String - Key
	 */
	public function key() {
		return "Date";
	}

	/**
	 * Override that method to return field's label
	 * @return String - Label
	 */
	public function name() {
		return "Дата";
	}
}