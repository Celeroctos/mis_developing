<?php

class LGuideColumnForm extends FormModel {

	public $id;
	public $name;
	public $type;
	public $guide_id;
	public $lis_guide_id;
	public $position;
	public $display_id;
	public $default_value;

	public function getType() {
		return FieldCollection::getCollection()->getDropDown([
			"Text",
			"TextArea",
			"Number",
			"YesNo",
			"DropDown",
			"Multiple",
			"Date",
			"Smoke",
			"Pregnant",
			"Trimester",
			"Menstruation",
			"Race"
		]);
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
				"type" => "number",
				"rules" => "safe, numerical",
				"hidden" => "true"
			],
			"name" => [
				"label" => "Название столбца",
				"type" => "Text",
				"rules" => "safe, required"
			],
			"type" => [
				"label" => "Тип данных",
				"type" => "DropDown",
				"rules" => "safe, required",
				"update" => "default_value"
			],
			"guide_id" => [
				"label" => "Справочник",
				"type" => "DropDown",
				"rules" => "safe, required",
				"format" => "%{name}",
				"hidden" => "true",
				"table" => [
					"name" => "lis.guide",
					"key" => "id",
					"value" => "name"
				]
			],
			"lis_guide_id" => [
				"label" => "Справочник",
				"type" => "DropDown",
				"format" => "%{name}",
				"hidden" => $this->hasListType(),
				"update" => "display_id",
				"table" => [
					"name" => "lis.guide",
					"key" => "id",
					"value" => "name"
				],
				"rules" => "safe"
			],
			"position" => [
				"label" => "Позиция",
				"type" => "Number",
				"hidden" => "true",
				"options" => [
					"min" => 1
				],
				"rules" => "safe, required"
			],
			"display_id" => [
				"label" => "Отображаемое значение",
				"type" => "DropDown",
				"format" => "%{name}",
				"hidden" => $this->hasListType(),
				"update" => "default_value",
				"rules" => "safe"
			],
			"default_value" => [
				"label" => "Значение по умолчанию",
				"type" => $this->isActive("type") ? $this->type : "hidden",
				"value" => $this->default_value,
				"rules" => "safe"
			]
		];
	}

	public function getDisplayId() {
		if ($this->isActive("lis_guide_id")) {
			$columns = LGuideColumn::model()->findDisplayableAndOrdered("guide_id = :guide_id", [
				$this->lis_guide_id
			]);
			$columns = LGuideColumn::model()->toDropDown($columns);
		} else {
			$columns = [];
		}
		return $columns;
	}

	public function getDefaultValue() {
		if (!$this->isActive("lis_guide_id") || !$this->isActive("display_id") || $this->hasListType()) {
			return [];
		}
		$rows = LGuide::model()->findValuesWithDisplay(
			$this->lis_guide_id, $this->display_id
		);
		if ($this->type == "dropdown") {
			$data = [ -1 => "Нет" ];
		} else {
			$data = [];
		}
		foreach ($rows as $row) {
			$data[$row["id"]] = $row["value"];
		}
		return $data;
	}

	private function hasListType() {
		return !$this->isActive("type") || ($this->type != "dropdown" && $this->type != "multiple");
	}
}