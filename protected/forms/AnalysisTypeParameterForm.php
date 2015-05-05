<?php

class AnalysisTypeParameterForm extends FormModel {

	public $id;
	public $short_name;
	public $name;
	public $checked;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ "short_name", "length", "max" => 20 ],
			[ "name", "length", "max" => 255 ]
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
			"short_name" => [
				"label" => "Краткое наименование анализа",
				"type" => "text",
				"rules" => "required"
			],
			"name" => [
				"label" => "Наименование параметра",
				"type" => "text",
				"rules" => "required"
			],
			"checked" => [
				"label" => "По умолчанию",
				"type" => "YesNo",
				"value" => "1"
			]
		];
	}
}