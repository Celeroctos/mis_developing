<?php

class LPolicyForm extends FormModel {

	public $id;
	public $surname;
	public $name;
	public $patronymic;
	public $birthday;
	public $number;
	public $issue_date;
	public $insurance_id;
	public $document_type;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			$this->createFilter("treatment.policy", [
				"region",
				"insurance",
				"oms_number",
				"type",
				"givedate",
				"status"
			])
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
				"label" => "Первичный ключ",
				"type" => "number"
			],
			"surname" => [
				"label" => "Фамилия",
				"type" => "text",
				"rules" => "required"
			]
		];
	}
}