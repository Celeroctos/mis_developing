<?php

class TreatmentMedcardSearch extends MedcardSearch {

	public $tableConfig = [
		"controls" => [
			"direction-register-icon" => [
				"class" => "glyphicon glyphicon-link",
				"tooltip" => "Создать направление"
			],
			"medcard-show-icon" => [
				"class" => "glyphicon glyphicon-list",
				"tooltip" => "Открыть / Изменить медкарту"
			]
		],
		"primaryKey" => "medcard_id",
		"click" => null,
	];

	public $id = "treatment-medcard-table";
}