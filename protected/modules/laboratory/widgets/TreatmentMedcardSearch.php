<?php

class TreatmentMedcardSearch extends MedcardSearch {

	public $tableConfig = [
		"controls" => [
			"treatment-medcard-register-direction-icon" => [
				"class" => "glyphicon glyphicon-plus",
				"options" => [
					"onclick" => "alert($(this).parents('tr:eq(0)').attr('data-id'))"
				],
				"tooltip" => "Создать направление"
			],
			"treatment-medcard-show-medcard-icon" => [
				"class" => "glyphicon glyphicon-list-alt",
				"options" => [
					"onclick" => ""
				],
				"tooltip" => "Открыть / Изменить медкарту"
			]
		],
		"primaryKey" => "medcard_id"
	];

	public $click = null;
	public $id = "treatment-medcard-table";
}