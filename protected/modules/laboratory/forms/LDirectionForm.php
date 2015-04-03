<?php

class LDirectionForm extends FormModel {

	public $id;
	public $barcode;
	public $status;
	public $comment;
	public $analysis_type_id;
	public $medcard_id;
	public $sender_id;
	public $sending_date;
	public $treatment_room_employee_id;
	public $laboratory_employee_id;
	public $history;
	public $ward_id;
	public $enterprise_id;
	public $is_repeated;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			$this->createFilter("treatment.edit", [
				"comment",
				"analysis_type",
				"treatment_room_employee_id",
				"laboratory_employee_id",
				"history",
				"ward_id"
			])
		];
	}

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->sender_id = LUserIdentity::get("doctorId");
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
				"type" => "hidden",
				"rules" => "numerical"
			],
			"barcode" => [
				"label" => "Штрих-код",
				"type" => "number",
				"rules" => "required"
			],
            "status" => [
                "label" => "Статус",
                "type" => "DirectionStatus",
                "rules" => "required"
            ],
			"comment" => [
				"label" => "Комментарий",
				"type" => "TextArea"
			],
			"analysis_type_id" => [
				"label" => "Тип анализа",
				"type" => "DropDown",
				"rules" => "required",
                "table" => [
                    "name" => "lis.analysis_type",
                    "key" => "id",
                    "value" => "name"
                ]
			],
			"medcard_id" => [
				"label" => "Медкарта",
				"type" => "number",
				"rules" => "required",
				"hidden" => "true"
			],
			"sender_id" => [
				"label" => "Врач направитель",
				"type" => "number",
				"rules" => "required",
				"hidden" => "true"
			],
			"sending_date" => [
				"label" => "Дата направления",
				"type" => "date",
				"rules" => "required"
			],
			"treatment_room_employee_id" => [
				"label" => "Сотрудник процедурного кабинета",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"format" => "%{last_name} %{first_name}",
					"name" => "mis.doctors",
					"key" => "id",
					"value" => "first_name, last_name",
					"order" => "last_name, first_name"
				]
			],
			"laboratory_employee_id" => [
				"label" => "Сотрудник лаборатории",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"format" => "%{last_name} %{first_name}",
					"name" => "mis.doctors",
					"key" => "id",
					"value" => "first_name, last_name",
					"order" => "last_name, first_name"
				]
			],
            "history" => [
                "label" => "Медикаментозный анамнез",
                "type" => "TextArea"
            ],
			"ward_id" => [
				"label" => "Отдел",
				"type" => "DropDown",
				"rules" => "required",
                "table" => [
                    "name" => "mis.wards",
                    "key" => "id",
                    "value" => "name"
                ]
			],
			"enterprise_id" => [
				"label" => "Направитель",
				"type" => "DropDown",
				"rules" => "required",
				"table" => [
					"name" => "lis.enterprise",
					"key" => "id",
					"value" => "shortname"
				]
			],
		];
	}
}