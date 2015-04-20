<?php

class TreatmentMedcardSearch extends MedcardSearch {

	public $tableConfig = [
		"controls" => [
			"direction-register-icon" => [
				"class" => "glyphicon glyphicon-plus",
				"tooltip" => "Создать направление"
			],
			"medcard-show-icon" => [
				"class" => "glyphicon glyphicon-list-alt",
				"tooltip" => "Открыть / Изменить медкарту"
			]
		],
		"primaryKey" => "medcard_id",
		"click" => null,
	];

	public $id = "treatment-medcard-table";
}