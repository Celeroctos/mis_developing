<?php

class LPatientCategoryForm extends FormModel {

	public $id;
	public $pregnant;
	public $smokes;
	public $gestational_age;
	public $menstruation_cycle;
	public $race;

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
				"label" => "Иднетификатор",
				"type" => "hidden"
			],
			"pregnant" => [
				"label" => "Беременна",
				"type" => "YesNo",
				"rules" => "required"
			],
			"smokes" => [
				"label" => "Курит",
				"type" => "YesNo",
				"rules" => "required"
			],
			"gestational_age" => [
				"label" => "Срок беременности",
				"type" => "GestationalAge",
				"rules" => "required"
			],
			"menstruation_cycle" => [
				"label" => "Менструальный цикл",
				"type" => "MenstruationCycle",
				"rules" => "required"
			],
			"race" => [
				"label" => "Расовая принадлежность",
				"type" => "Race",
				"rules" => "required"
			]
		];
	}
}