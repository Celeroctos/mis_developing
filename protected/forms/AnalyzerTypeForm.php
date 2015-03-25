<?php

class AnalyzerTypeForm extends FormModel {

	public $id;
	public $type_name;
	public $name;
	public $notes;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ ["type_name", "name"], "length", "max" => 50 ]
		];
	}

	/**
	 * Override that method to return config. Config should return array associated with
	 * model's variables. Every field must contains 3 parameters:
	 *  + label - Variable's label, will be displayed in the form
	 *  + type - Input type (@see _LFormInternalRender#render())
	 *  + rules - Basic form's Yii rules, such as 'required' or 'numeric' etc
	 * @return Array - ActiveRecord's config
	 */
	public function config() {
		return [
			"id" => [
				"label" => "Идентификатор",
				"type" => "hidden"
			],
			"type_name" => [
				"label" => "Название типа анализатора",
				"type" => "text",
				"rules" => "required"
			],
			"name" => [
				"label" => "Название анализатора",
				"type" => "text",
				"rules" => "required"
			],
			"notes" => [
				"label" => "Пометки",
				"type" => "TextArea",
				"rules" => "safe"
			]
		];
	}
}