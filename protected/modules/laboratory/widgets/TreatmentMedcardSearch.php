<?php

class TreatmentMedcardSearch extends MedcardSearch {

	public $tableConfig = [
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
		"primaryKey" => "medcard_id",
		"click" => null,
	];

	public $id = "treatment-medcard-table";

	public function init() {
		$this->widget("Modal", [
			"title" => "Регистрация направления",
			"body" => $this->createWidget("DirectionCreator", [
				"id" => "register-direction-form",
				"disableControls" => true
			]),
			"buttons" => [
				"treatment-register-direction-modal-save-button" => [
					"text" => "Сохранить",
					"class" => "btn btn-primary",
					"type" => "button",
				],
			],
			"id" => "register-direction-modal"
		]);
		$this->widget("Modal", [
			"title" => "Медицинская карта",
			"body" => "<h4 class=\"text-center no-margin\">Медкарта не выбрана</h4>",
			"buttons" => [],
			"id" => "show-medcard-modal"
		]);
		parent::init();
	}
}