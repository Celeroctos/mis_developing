<?php

class LDirectionFormEx extends FormModel {

	public $analysis_type_id;
	public $analysis_parameters;
	public $pregnant;
	public $smokes;
	public $gestational_age;
	public $menstruation_cycle;
	public $race;
	public $history;
	public $comment;
	public $medcard_id;

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
			"analysis_type_id" => [
				"label" => "Тип анализа",
				"type" => "DropDown",
				"table" => [
					"name" => "lis.analysis_type",
					"key" => "id",
					"value" => "name"
				],
				"rules" => "required",
			],
			"analysis_parameters" => [
				"label" => "Параметры анализа",
				"type" => "hidden",
				"rules" => "required"
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
			],
			"history" => [
				"label" => "Медикаментозный анамнез",
				"options" => [
					"rows" => "5"
				],
				"type" => "TextArea"
			],
			"comment" => [
				"label" => "Комментарий",
				"options" => [
					"rows" => "5"
				],
				"type" => "TextArea"
			],
			"medcard_id" => [
				"label" => "Номер медкарты",
				"type" => "hidden",
				"rules" => "required"
			]
		];
	}
}