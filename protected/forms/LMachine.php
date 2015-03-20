<?php

class LMachine extends FormModel {

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ "name", "length", "max" => 50 ],
			[ "software_version", "length", "max" => 10 ],
			[ "model", "length", "max" => 20 ]
		];
	}

	/**
	 * Override that method to return config. Config should return array associated with
	 * model's variables. Every field must contains 3 parameters:
	 *  + label - Variable's label, will be displayed in the form
	 *  + type - Input type (@see _LFormInternalRender#render())
	 *  + rules - Basic form's Yii rules, such as 'required' or 'numeric' etc
	 * @return Array - Model's config
	 */
	public function config() {
		return [
			"id" => [
				"label" => "Идентификатор",
				"type" => "hidden"
			],
			"name" => [
				"label" => "Наименование",
				"type" => "text",
				"rules" => "required"
			],
			"serial" => [
				"label" => "Серийный номер",
				"type" => "number",
				"rules" => "required"
			],
			"model" => [
				"label" => "Модель",
				"type" => "text",
				"rules" => "required"
			],
			"software_version" => [
				"label" => "Версия ПО",
				"type" => "text"
			],
			"analyzer_type_id" => [
				"label" => "Тип анализатора",
				"type" => "DropDown",
				"table" => [
					"name" => "lis.analyzer_types",
					"key" => "id",
					"value" => "name"
				],
				"rules" => "required"
			]
		];
	}
}