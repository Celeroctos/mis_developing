<?php

class AnalyzerForm extends FormModel {

	public $id;
	public $analyzer_type_id;
	public $name;
	public $serial_number;
	public $model;
	public $software_version;

	public function backward() {
		return [
			[ "name", "length", "max" => 100 ],
			[ [ "serial_number", "model" ], "length", "max" => 100 ],
			[ "software_version", "length", "max" => 10 ]
		];
	}

	public function config() {
		return [
			"id" => [
				"label" => "Идентификатор",
				"type" => "hidden"
			],
			"analyzer_type_id" => [
				"label" => "Тип анализатора",
				"type" => "dropdown",
				"table" => [
					"name" => "lis.analyzer_type",
					"format" => "%{name} (%{type_name})",
					"key" => "id",
					"value" => "name, type_name"
				],
				"rules" => "required"
			],
			"name" => [
				"label" => "Название анализатора",
				"type" => "text",
				"rules" => "required"
			],
			"serial_number" => [
				"label" => "Серийный номер",
				"type" => "text"
			],
			"model" => [
				"label" => "Модель",
				"type" => "text"
			],
			"software_version" => [
				"label" => "Версия ПО",
				"type" => "text"
			]
		];
	}
}