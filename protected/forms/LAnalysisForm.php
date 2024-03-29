<?php

class LAnalysisForm extends FormModel {

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ "id", "required", "on" => [ "update", "search" ] ],
			[ "id", "hidden", "on" => "register" ],
			[ "medcard_number", "length", "max" => 50 ]
		];
	}

	/**
	 * Override that method to return config. Config should return array associated with
	 * model's variables. Every field must contains 3 parameters:
	 *  + label - Variable's label, will be displayed in the form
	 *  + type - Input type, check out field folder and it's abstract classes
	 *  + rules - Basic form's Yii rules, such as 'required' or 'numeric' etc
	 * @return Array - ActiveRecord's config
	 * @see Field, LDropDown
	 */
	public function config() {
		return [
			"id" => [
				"label" => "Идентификатор",
				"type" => "hidden"
			],
			"registration_date" => [
				"label" => "Дата регистрации",
				"type" => "date",
				"rules" => "safe",
				"hidden" => "true"
			],
			"direction_id" => [
				"label" => "Направление",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"name" => "lis.direction",
					"key" => "id",
					"value" => "barcode"
				]
			],
			"doctor_id" => [
				"label" => "Врач",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"name" => "mis.doctors",
					"format" => "%{first_name} %{last_name}",
					"key" => "id",
					"value" => "first_name, last_name"
				]
			],
			"medcard_number" => [
				"label" => "Номер ЛКП",
				"type" => "text",
				"rules" => "required"
			]
		];
	}
}