<?php

class MedcardGridProvider extends GridProvider {

	public $columns = [
		"card_number" => "Номер",
		"surname" => [
			"label" => "Фамилия",
			"relation" => "patient",
		],
		"name" => [
			"label" => "Имя",
			"relation" => "patient",
		],
		"patronymic" => [
			"label" => "Отчество",
			"relation" => "patient",
		],
		"enterprise_id" => [
			"label" => "МУ направитель",
			"format" => "%{shortname}",
			"relation" => "enterprise",
		],
		"birthday" => [
			"label" => "Дата рождения",
			"format" => "%{birthday}",
			"relation" => "patient",
		]
	];

	public $menu = [
		"controls" => [
			"direction-register-icon" => [
				"icon" => "glyphicon glyphicon-link",
				"label" => "Создать направление"
			],
			"medcard-show-icon" => [
				"icon" => "glyphicon glyphicon-list",
				"label" => "Открыть / Изменить медкарту"
			]
		],
		"mode" => ControlMenu::MODE_ICON
	];

	public function search() {
		return [
			"criteria" => [
				"with" => [
					"patient", "enterprise"
				]
			],
			"sort" => [
				"attributes" => [
					"card_number", "fio", "enterprise_id", "birthday"
				],
				"defaultOrder" => [
					"card_number" => CSort::SORT_ASC
				]
			]
		];
	}

	public function model() {
		return new LMedcard();
	}
}