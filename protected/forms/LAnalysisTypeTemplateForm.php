<?php

class LAnalysisTypeTemplateForm extends FormModel {

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ "id", "required", "on" => [ "update", "search" ] ]
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
			"analysis_type_id" => [
				"label" => "Тип анализа",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"name" => "lis.analysis_types",
					"key" => "id",
					"value" => "name"
				]
			],
			"analysis_param_id" => [
				"label" => "Параметр анализа",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"name" => "lis.analysis_params",
					"key" => "id",
					"value" => "name"
				]
			],
			"checked" => [
				"label" => "Сделать по умолчанию",
				"type" => "YesNo",
				"rules" => "required"
			]
		];
	}
}