<?php

class LDirectionSearchForm extends FormModel {

	public $class;
	public $fio;
	public $card_number;
	public $sender_id;
	public $analysis_type_id;

	public function config() {
		return [
			"class" => [
				"type" => "hidden",
				"options" => [
					"data-cleanup" => "false"
				]
			],
			"fio" => [
				"label" => "ФИО пациента",
				"type" => "text"
			],
			"card_number" => [
				"label" => "Номер карты",
				"type" => "text"
			],
			"sender_id" => [
				"label" => "Врач направитель",
				"type" => "DropDown",
				"table" => [
					"name" => "lis.doctor",
					"format" => "%{last_name} %{first_name} %{middle_name}",
					"key" => "id",
					"value" => "first_name, last_name, middle_name"
				]
			],
			"analysis_type_id" => [
				"label" => "Тип анализа",
				"type" => "DropDown",
				"table" => [
					"name" => "lis.analysis_type",
					"format" => "%{name} (%{short_name})",
					"key" => "id",
					"value" => "name, short_name"
				]
			]
		];
	}
}