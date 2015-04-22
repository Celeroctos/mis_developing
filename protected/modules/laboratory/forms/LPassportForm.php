<?php

class LPassportForm extends FormModel {

	public $id;
	public $series;
	public $number;
	public $subdivision_name;
	public $issue_date;
	public $subdivision_code;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ "id", "hide", "on" => "treatment" ]
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
			"series" => [
				"label" => "Серия",
				"type" => "number",
				"rules" => "required"
			],
			"number" => [
				"label" => "Номер",
				"type" => "number",
				"rules" => "required"
			],
			"subdivision_name" => [
				"label" => "Подразделение",
				"type" => "text",
				"rules" => "required"
			],
			"issue_date" => [
				"label" => "Дата выдачи",
				"type" => "date",
				"rules" => "required"
			],
			"subdivision_code" => [
				"label" => "Код подразделения",
				"type" => "number",
				"rules" => "required"
			]
		];
	}
}