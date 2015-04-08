<?php

class LMedcardForm extends FormModel {

	public $id;
	public $mis_medcard;
	public $sender_id;
	public $patient_id;
	public $card_number;
	public $enterprise_id;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			// hide identification number and reference to patient on medcard edit from treatment room
			[ [ "id", "patient_id" ], "hide", "on" => "treatment.edit" ],
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
				"type" => "hidden",
				"rules" => "numerical"
			],
			"card_number" => [
				"label" => "Номер карты",
				"type" => "text",
				"options" => [
					"readonly" => "true"
				],
				"rules" => "required"
			],
			"mis_medcard" => [
				"label" => "Номер карты в МИС",
				"type" => "text",
				"options" => [
					"readonly" => "true"
				],
				"rules" => "safe"
			],
			"sender_id" => [
				"label" => "Врач направитель",
				"type" => "DropDown",
				"table" => [
					"name" => "mis.doctors",
					"key" => "id",
					"value" => "last_name, first_name",
					"format" => "%{last_name} %{first_name}",
					"order" => "last_name, first_name"
				],
				"rules" => "required"
			],
			"patient_id" => [
				"label" => "Идентификатор пациента",
				"type" => "number",
				"rules" => "safe"
			]
		];
	}
}