<?php

class LDirectionSearchForm extends FormModel {

	public $widget;
	public $provider;
	public $config;
	public $surname;
	public $name;
	public $patronymic;
	public $card_number;
	public $analysis_type_id;

	public function config() {
		return [
			"widget" => [
				"type" => "hidden",
				"options" => [
					"data-cleanup" => "false"
				]
			],
			"provider" => [
				"type" => "hidden",
				"options" => [
					"data-cleanup" => "false"
				]
			],
			"config" => [
				"type" => "hidden",
				"options" => [
					"data-cleanup" => "false"
				]
			],
			"surname" => [
				"label" => "Фамилия",
				"type" => "text"
			],
			"name" => [
				"label" => "Имя",
				"type" => "text"
			],
			"patronymic" => [
				"label" => "Отчество",
				"type" => "text"
			],
			"card_number" => [
				"label" => "Карта",
				"type" => "text"
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